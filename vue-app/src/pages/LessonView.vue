<template>
  <div class="lesson-view">
    <h2>عرض الدرس</h2>
    <!-- عرض الميديا -->
    <div v-if="lesson">
      <div v-if="lesson.type === 'video'">
        <video :src="mediaUrl" controls width="400" />
      </div>
      <div v-else-if="lesson.type === 'audio'">
        <audio :src="mediaUrl" controls />
      </div>
      <div v-else-if="lesson.type === 'pdf'">
        <iframe :src="mediaUrl" width="400" height="500"></iframe>
      </div>
      <div v-else-if="lesson.type === 'image'">
        <img :src="mediaUrl" width="400" />
      </div>
      <div v-else>
        <a :href="mediaUrl" target="_blank">تحميل المستند</a>
      </div>
      <div class="transcript">
        <h3>النص المفرغ</h3>
        <pre>{{ transcript }}</pre>
      </div>
      <div class="summaries">
        <h3>الملخصات</h3>
        <ul>
          <li v-for="s in summaries" :key="s.id">{{ s.style }}: {{ s.text }}</li>
        </ul>
      </div>
      <div class="qa-panel">
        <h3>سؤال وجواب</h3>
        <input v-model="question" placeholder="اكتب سؤالك..." />
        <button @click="ask">اسأل</button>
        <div v-if="answer">الإجابة: {{ answer }}</div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import { useRoute } from 'vue-router';

const route = useRoute();
const lesson = ref(null);
const transcript = ref('');
const summaries = ref([]);
const question = ref('');
const answer = ref('');
const mediaUrl = ref('');

onMounted(async () => {
  // جلب بيانات الدرس (مبدئي)
  // هنا يفترض جلب بيانات الدرس من API
  // مثال:
  // const { data } = await api.get(`/lessons/${route.params.id}`);
  // lesson.value = data.lesson;
  // mediaUrl.value = ...
});

async function ask() {
  if (!question.value) return;
  // إرسال سؤال للـ API
  // const { data } = await api.post('/qa', { lesson_id: lesson.value.id, query: question.value });
  // answer.value = data.answer;
}
</script>

<style scoped>
.lesson-view { direction: rtl; max-width: 600px; margin: 2rem auto; }
</style>
