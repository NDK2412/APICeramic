# routes.py
from fastapi import FastAPI, UploadFile, File, Request, HTTPException, Header
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
from pydantic import BaseModel
import os
import logging
import numpy as np
from models import model, CLASS_NAMES
from utils import preprocess_image
from retrieval import get_ceramic_info
from config import IMAGE_DIR, GOOGLE_API_KEY
from system_controller import SystemController

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Khởi tạo FastAPI
app = FastAPI(title="API Phân loại Gốm Sứ")

# Cấu hình file tĩnh
app.mount("/static", StaticFiles(directory="."), name="static")

# Biến toàn cục lưu cấu hình LLM
llm_config = {
    "provider": "gemini",  # Mặc định là Gemini
    "api_key": GOOGLE_API_KEY  # Lấy từ config.py
}

# Model cho dữ liệu cập nhật LLM
class LLMConfig(BaseModel):
    model: str
    api_key: str

# Trang chính
@app.get("/", response_class=HTMLResponse)
async def read_root():
    with open("index.html", "r", encoding="utf-8") as f:
        return f.read()

# Lấy danh sách ảnh
@app.get("/images")
async def get_images():
    image_files = [f for f in os.listdir(IMAGE_DIR) if f.endswith(('.jpg', '.jpeg', '.png'))]
    return {"images": image_files}

# Router chat độc lập
@app.post("/chat")
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
@app.post("/predict")
async def predict(file: UploadFile = File(...), api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        image_bytes = await file.read()
        logger.info(f"Đang xử lý ảnh: {file.filename}")
        processed_image = preprocess_image(image_bytes)
        predictions = model.predict(processed_image)
        predicted_class_idx = np.argmax(predictions[0])
        predicted_class = CLASS_NAMES[predicted_class_idx]

        llm_response = get_ceramic_info(predicted_class, llm_config["provider"], llm_config["api_key"])

        result = {
            "predicted_class": predicted_class,
            "llm_response": llm_response
        }
        logger.info(f"Ảnh được dự đoán là: {predicted_class}")
        return result
    except Exception as e:
        logger.error(f"Lỗi khi xử lý: {str(e)}")
        return {"error": f"Lỗi: Không thể xử lý ảnh. Vui lòng kiểm tra API key của {llm_config['provider']} hoặc thử lại."}

# Cập nhật cấu hình LLM
@app.post("/update-llm")
async def update_llm(config: LLMConfig, api_key: str = Header(...)):
    try:
        # Xác thực API key
        expected_api_key = os.getenv("API_KEY", "AuwTLoaTGAWYm2HmDzV0i9ahfemzky")
        if api_key != expected_api_key:
            raise HTTPException(status_code=401, detail="Khóa API không hợp lệ")

        # Kiểm tra provider hợp lệ
        if config.model.lower() not in ["gemini", "openai"]:
            raise HTTPException(status_code=400, detail="Nhà cung cấp LLM không hợp lệ. Phải là 'gemini' hoặc 'openai'.")

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
@app.get("/system-stats")
async def get_system_stats():
    return await SystemController.get_system_stats()