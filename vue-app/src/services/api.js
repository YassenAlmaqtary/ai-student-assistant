import axios from 'axios';
import { getToken, clearToken } from './auth';
import { showToast } from './toast';

// بناء عنوان الأساس مع معالجة التكرار (تجنب /api/api)
const rawBase = (import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000').replace(/\/$/, '');
const baseURL = /\/api$/.test(rawBase) ? rawBase : rawBase + '/api';

// لا نحتاج withCredentials لأننا نستعمل Authorization Bearer
const api = axios.create({ baseURL });
console.log('API Base URL:', baseURL);
api.interceptors.request.use((config) => {
  const token = getToken();
  if (token) config.headers.Authorization = 'Bearer ' + token;
  return config;
});

api.interceptors.response.use(
  (res) => res,
  (err) => {
    // معالجة الأخطاء العامة
    if (err.response) {
      if (err.response.status === 401) {
        const hadToken = !!getToken();
        clearToken();
        if(hadToken) showToast('error', 'انتهت الجلسة، يرجى تسجيل الدخول');
        // توجيه اختياري لاحقاً يمكن وضعه هنا باستخدام window.location = '/login';
      } else if (err.response.status >= 500) {
        showToast('error', 'خطأ في الخادم');
      } else if (err.response.data?.error?.message) {
        showToast('error', err.response.data.error.message);
      }
    } else {
      showToast('error', 'تعذر الاتصال بالخادم');
    }
    return Promise.reject(err);
  }
);

export default api;
