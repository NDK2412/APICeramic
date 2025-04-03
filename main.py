import uvicorn
from routes import app
from fastapi.middleware.cors import CORSMiddleware

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:8000"],  # Cho phép Laravel
    allow_credentials=True,
    allow_methods=["*"],  # Cho phép tất cả phương thức (GET, POST, ...)
    allow_headers=["*"],  # Cho phép tất cả header
)
if __name__ == "__main__":
    uvicorn.run(app, host="0.0.0.0", port=60074)