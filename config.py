<<<<<<< HEAD
# import os
# from dotenv import load_dotenv
#
# # Load biến môi trường từ file .env
# load_dotenv()
#
# # Cấu hình
# GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY", "AIzaSyAH7ynNF7vZDM-3y34Bz4eMGNjqq0sUv6E")  # Thay bằng key mới
# TEXT_FOLDER = "D:\\PY_Code\\GD5\\texts"
# IMAGE_DIR = "D:\\PY_Code\\SecondModel\\images"
# MODEL_PATH_66 = "D:\\PY_Code\\Ceramic_Detection\\xception_66class_model.h5"  # Mô hình 66 class
# MODEL_PATH_67 = "D:\\PY_Code\\Ceramic_Detection\\xception_67class_model.h5"  # Mô hình 67 class
# DEFAULT_MODEL = "xception_66class_model.h5"  # Mô hình mặc định khi khởi động
#
# # Đảm bảo thư mục tồn tại
# os.makedirs(TEXT_FOLDER, exist_ok=True)
#
# # API key cho FastAPI
# API_KEY = "AuwTLoaTGAWYm2HmDzV0i9ahfemzky"  # Giữ nguyên




=======
>>>>>>> 030ad931c4b00f84144f65f1375678cf8d0924ad
import os
from dotenv import load_dotenv

# Load biến môi trường từ file .env
load_dotenv()

# Cấu hình
<<<<<<< HEAD
GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY", "AIzaSyAH7ynNF7vZDM-3y34Bz4eMGNjqq0sUv6E")
TEXT_FOLDER = "/app/texts"
IMAGE_DIR = "/app/images"
MODEL_PATH_66 = "/app/xception_66class_model.h5"  # Đường dẫn tuyệt đối
MODEL_PATH_67 = "/app/xception_67class_model.h5"  # Đường dẫn tuyệt đối
DEFAULT_MODEL = "xception_66class_model.h5"  # Chỉ tên file, sẽ kết hợp với /app
=======
GOOGLE_API_KEY = os.getenv("GOOGLE_API_KEY", "AIzaSyAH7ynNF7vZDM-3y34Bz4eMGNjqq0sUv6E")  # Thay bằng key mới
TEXT_FOLDER = "D:\\PY_Code\\GD5\\texts"
IMAGE_DIR = "D:\\PY_Code\\SecondModel\\images"
MODEL_PATH = "D:\\PY_Code\\Ceramic_Detection\\xception_66class_model.h5"
>>>>>>> 030ad931c4b00f84144f65f1375678cf8d0924ad

# Đảm bảo thư mục tồn tại
os.makedirs(TEXT_FOLDER, exist_ok=True)

# API key cho FastAPI
<<<<<<< HEAD
API_KEY = "AuwTLoaTGAWYm2HmDzV0i9ahfemzky"
=======
API_KEY = "AuwTLoaTGAWYm2HmDzV0i9ahfemzky"  # Giữ nguyên
>>>>>>> 030ad931c4b00f84144f65f1375678cf8d0924ad
