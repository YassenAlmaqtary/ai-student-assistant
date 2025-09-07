# Python Workers (ai-student-assistant)

- FastAPI (API داخلي)
- Celery workers
- مهام: معالجة PDF، استخراج صوت، تفريغ صوتي، تلخيص، فهرسة، Q&A

## الإعداد
1. python -m venv venv && source venv/bin/activate
2. pip install -r requirements.txt
3. نسخ env.example إلى .env وتعديل القيم
4. uvicorn main:app --reload
5. celery -A tasks worker --loglevel=info
