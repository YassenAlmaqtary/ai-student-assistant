<?php

return [
    'name' => env('APP_NAME', 'AI Student Assistant'),
    'env' => env('APP_ENV', 'local'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost:8080'),
    'timezone' => 'UTC',
    // الضبط الافتراضي للغة إلى العربية مع الإنجليزية كبديلة
    'locale' => 'ar',
    'fallback_locale' => 'en',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
];
