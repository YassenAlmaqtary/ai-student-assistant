# Quickstart

1. إعداد قاعدة بيانات MySQL وMinIO وQdrant وRedis محلياً.
2. إعداد env لكل مشروع.
3. تشغيل Laravel: `cd laravel-app && composer install && php artisan migrate --seed && php artisan serve`
4. تشغيل Vue: `cd vue-app && npm install && npm run dev`
5. تشغيل Python Workers: `cd python-workers && python -m venv venv && source venv/bin/activate && pip install -r requirements.txt && uvicorn main:app --reload`
6. تشغيل Celery Worker: `cd python-workers && celery -A tasks worker --loglevel=info`
