dev:up:
	cd laravel-app && php artisan serve &
	cd ../vue-app && npm run dev &
	cd ../python-workers && uvicorn main:app --reload &

migrate:
	cd laravel-app && php artisan migrate

seed:
	cd laravel-app && php artisan db:seed

worker:celery:
	cd python-workers && celery -A tasks worker --loglevel=info

test:
	cd laravel-app && php artisan test
	cd ../python-workers && pytest
