<template>
  <div class="upload-page rtl container fade-in">
    <div class="card">
      <h2 style="margin-top:0">رفع درس جديد</h2>
      <div class="drop-zone" @dragover.prevent @drop.prevent="onDrop" @click="fileInput.click()">
        <input type="file" ref="fileInput" class="hidden" @change="onFileChange" />
        <div v-if="!file">اسحب الملف هنا أو اضغط للاختيار</div>
        <div v-else class="file-chosen">{{ file.name }} ({{ (file.size/1024/1024).toFixed(2) }} MB)</div>
      </div>
      <div class="form-grid">
        <div>
          <label>النوع</label>
          <select v-model="type" class="input select">
            <option value="video">فيديو</option>
            <option value="audio">صوت</option>
            <option value="pdf">PDF</option>
            <option value="image">صورة</option>
            <option value="doc">مستند</option>
          </select>
        </div>
        <div class="grow">
          <label>العنوان</label>
          <input v-model="title" class="input" placeholder="عنوان الدرس" />
        </div>
      </div>
      <div class="actions">
        <button class="btn" @click="upload" :disabled="loading">رفع</button>
        <div v-if="progress>0" class="upload-progress">
          <div class="bar" :style="{width:progress+'%'}"></div>
        </div>
      </div>
      <p v-if="error" class="error-text">{{ error }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '../services/api';
import { showToast } from '../services/toast';

const file = ref(null);
const type = ref('pdf');
const title = ref('');
const progress = ref(0);
const loading = ref(false);
const error = ref('');

function onFileChange(e) { file.value = e.target.files[0]; }
function onDrop(e){
  const f = e.dataTransfer.files[0];
  if(f) file.value = f;
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
    // استجابة السيرفر ملفوفة: { success:true, data:{ upload_url, path } }
    const { data: wrapper } = await api.post('/lessons/upload-url', {
      filename: file.value.name,
      type: type.value,
    });
    const payload = wrapper?.data || {};
    if(!payload.path){
      throw new Error('لم يتم استلام path من الخادم');
    }
    const formData = new FormData();
    let finalPath = payload.path;
    if(!payload.upload_url){
      // جرّب رفع مباشر عبر الباكند
      formData.append('file', file.value);
      formData.append('type', type.value);
      try {
        const { data: upWrap } = await api.post('/lessons/direct-upload', formData, { headers:{ 'Content-Type':'multipart/form-data' } });
        const upData = upWrap?.data || {};
        if(upData.path) finalPath = upData.path;
      } catch(e){
        showToast('error','فشل الرفع المباشر عبر الخادم');
      }
    }
    // 2. رفع الملف إلى MinIO مباشرة (رابط خارجي افتراضي placeholder قد يفشل CORS)
    // إذا فشل نعرض تنبيه ونكمل (تحتاج لاحقاً backend يقوم بالرفع أو توليد URL حقيقي).
    try {
      if(!payload.upload_url) throw new Error('skip-upload');
  const resp = await fetch(payload.upload_url, {
        method: 'PUT',
        body: file.value,
        headers: { 'Content-Type': file.value.type || 'application/octet-stream' },
        mode: 'cors'
      });
      if(!resp.ok) throw new Error('فشل رفع الملف للوجهة');
      progress.value = 100;
    } catch(uploadErr){
      if(uploadErr.message !== 'skip-upload')
        showToast('error','تعذر الرفع المباشر (CORS) — سيتم حفظ الدرس مع المسار فقط');
    }
    // 3. إرسال بيانات الدرس
    await api.post('/lessons', {
      title: title.value,
      path: finalPath,
      type: type.value,
    });
  progress.value = 100; showToast('success','تم رفع الدرس وإنشاؤه بنجاح');
    setTimeout(()=>{ progress.value=0; }, 1200);
    title.value = ''; file.value = null;
  } catch (e) {
    error.value = e.response?.data?.error?.message || e.response?.data?.message || 'خطأ في الرفع';
    showToast('error', error.value);
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
.upload-page .drop-zone{border:2px dashed #b6c2d6;padding:1.5rem;border-radius:14px;display:flex;align-items:center;justify-content:center;cursor:pointer;background:#f8fafc;font-size:.8rem;color:#555;transition:.2s}
.upload-page .drop-zone:hover{background:#eef2f7}
.file-chosen{font-size:.7rem;font-weight:600;color:#222}
.form-grid{display:flex;gap:1rem;margin-top:1rem;flex-wrap:wrap}
.form-grid label{font-size:.6rem;font-weight:600;color:#666;letter-spacing:.5px;margin-bottom:.25rem;display:block}
.actions{margin-top:1rem;display:flex;align-items:center;gap:1rem}
.upload-progress{flex:1;height:10px;background:#e2e8f0;border-radius:6px;overflow:hidden;position:relative}
.upload-progress .bar{background:linear-gradient(90deg,#3f51b5,#6366f1);height:100%;width:0;transition:width .3s}
</style>
