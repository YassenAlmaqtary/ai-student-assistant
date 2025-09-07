import os
import requests
from celery import Celery
from dotenv import load_dotenv
import pdfplumber
import pytesseract
from PIL import Image
import tempfile

load_dotenv()

celery_app = Celery('tasks', broker=f'redis://{os.getenv("REDIS_HOST", "localhost")}:6379/0')

LARAVEL_WEBHOOK = os.getenv('LARAVEL_API_URL', 'http://localhost:8000') + '/api/hooks/processing/pdf'
WEBHOOK_SECRET = os.getenv('LARAVEL_WEBHOOK_SECRET', 'REPLACE_ME')

@celery_app.task
def process_pdf(lesson_id, s3_url):
    # تحميل PDF من MinIO (مبدئي: يفترض الملف محلي)
    text = ''
    try:
        with pdfplumber.open(s3_url) as pdf:
            for page in pdf.pages:
                text += page.extract_text() or ''
    except Exception:
        # fallback OCR
        with pdfplumber.open(s3_url) as pdf:
            for page in pdf.pages:
                img = page.to_image(resolution=300).original
                text += pytesseract.image_to_string(img)
    # إرسال النص إلى Laravel webhook
    requests.post(LARAVEL_WEBHOOK, json={
        'lesson_id': lesson_id,
        'text': text,
    }, headers={'X-WEBHOOK-SECRET': WEBHOOK_SECRET})
    return True

@celery_app.task
def extract_audio_from_video(s3_path):
    # استخدم ffmpeg لاستخراج الصوت (مبدئي: محاكاة)
    return 'audio.wav'

@celery_app.task
def transcribe_audio_whisper(wav):
    # استخدم whisper أو mock
    return {'text': 'تفريغ صوتي تجريبي', 'timestamps': []}

@celery_app.task
def chunk_and_embed(text, lesson_id):
    # تقسيم النص وتوليد embeddings (محاكاة)
    return [{'chunk': text[:500], 'embedding': [0.1]*384}]

@celery_app.task
def generate_summaries(text_or_chunks, styles):
    # تلخيص (محاكاة)
    return [{'style': s, 'text': f'ملخص ({s})'} for s in styles]

@celery_app.task
def rag_answer(index_ref, query):
    # RAG/LLM (محاكاة)
    return {'answer': 'إجابة تجريبية', 'sources': []}
