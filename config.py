import os
from dotenv import load_dotenv

# Load biến môi trường từ file .env
load_dotenv()

# Cấu hình
GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY", "AIzaSyA-Lymc-kEm_p00YMcQEbAlvgO79ZO-fgQ")
TEXT_FOLDER = "D:\\PY_Code\\GD5\\texts"
IMAGE_DIR = "D:\\PY_Code\\SecondModel\\images"
MODEL_PATH = "D:\\PY_Code\\Ceramic_Detection\\xception_66class_model.h5"

# Đảm bảo thư mục tồn tại
os.makedirs(TEXT_FOLDER, exist_ok=True)