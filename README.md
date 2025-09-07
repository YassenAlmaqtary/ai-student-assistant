# AI Student Assistant

منصة ذكية لمساعدة الطلاب على رفع ومعالجة المحتوى الدراسي (PDF / فيديو / صوت / صور / مستندات) مع مراحل آلية: تفريغ، تقسيم مقاطع، توليد ملخص، بحث نصي و Q&A مبدئي.

## المجلدات
| مجلد | الوصف |
|-------|-------|
| `laravel-app/` | واجهة برمجة التطبيقات + لوحة الإدارة (Laravel + Filament + JWT) |
| `vue-app/` | واجهة الطالب (Vue 3) |
| `python-workers/` | مهام معالجة مستقبلية (OCR/ASR/LLM) |

## المتطلبات الأساسية
- PHP 8.2+
- Composer
- Node 18+
- SQLite (مضمن) أو MySQL (اختياري)

## إعداد سريع (Backend)
```bash
cd laravel-app
cp .env.example .env
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

## وظائف API الحالية
- تسجيل / دخول / Refresh / Me (JWT)
- إنشاء درس + متابعة تقدمه (status + progress%)
- إعادة المعالجة Reprocess (إعادة توليد chunks & summary)
- استرجاع: Transcripts / Summaries / Chunks
- بحث نصي بسيط داخل المقاطع (قبل دمج Qdrant)
- سؤال وجواب مبدئي (يحفظ الجلسة ويعيد مصادر)

الحالات: `pending` → `processing` → `ready` أو `failed` (مع `failure_reason`).

## الاختبارات
تشغيل:
```bash
php artisan test
```

## Postman
استورد الملف: `laravel-app/postman/AIStudentAssistant.postman_collection.json`

## الخطط القادمة
1. دمج Qdrant للبحث الدلالي.
2. استدعاء LLM حقيقي للملخص و Q&A.
3. Signed URLs عبر MinIO/S3.
4. بث لحظي (WebSockets) لتحديث التقدم.

## المساهمة
Pull Requests لاحقاً بعد رفع المستودع على GitHub.

## الرخصة
مبدئياً: خاص (يمكن تحويله لاحقاً إلى MIT).
