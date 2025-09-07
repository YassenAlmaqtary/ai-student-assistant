import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL + '/api',
  withCredentials: true,
});

api.interceptors.response.use(
  (res) => res,
  (err) => {
    // معالجة الأخطاء العامة
    if (err.response && err.response.status === 401) {
      // يمكن إعادة التوجيه لصفحة تسجيل الدخول
    }
    return Promise.reject(err);
  }
);

export default api;
