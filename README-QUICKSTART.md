# Quickstart

1. إعداد قاعدة بيانات MySQL وMinIO وQdrant وRedis محلياً.
2. إعداد env لكل مشروع.
3. تشغيل Laravel: `cd laravel-app && composer install && php artisan migrate --seed && php artisan serve`
4. تشغيل Vue: `cd vue-app && npm install && npm run dev`
5. تشغيل Python Workers: `cd python-workers && python -m venv venv && source venv/bin/activate && pip install -r requirements.txt && uvicorn main:app --reload`
6. تشغيل Celery Worker: `cd python-workers && celery -A tasks worker --loglevel=info`

## CORS Troubleshooting

في حال ظهور أخطاء preflight أو CORS:

1. تأكد من وجود FRONTEND_URL في ملف `laravel-app/.env` مثلاً:
	FRONTEND_URL=http://localhost:5173
2. نفّذ: `php artisan optimize:clear`
3. راقب تبويب Network في المتصفح: طلب OPTIONS يجب أن يحتوي `Access-Control-Allow-Origin`.
4. لا تستخدم `withCredentials` مع JWT في الهيدر إذا كان `supports_credentials=false`.
5. تأكد من أن قيمة VITE_API_URL لا تحتوي `/api` مكرر (يجب أن تكون مثل http://localhost:8000 أو http://localhost:8000/api وليس كلاهما).
6. للاختبار المؤقت يمكنك ضبط allowed_origins إلى ['*'] في `config/cors.php` ثم مسح الكاش (للبيئة التطويرية فقط).

مثال اختبار سريع:
curl -i -X OPTIONS http://localhost:8000/api/login -H "Origin: http://localhost:5173" -H "Access-Control-Request-Method: POST"

يجب أن ترى ترويسة Access-Control-Allow-Origin في الاستجابة.
