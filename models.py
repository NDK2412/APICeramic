import tensorflow as tf
import logging
import os
import tensorflow.keras as k3

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Danh sách class được định nghĩa sẵn (tiếng Việt có dấu, không có dấu _)
CLASS_NAMES = [
    'Bát Tràng Men chảy', 'Bát Tràng Men hoa biến', 'Bát Tràng Men lam',
    'Bát Tràng Men nâu', 'Bát Tràng Men nâu da lươn', 'Bát Tràng Men ngọc',
    'Bát Tràng Men rạn', 'Bát Tràng Men trắng', 'Bát Tràng Men vàng',
    'Bàu Trúc Men nâu đất', 'Cây Mai Men Nâu', 'Cây Mai Men Xanh Lam',
    'Cây Mai Men xanh rêu', 'Chu Đậu Men nâu', 'Chu Đậu Men ngọc',
    'Chu Đậu Men trắng chấm', 'Đồng Triều Men đỏ', 'Đồng Triều Men nâu',
    'Đồng Triều Men trắng vẽ lam', 'Đồng Triều Men vàng đất',
    'Gốm Biên Hòa Men Nâu', 'Gốm Biên Hòa Men Xanh Đồng Trổ Bông',
    'Gốm Biên Hòa Men Xanh Lục', 'Gốm Biên Hòa Men nâu Da lươn',
    'Gốm Biên Hòa Men xanh lam', 'Gốm Bình Dương Men Đen Xanh Chảy',
    'Gốm Bình Dương Men Nâu', 'Gốm Bình Dương Men Trắng',
    'Gốm Bình Dương Men Xanh', 'Gốm Cây Men lam nhạt', 'Gốm Cây Men trắng',
    'Gốm Cây Men xanh ngọc', 'Gốm Bồ Bát Men trắng đục',
    'Gốm Bồ Bát Men trắng vẽ lam', 'Gốm Gia Thủy Màu nâu đất',
    'Gốm Gò Sành (Bình Định) Men nâu', 'Gốm Hoa Nâu Men trắng nâu',
    'Gốm Hương Canh Vuốt tay không men', 'Gốm Kim Lan Men chảy giả cổ',
    'Gốm Kim Lan Men nâu', 'Gốm Kim Lan Men xanh', 'Gốm Lai Thêu Màu đất nung',
    'Gốm Lai Thêu Men nâu da lươn', 'Gốm Lai Thêu Men ngũ thái',
    'Gốm Lai Thêu Men trắng', 'Gốm Lai Thêu Men xanh',
    'Gốm Lai Thêu Men xanh lục', 'Gốm Lai Thêu Men xanh trắng',
    'Gốm Mường Chanh Màu đất nung (không men)', 'Gốm Mỹ Thiên Màu đất nung',
    'Gốm Mỹ Thiên Men vàng nâu', 'Gốm Mỹ Thiên Men xanh lá',
    'Gốm Quảng Đức Men nâu', 'Gốm Quảng Đức Men xanh lục',
    'Gốm Sa Huỳnh Màu đỏ đất nung', 'Gốm Thanh Lễ Men màu',
    'Phù Lãng Men Nâu da lươn', 'Phù Lãng Men nâu đen', 'Phù Lãng Men nâu đỏ',
    'Phước Tích Men nâu hoàng gia', 'Phước Tích Men xám đen',
    'Tân Vân Men Nâu', 'Tân Vân Men Xanh', 'Thanh Hà Men đỏ cam',
    'Thổ Hà Men nâu', 'Vĩnh Long Men đỏ'
]

# Load mô hình TensorFlow
logger.info("Đang tải mô hình TensorFlow...")
# model_path = os.path.join(os.path.dirname(__file__), 'xception_66class_model.h5')
# model = tf.keras.models.load_model(model_path)
# model = k3.models.load_model(model_path)
model = tf.keras.models.load_model(os.path.join('/app', 'xception_66class_model.h5'))
# model_path = os.path.join(os.path.dirname(__file__), 'xception_66class_model.h5')
# model = tf.keras.models.load_model(model_path)
# model = tf.keras.models.load_model('D:\\PY_Code\\Ceramic_Detection\\xception_66class_model.h5')
logger.info("Mô hình TensorFlow đã được tải thành công.")
logger.info(f"Nhận diện được {len(CLASS_NAMES)} lớp: {CLASS_NAMES}")
