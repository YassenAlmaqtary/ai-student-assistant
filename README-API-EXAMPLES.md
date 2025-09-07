# أمثلة الطلبات والاستجابات

## رفع URL
### الطلب
POST /api/lessons/upload-url
```json
{
  "filename": "lecture1.pdf",
  "type": "pdf"
}
```
### الاستجابة
```json
{
  "upload_url": "https://minio.local/uploads/abc123_lecture1.pdf",
  "path": "uploads/abc123_lecture1.pdf"
}
```

## إنشاء درس
### الطلب
POST /api/lessons
```json
{
  "title": "محاضرة الذكاء الاصطناعي",
  "path": "uploads/abc123_lecture1.pdf",
  "type": "pdf"
}
```
### الاستجابة
```json
{
  "lesson": {
    "id": 1,
    "user_id": 1,
    "title": "محاضرة الذكاء الاصطناعي",
    "type": "pdf",
    "s3_path": "uploads/abc123_lecture1.pdf",
    "status": "pending"
  }
}
```

## Webhook (transcript)
### الطلب
POST /api/hooks/processing/pdf
Headers: X-WEBHOOK-SECRET: ...
```json
{
  "lesson_id": 1,
  "text": "النص المستخرج من PDF"
}
```
### الاستجابة
```json
{"ok": true}
```

## البحث
### الطلب
POST /api/search
```json
{
  "lesson_id": 1,
  "query": "ما هو الذكاء الاصطناعي؟"
}
```
### الاستجابة
```json
{
  "results": [
    {"chunk": "...", "score": 0.92}
  ]
}
```

## سؤال وجواب
### الطلب
POST /api/qa
```json
{
  "lesson_id": 1,
  "query": "عرف الذكاء الاصطناعي"
}
```
### الاستجابة
```json
{
  "answer": "الذكاء الاصطناعي هو ...",
  "sources": ["chunk1", "chunk2"]
}
```
