from fastapi import FastAPI, UploadFile, File, Request
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
import os
import logging
import numpy as np  # Thêm import này
from models import model, CLASS_NAMES
from utils import preprocess_image
from retrieval import qa_chain
from config import IMAGE_DIR

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Khởi tạo FastAPI
app = FastAPI(title="Ceramic Classification and Chatbot API")

# Cấu hình file tĩnh
app.mount("/static", StaticFiles(directory="."), name="static")

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

# Dự đoán và chatbot
@app.post("/predict")
async def predict(file: UploadFile = File(...)):
    try:
        image_bytes = await file.read()
        logger.info(f"Đang xử lý ảnh: {file.filename}")
        processed_image = preprocess_image(image_bytes)
        predictions = model.predict(processed_image)
        predicted_class_idx = np.argmax(predictions[0])  # Sử dụng np ở đây
        confidence = float(predictions[0][predicted_class_idx])
        predicted_class = CLASS_NAMES[predicted_class_idx]

        # Tạo truy vấn cho chatbot
        llm_response = None
        if qa_chain and predicted_class:
            query = f"Cho tôi thông tin chi tiết về gốm sứ '{predicted_class}'. Vui lòng cung cấp thông tin về nguồn gốc, ý nghĩa và các chi tiết liên quan."
            logger.info(f"Gửi truy vấn tới RetrievalQA: {query}")
            result = qa_chain.invoke({"query": query})
            llm_response = result["result"]
            if not llm_response or llm_response.strip() == "":
                llm_response = f"Không tìm thấy thông tin chi tiết về '{predicted_class}' trong dữ liệu hiện tại."
        else:
            llm_response = "Hệ thống chatbot không khả dụng hoặc không có nhãn nhận diện."

        result = {
            "predicted_class": predicted_class,
            "confidence": confidence,
            "llm_response": llm_response
        }
        logger.info(f"Ảnh được dự đoán là: {predicted_class}")
        return result
    except Exception as e:
        logger.error(f"Lỗi khi xử lý: {str(e)}")
        return {"error": str(e)}