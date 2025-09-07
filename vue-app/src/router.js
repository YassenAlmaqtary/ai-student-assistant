import { createRouter, createWebHistory } from 'vue-router';
import UploadLesson from './pages/UploadLesson.vue';
import LessonView from './pages/LessonView.vue';

const routes = [
  { path: '/', component: UploadLesson },
  { path: '/lesson/:id', component: LessonView, props: true },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
