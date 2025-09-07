import { createRouter, createWebHistory } from 'vue-router';
import UploadLesson from './pages/UploadLesson.vue';
import LessonView from './pages/LessonView.vue';
import Login from './pages/Login.vue';
import Register from './pages/Register.vue';
import LessonsList from './pages/LessonsList.vue';
import { getToken } from './services/auth';

const routes = [
  { path: '/login', component: Login, meta: { guest: true } },
  { path: '/register', component: Register, meta: { guest: true } },
  { path: '/', component: LessonsList, meta: { auth: true } },
  { path: '/upload', component: UploadLesson, meta: { auth: true } },
  { path: '/lesson/:id', component: LessonView, props: true, meta: { auth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to) => {
  const token = getToken();
  if (to.meta.auth && !token) {
    return '/login';
  }
  if (to.meta.guest && token) {
    return '/';
  }
});

export default router;
