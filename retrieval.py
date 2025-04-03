import logging
import google.generativeai as genai
from googlesearch import search  # Thư viện để tìm kiếm Google
import requests
from bs4 import BeautifulSoup
from config import GOOGLE_API_KEY
from sentence_transformers import SentenceTransformer, util
import numpy as np

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Cấu hình API Google Gemini
genai.configure(api_key=GOOGLE_API_KEY)

# Khởi tạo mô hình nhúng văn bản
embedder = SentenceTransformer('paraphrase-multilingual-MiniLM-L12-v2')  # Mô hình đa ngôn ngữ nhẹ

# Hàm tìm kiếm thông tin từ Google và scrape nội dung
def search_google(query, num_results=10):
    """Tìm kiếm trên Google và trả về danh sách nội dung từ các trang web đầu tiên."""
    try:
        urls = list(search(query, num_results=num_results))
        logger.info(f"Tìm thấy {len(urls)} URL cho truy vấn: {query}")

        # Danh sách lưu nội dung từ các trang web
        contents = []

        # Trích xuất nội dung từ từng URL
        for url in urls:
            try:
                response = requests.get(url, timeout=5)
                response.raise_for_status()  # Kiểm tra lỗi HTTP
                soup = BeautifulSoup(response.text, 'html.parser')

                # Lấy toàn bộ nội dung văn bản từ thẻ <p> (đoạn văn)
                paragraphs = soup.find_all('p')
                page_content = " ".join([p.get_text() for p in paragraphs])

                if page_content.strip():
                    contents.append(f"Nội dung từ {url}: {page_content[:2000]}")  # Giới hạn 2000 ký tự mỗi trang
                    logger.info(f"Đã scrape nội dung từ: {url}")
                else:
                    logger.warning(f"Không tìm thấy nội dung hữu ích từ: {url}")
            except Exception as e:
                logger.error(f"Lỗi khi scrape {url}: {e}")

        return contents if contents else ["Không tìm thấy nội dung từ các trang web."]
    except Exception as e:
        logger.error(f"Lỗi khi tìm kiếm Google: {e}")
        return ["Không tìm thấy thông tin từ Google."]

# Hàm tổng hợp nội dung bằng Gemini
def summarize_with_gemini(predicted_class, contents):
    """Dùng Gemini để tổng hợp nội dung từ các trang web."""
    gemini_model = genai.GenerativeModel('gemini-1.5-flash')

    if not contents or contents == ["Không tìm thấy nội dung từ các trang web."]:
        content_text = "Không đủ thông tin để tổng hợp."
    else:
        content_text = "\n".join(contents)

    # Tạo prompt cho Gemini bằng tiếng Việt
    prompt = (
        f"Tôi đã nhận diện được dòng gốm '{predicted_class}'. "
        f"Dựa trên thông tin từ các trang web sau:\n"
        f"{content_text}\n"
        f"Hãy cung cấp mô tả chi tiết về dòng gốm này bằng tiếng Việt, bao gồm: mô tả, giá bán, và lịch sử hình thành. "
        "Chỉ trả về thông tin liên quan và chính xác nhất có thể"
    )

    try:
        response = gemini_model.generate_content(prompt)
        logger.info(f"Đã nhận phản hồi từ Gemini: {response.text[:100]}...")
        return response.text
    except Exception as e:
        logger.error(f"Lỗi khi gọi Gemini: {e}")
        return f"Lỗi khi lấy thông tin từ Gemini: {e}"

# Hàm lọc nội dung liên quan dựa trên vector
def filter_relevant_content(summary, query):
    """Chuyển nội dung tổng hợp từ Gemini thành vector và lọc các đoạn liên quan nhất."""
    if not summary or summary.startswith("Lỗi khi lấy thông tin từ Gemini") or summary == "Không đủ thông tin để tổng hợp.":
        return summary

    # Tách nội dung tổng hợp thành các câu riêng lẻ
    sentences = summary.split(". ")

    # Tạo embedding cho truy vấn
    query_embedding = embedder.encode(query, convert_to_tensor=True)

    # Tạo embedding cho các câu từ Gemini
    sentence_embeddings = embedder.encode(sentences, convert_to_tensor=True)

    # Tính độ tương đồng cosine giữa truy vấn và các câu
    cos_scores = util.pytorch_cos_sim(query_embedding, sentence_embeddings)[0]

    # Chọn top 3 câu có độ tương đồng cao nhất (để rút gọn hơn)
    top_k = min(3, len(sentences))
    top_indices = np.argsort(cos_scores.cpu().numpy())[::-1][:top_k]

    # Lấy các câu liên quan nhất
    filtered_sentences = [sentences[idx] for idx in top_indices]
    return ". ".join(filtered_sentences) + "."  # Thêm dấu chấm kết thúc

# Hàm lấy thông tin chi tiết từ Gemini dựa trên dòng gốm nhận diện
def get_ceramic_info(predicted_class):
    """Lấy thông tin từ Google, tổng hợp bằng Gemini, chuyển thành vector và lọc."""
    # Tạo truy vấn tìm kiếm
    search_query = f"gốm: {predicted_class}"
    logger.info(f"Truy vấn Google: {search_query}")

    # Tìm kiếm và scrape nội dung từ Google
    contents = search_google(search_query)

    # Tổng hợp nội dung bằng Gemini
    summary = summarize_with_gemini(predicted_class, contents)

    # Lọc nội dung liên quan bằng vector
    #filtered_result = filter_relevant_content(summary, search_query)

    return summary

# Khởi tạo hệ thống retrieval (không cần FAISS hay text file nữa)
qa_chain = None  # Bỏ qa_chain vì không dùng chatbot từ file .txt

