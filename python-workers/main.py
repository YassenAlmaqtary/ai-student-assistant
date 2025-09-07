from fastapi import FastAPI, Request
from dotenv import load_dotenv
import os

load_dotenv()

app = FastAPI()

@app.post("/internal/task")
def accept_task(request: Request):
    # نقطة استقبال مهام داخلية (اختياري)
    return {"ok": True}
