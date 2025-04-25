# routes.py
from fastapi import FastAPI, UploadFile, File, Request, HTTPException, Header
from fastapi.responses import HTMLResponse, JSONResponse
from fastapi.staticfiles import StaticFiles
from pydantic import BaseModel
import os
import logging
import numpy as np
from models import current_model, current_class_names, switch_model, current_model_type, load_model, load_state
from utils import preprocess_image
from retrieval import get_ceramic_info
from config import IMAGE_DIR, GOOGLE_API_KEY, DEFAULT_MODEL
from system_controller import SystemController
from models import current_model, current_class_names, switch_model, current_model_type
import asyncio
from threading import Lock
from fastapi.middleware import Middleware

state_lock = Lock()
# Khóa toàn cục
model_lock = asyncio.Lock()

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Khởi tạo FastAPI với cấu hình docs tiếng Việt
app = FastAPI(
    title="API Phân loại Gốm Sứ",
    description="""**API hỗ trợ phân loại đồ gốm sứ** bằng mô hình học máy kết hợp với LLM để cung cấp thông tin chi tiết.

    ### Chức năng chính:
    - Phân loại ảnh đồ gốm sứ thành các loại khác nhau
    - Hỗ trợ 2 chế độ phân loại: 66 lớp và 67 lớp
    - Tích hợp chat bot cung cấp thông tin về đồ gốm sứ
    - Quản lý và chuyển đổi giữa các mô hình
    """,
    version="1.0.0",
    contact={
        "name": "Nhóm phát triển",
        "email": "support@ceramic-classification.com",
    },
    license_info={
        "name": "MIT",
    },
    openapi_tags=[
        {
            "name": "Phân loại",
            "description": "Các endpoint liên quan đến phân loại ảnh gốm sứ",
        },
        {
            "name": "Chat",
            "description": "Tương tác với chatbot về thông tin gốm sứ",
        },
        {
            "name": "Quản lý Mô hình",
            "description": "Quản lý và chuyển đổi giữa các mô hình phân loại",
        },
        {
            "name": "LLM",
            "description": "Quản lý cấu hình mô hình ngôn ngữ lớn (LLM)",
        },
        {
            "name": "Hệ thống",
            "description": "Theo dõi và giám sát hệ thống",
        }
    ],
    docs_url="/api-docs",
    redoc_url=None,
    openapi_url="/api/openapi.json",
    swagger_ui_parameters={"defaultModelsExpandDepth": -1}  # Ẩn phần model mẫu
)


# Middleware xác thực API key

# Cấu hình file tĩnh
app.mount("/static", StaticFiles(directory="."), name="static")

# Biến toàn cục lưu cấu hình LLM
llm_config = {
    "provider": "gemini",  # Mặc định là Gemini
    "api_key": GOOGLE_API_KEY  # Lấy từ config.py
}


# Model cho dữ liệu cập nhật LLM
class LLMConfig(BaseModel):
    """
    Cấu hình cho mô hình ngôn ngữ lớn (LLM)

    Attributes:
        model: Loại mô hình ('gemini' hoặc 'openai')
        api_key: Khóa API cho dịch vụ LLM
    """
    model: str
    api_key: str


# Model cho yêu cầu chuyển đổi mô hình
class ModelSwitchRequest(BaseModel):
    """
    Yêu cầu chuyển đổi mô hình phân loại

    Attributes:
        model_path: Đường dẫn đến file .h5 nếu dùng mô hình tùy chỉnh
        class_names: Danh sách tên lớp nếu dùng mô hình tùy chỉnh
        model_type: Loại mô hình ('66' hoặc '67') nếu dùng mô hình mặc định
    """
    model_path: str = None
    class_names: list = None
    model_type: str = None


# Trang chính
@app.get("/",
         response_class=HTMLResponse,
         tags=["Hệ thống"],
         summary="Trang chủ ứng dụng",
         description="Hiển thị giao diện web tương tác với API")
async def read_root():
    with open("index.html", "r", encoding="utf-8") as f:
        return f.read()


# Lấy danh sách ảnh
@app.get("/images",
         tags=["Phân loại"],
         summary="Lấy danh sách ảnh mẫu",
         description="Trả về danh sách các ảnh gốm sứ mẫu trong thư mục IMAGE_DIR")
async def get_images():
    image_files = [f for f in os.listdir(IMAGE_DIR) if f.endswith(('.jpg', '.jpeg', '.png'))]
    return {"images": image_files}


# Router chat độc lập
@app.post("/chat",
          tags=["Chat"],
          summary="Chat về gốm sứ",
          description="""Gửi tin nhắn hỏi về các thông tin liên quan đến gốm sứ.

          **Yêu cầu:**
          - Header phải chứa API key hợp lệ
          - Body chứa message cần hỏi
          """,
          response_description="Phản hồi từ LLM với thông tin được yêu cầu")
async def chat(request: Request, api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        data = await request.json()
        message = data.get("message", "").strip()

        if not message:
            return {"response": "Vui lòng gửi một tin nhắn hợp lệ."}

        logger.info(f"Nhận tin nhắn từ người dùng: {message}")
        llm_response = get_ceramic_info(message, llm_config["provider"], llm_config["api_key"])
        logger.info(f"Phản hồi từ {llm_config['provider']}: {llm_response}")
        return {"response": llm_response}
    except Exception as e:
        logger.error(f"Lỗi khi xử lý chat: {str(e)}")
        return {"response": f"Lỗi: {str(e)}"}


# Dự đoán và lấy thông tin
@app.post("/predict",
          tags=["Phân loại"],
          summary="Phân loại ảnh gốm sứ",
          description="""Nhận ảnh đầu vào và trả về kết quả phân loại cùng thông tin từ LLM.

          **Yêu cầu:**
          - File ảnh gửi dưới dạng form-data
          - Header chứa API key hợp lệ
          """,
          response_description="Kết quả phân loại và thông tin mở rộng")
async def predict(file: UploadFile = File(...), api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        image_bytes = await file.read()
        logger.info(f"Đang xử lý ảnh: {file.filename}")
        processed_image = preprocess_image(image_bytes)
        predictions = current_model.predict(processed_image)
        predicted_class_idx = np.argmax(predictions[0])
        predicted_class = current_class_names[predicted_class_idx]

        llm_response = get_ceramic_info(predicted_class, llm_config["provider"], llm_config["api_key"])

        result = {
            "predicted_class": predicted_class,
            "llm_response": llm_response
        }
        logger.info(f"Ảnh được dự đoán là: {predicted_class}")
        return result
    except Exception as e:
        logger.error(f"Lỗi khi xử lý: {str(e)}")
        return {
            "error": f"Lỗi: Không thể xử lý ảnh. Vui lòng kiểm tra API key của {llm_config['provider']} hoặc thử lại."}


# Reset mô hình về trạng thái mặc định (66 class)
@app.post("/reset-model",
          tags=["Quản lý Mô hình"],
          summary="Reset về mô hình mặc định",
          description="""Đưa mô hình về trạng thái mặc định (66 lớp phân loại).

          **Yêu cầu:**
          - API key hợp lệ
          """,
          response_description="Trạng thái reset mô hình")
async def reset_model(api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        # Tải lại mô hình mặc định (66 class)
        load_model(os.path.join(os.path.dirname(__file__), DEFAULT_MODEL), "66")
        logger.info("Mô hình đã được reset về trạng thái mặc định: 66 class")

        return {
            "status": "success",
            "message": "Mô hình đã được reset về trạng thái mặc định: 66 class",
            "class_count": len(current_class_names)
        }
    except Exception as e:
        logger.error(f"Lỗi khi reset mô hình: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Lỗi khi reset mô hình: {str(e)}")


# Chuyển đổi mô hình
@app.post("/switch-model",
          tags=["Quản lý Mô hình"],
          summary="Chuyển đổi giữa các mô hình",
          description="""Cho phép chuyển đổi giữa mô hình 66 lớp và 67 lớp hoặc dùng mô hình tùy chỉnh.

          **Yêu cầu:**
          - API key hợp lệ
          - Có thể chỉ định model_path và class_names để dùng mô hình tùy chỉnh
          - Hoặc chỉ định model_type ('66' hoặc '67') để chuyển đổi giữa 2 mô hình mặc định
          """,
          response_description="Trạng thái chuyển đổi mô hình")
async def switch_model_endpoint(request: ModelSwitchRequest, api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            logger.error("Khóa API không hợp lệ")
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        # Đảm bảo việc chuyển đổi mô hình và trạng thái được thực hiện đồng bộ
        with state_lock:
            # Tải trạng thái từ file trước khi chuyển đổi
            load_state()

            if request.model_path:
                # Kiểm tra file mô hình tồn tại
                if not os.path.exists(request.model_path):
                    logger.error("Đường dẫn mô hình không tồn tại.")
                    raise HTTPException(status_code=400, detail="Đường dẫn mô hình không tồn tại.")
                if not request.model_path.endswith('.h5'):
                    logger.error("Mô hình phải có định dạng .h5.")
                    raise HTTPException(status_code=400, detail="Mô hình phải có định dạng .h5.")
                # Kiểm tra số lượng class nếu có danh sách class tùy chỉnh
                if not request.class_names or len(request.class_names) <= 0:
                    logger.error("Danh sách class phải được cung cấp và không được rỗng khi sử dụng mô hình tùy chỉnh.")
                    raise HTTPException(status_code=400,
                                        detail="Danh sách class phải được cung cấp và không được rỗng khi sử dụng mô hình tùy chỉnh.")
                # Chuyển đổi sang mô hình tùy chỉnh
                switch_model(model_path=request.model_path, class_names=request.class_names)
            else:
                # Chuyển đổi giữa 66 và 67 class dựa trên model_type từ body
                if request.model_type not in ["66", "67"]:
                    logger.error("model_type không hợp lệ")
                    raise HTTPException(status_code=400, detail="model_type phải là '66' hoặc '67'.")
                if current_model_type != request.model_type:
                    switch_model()  # Chỉ gọi switch_model nếu trạng thái hiện tại không khớp
                else:
                    logger.info(f"Không cần chuyển đổi, mô hình đã ở trạng thái: {request.model_type}")

            # Tải lại trạng thái từ file sau khi chuyển đổi
            load_state()

            # Kiểm tra tính đồng bộ của trạng thái
            if current_model_type not in ["66", "67"] and not request.model_path:
                logger.error("Trạng thái mô hình không hợp lệ sau khi chuyển đổi.")
                raise HTTPException(status_code=500, detail="Trạng thái mô hình không hợp lệ sau khi chuyển đổi.")
            if len(current_class_names) == 0:
                logger.error("Danh sách class bị trống sau khi chuyển đổi.")
                raise HTTPException(status_code=500, detail="Danh sách class bị trống sau khi chuyển đổi.")

        # Tạo thông điệp phản hồi
        message = f"Đã chuyển đổi mô hình thành công"
        logger.info(message)

        # Trả về phản hồi thành công
        return {
            "status": "success",
            "message": message,
            "class_count": len(current_class_names)
        }
    except Exception as e:
        logger.error(f"Lỗi khi chuyển đổi mô hình: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Lỗi khi chuyển đổi mô hình: {str(e)}")


# Cập nhật cấu hình LLM
@app.post("/update-llm",
          tags=["LLM"],
          summary="Cập nhật cấu hình LLM",
          description="""Thay đổi nhà cung cấp LLM (Gemini/OpenAI) và API key tương ứng.

          **Yêu cầu:**
          - API key hợp lệ
          - Chỉ định model ('gemini' hoặc 'openai')
          - Cung cấp API key mới
          """,
          response_description="Trạng thái cập nhật")
async def update_llm(config: LLMConfig, api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        # Kiểm tra provider hợp lệ
        if config.model.lower() not in ["gemini", "openai"]:
            raise HTTPException(status_code=400,
                                detail="Nhà cung cấp LLM không hợp lệ. Phải là 'gemini' hoặc 'openai'.")

        # Kiểm tra API key trước khi lưu
        if not config.api_key:
            raise HTTPException(status_code=400, detail="API key không được để trống.")

        # Cập nhật cấu hình toàn cục
        llm_config["provider"] = config.model.lower()
        llm_config["api_key"] = config.api_key

        logger.info(f"Cấu hình LLM đã được cập nhật: provider={llm_config['provider']}")
        return {"status": "success", "model": llm_config["provider"]}
    except Exception as e:
        logger.error(f"Lỗi khi cập nhật LLM: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Lỗi khi cập nhật LLM: {str(e)}")


# Lấy thông tin hệ thống
@app.get("/system-stats",
         tags=["Hệ thống"],
         summary="Thống kê hệ thống",
         description="Lấy các thông số về hiệu năng và trạng thái hệ thống",
         response_description="Các chỉ số hệ thống")
async def get_system_stats():
    return await SystemController.get_system_stats()