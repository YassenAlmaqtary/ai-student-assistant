<template>
  <div class="upload-lesson">
    <h2>رفع درس جديد</h2>
    <input type="file" @change="onFileChange" />
    <select v-model="type">
      <option value="video">فيديو</option>
      <option value="audio">صوت</option>
      <option value="pdf">PDF</option>
      <option value="image">صورة</option>
      <option value="doc">مستند</option>
    </select>
    <input v-model="title" placeholder="عنوان الدرس" />
    <button @click="upload" :disabled="loading">رفع</button>
    <div v-if="progress > 0">Progress: {{ progress }}%</div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../services/api';

const file = ref(null);
const type = ref('pdf');
const title = ref('');
const progress = ref(0);
const loading = ref(false);
const error = ref('');

function onFileChange(e) {
  file.value = e.target.files[0];
}

async function upload() {
  if (!file.value || !title.value) {
    error.value = 'يرجى اختيار ملف وكتابة عنوان';
    return;
  }
  loading.value = true;
  error.value = '';
  try {
    // 1. طلب signed URL
    const { data } = await api.post('/lessons/upload-url', {
      filename: file.value.name,
      type: type.value,
    });
    // 2. رفع الملف إلى MinIO مباشرة
    await api.put(data.upload_url, file.value, {
      headers: { 'Content-Type': file.value.type },
      onUploadProgress: (e) => {
        progress.value = Math.round((e.loaded * 100) / e.total);
      },
    });
    // 3. إرسال بيانات الدرس
    await api.post('/lessons', {
      title: title.value,
      path: data.path,
      type: type.value,
    });
    progress.value = 100;
    title.value = '';
    file.value = null;
  } catch (e) {
    error.value = e.response?.data?.message || 'خطأ في الرفع';
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.upload-lesson { direction: rtl; max-width: 400px; margin: 2rem auto; }
.error { color: red; }
</style>
