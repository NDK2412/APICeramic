import tensorflow as tf
import logging

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Danh sách class được định nghĩa sẵn
CLASS_NAMES = [
    'Bat_Trang_Men_chay', 'Bat_Trang_Men_hoa_bien', 'Bat_Trang_Men_lam',
    'Bat_Trang_Men_nau', 'Bat_Trang_Men_nau_da_luong', 'Bat_Trang_Men_ngoc',
    'Bat_Trang_Men_ran', 'Bat_Trang_Men_trang', 'Bat_Trang_Men_vang',
    'Bau_Truc_Men_nau_dat', 'Cay_Mai_Men_Nau', 'Cay_Mai_Men_Xanh_Lam',
    'Cay_Mai_Men_xanh_reu', 'Chu_Dau_Men_nau', 'Chu_Dau_Men_ngoc',
    'Chu_Dau_Men_trang_cham', 'Dong_Trieu_Men_do', 'Dong_Trieu_Men_nau',
    'Dong_Trieu_Men_trang_ve_lam', 'Dong_Trieu_Men_vang_dat',
    'Gom_Bien_Hoa_Men_Nau', 'Gom_Bien_Hoa_Men_Xanh_Dong_Tro_Bong',
    'Gom_Bien_Hoa_Men_Xanh_Luc', 'Gom_Bien_Hoa_Men_nau_da_luon',
    'Gom_Bien_Hoa_Men_xanh_lam', 'Gom_Binh_Duong_Men_Den_Xanh_Chay',
    'Gom_Binh_Duong_Men_Nau', 'Gom_Binh_Duong_Men_Trang',
    'Gom_Binh_Duong_Men_Xanh', 'Gom_Cay_Men_lam_nhat', 'Gom_Cay_Men_trang',
    'Gom_Cay_Men_xanh_ngoc', 'Gom_bo_bat_Men_trang_duc',
    'Gom_bo_bat_Men_trang_ve_lam', 'Gom_gia_thuy_Mau_nau_dat',
    'Gom_go_sanh(binh_dinh)_Men_nau', 'Gom_hoa_nau_Men_trang_nau',
    'Gom_huong_canh_Vuot_tay_khong_men', 'Gom_kim_lan_Men_chay_gia_co',
    'Gom_kim_lan_Men_nau', 'Gom_kim_lan_Men_xanh', 'Gom_lai_theu_Mau_dat_nung',
    'Gom_lai_theu_Men_nau_da_luon', 'Gom_lai_theu_Men_ngu_thai',
    'Gom_lai_theu_Men_trang', 'Gom_lai_theu_Men_xanh',
    'Gom_lai_theu_Men_xanh_luc', 'Gom_lai_theu_Men_xanh_trang',
    'Gom_muong_chanh_Mau_dat_nung(khong_men)', 'Gom_my_thien_Mau_dat_nung',
    'Gom_my_thien_Men_vang_nau', 'Gom_my_thien_Men_xanh_la',
    'Gom_quang_duc_Men_nau', 'Gom_quang_duc_Men_xanh_luc',
    'Gom_sa_huynh_Mau_do_dat_nung', 'Gom_thanh_le_Men_mau',
    'Phu_Lang_Men_Nau_Da_Luon', 'Phu_Lang_Men_nau_den', 'Phu_Lang_Men_nau_do',
    'Phuoc_Tich_Men_nau_hoang_gia', 'Phuoc_Tich_Men_xam_den',
    'Tan_Van_Men_Nau', 'Tan_Van_Men_Xanh', 'Thanh_Ha_Men_do_cam',
    'Tho_Ha_Men_nau', 'Vinh_Long_Men_do'
]

# Load mô hình TensorFlow
logger.info("Đang tải mô hình TensorFlow...")
model = tf.keras.models.load_model('D:\\PY_Code\\Ceramic_Detection\\xception_66class_model.h5')
logger.info("Mô hình TensorFlow đã được tải thành công.")
logger.info(f"Nhận diện được {len(CLASS_NAMES)} lớp: {CLASS_NAMES}")