import logging
import glob
import google.generativeai as genai
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_community.vectorstores import FAISS
from langchain.prompts import PromptTemplate
from langchain.chains import RetrievalQA
from langchain_core.runnables import RunnableLambda
from config import GOOGLE_API_KEY, TEXT_FOLDER

# Cấu hình logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Cấu hình API Google Gemini
genai.configure(api_key=GOOGLE_API_KEY)

# Hàm tải và xử lý dữ liệu từ file .txt
def load_text_files(folder_path):
    documents = []
    for file_path in glob.glob(f"{folder_path}/*.txt"):
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                documents.append(content)
                logger.info(f"Đã tải file: {file_path} với {len(content)} ký tự.")
        except Exception as e:
            logger.error(f"Lỗi khi đọc file {file_path}: {e}")
    if not documents:
        logger.warning(f"Không tìm thấy file .txt nào trong thư mục {folder_path}.")
    return documents

# Hàm chia nhỏ tài liệu
def split_documents(documents):
    if not documents:
        raise ValueError("Không có tài liệu nào được tải từ thư mục.")
    text_splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200)
    chunks = text_splitter.split_text("\n\n".join(documents))
    logger.info(f"Đã chia thành {len(chunks)} đoạn văn bản.")
    return chunks

# Tạo vector store
def create_vector_store(text_chunks):
    if not text_chunks:
        raise ValueError("Danh sách text_chunks rỗng, không thể tạo vector store.")
    embeddings = HuggingFaceEmbeddings(
        model_name="sentence-transformers/all-MiniLM-L6-v2",
        model_kwargs={'device': 'cpu'},
        encode_kwargs={'normalize_embeddings': True}
    )
    vector_store = FAISS.from_texts(texts=text_chunks, embedding=embeddings)
    logger.info("Đã tạo vector store FAISS thành công.")
    return vector_store

# Khởi tạo RetrievalQA với Gemini
def initialize_retrieval_qa(vector_store):
    gemini_model = genai.GenerativeModel('gemini-1.5-flash')

    def gemini_query(prompt, **kwargs):
        try:
            if not isinstance(prompt, str):
                prompt = str(prompt)
            response = gemini_model.generate_content(prompt)
            logger.info(f"Đã nhận phản hồi từ Gemini: {response.text[:100]}...")
            return response.text
        except Exception as e:
            logger.error(f"Lỗi khi gọi Gemini: {e}")
            return f"Lỗi khi gọi Gemini: {e}"

    gemini_runnable = RunnableLambda(gemini_query)

    prompt_template = PromptTemplate(
        input_variables=["context", "question"],
        template="Dựa trên thông tin sau: {context}\nTrả lời câu hỏi: {question}"
    )

    qa_chain = RetrievalQA.from_chain_type(
        llm=gemini_runnable,
        chain_type="stuff",
        retriever=vector_store.as_retriever(search_kwargs={"k": 3}),
        return_source_documents=True,
        chain_type_kwargs={"prompt": prompt_template}
    )
    return qa_chain

# Khởi tạo hệ thống retrieval
try:
    documents = load_text_files(TEXT_FOLDER)
    text_chunks = split_documents(documents)
    vector_store = create_vector_store(text_chunks)
    qa_chain = initialize_retrieval_qa(vector_store)
except Exception as e:
    logger.error(f"Lỗi khởi tạo hệ thống retrieval: {e}")
    qa_chain = None