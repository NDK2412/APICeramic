# routes.py
from fastapi import FastAPI, UploadFile, File, Request, HTTPException, Header
from fastapi.responses import HTMLResponse
from fastapi.staticfiles import StaticFiles
import os
import logging
import numpy as np
from models import model, CLASS_NAMES
from utils import preprocess_image
from retrieval import get_ceramic_info
from config import IMAGE_DIR, API_KEY

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Khởi tạo FastAPI
app = FastAPI(title="Ceramic Classification API")

# Cấu hình file tĩnh
app.mount("/static", StaticFiles(directory="."), name="static")

# Hàm kiểm tra API Key
def verify_api_key(api_key: str = Header(...)):
    if api_key != API_KEY:
        raise HTTPException(status_code=401, detail="Invalid API Key")
    return api_key

# Trang chính
@app.get("/", response_class=HTMLResponse)
async def read_root():
    with open("index.html", "r", encoding="utf-8") as f:
        return f.read()

# Lấy danh sách ảnh
@app.get("/images")
async def get_images(api_key: str = Header(...)):
    verify_api_key(api_key)
    image_files = [f for f in os.listdir(IMAGE_DIR) if f.endswith(('.jpg', '.jpeg', '.png'))]
    return {"images": image_files}

# Router chat độc lập
@app.post("/chat")
async def chat(request: Request, api_key: str = Header(...)):
    verify_api_key(api_key)
    try:
        data = await request.json()
        message = data.get("message", "").strip()

        if not message:
            return {"response": "Vui lòng gửi một tin nhắn hợp lệ."}

        logger.info(f"Nhận tin nhắn từ người dùng: {message}")
        llm_response = get_ceramic_info(message)
        logger.info(f"Phản hồi từ Google/Gemini: {llm_response}")
        return {"response": llm_response}
    except Exception as e:
        logger.error(f"Lỗi khi xử lý chat: {str(e)}")
        return {"response": f"Lỗi: {str(e)}"}

# Dự đoán và lấy thông tin
@app.post("/predict")
async def predict(file: UploadFile = File(...), api_key: str = Header(...)):
    verify_api_key(api_key)
    try:
        image_bytes = await file.read()
        logger.info(f"Đang xử lý ảnh: {file.filename}")
        processed_image = preprocess_image(image_bytes)
        predictions = model.predict(processed_image)
        predicted_class_idx = np.argmax(predictions[0])
        predicted_class = CLASS_NAMES[predicted_class_idx]

        llm_response = get_ceramic_info(predicted_class)

        result = {
            "predicted_class": predicted_class,
            "llm_response": llm_response
        }
        logger.info(f"Ảnh được dự đoán là: {predicted_class}")
        return result
    except Exception as e:
        logger.error(f"Lỗi khi xử lý: {str(e)}")
        return {"error": str(e)}