<template>
  <div class="auth-wrapper rtl fade-in">
    <div class="card auth-card">
      <h2 class="auth-title">تسجيل الدخول</h2>
      <form @submit.prevent="login">
        <input class="input" v-model="email" type="email" placeholder="البريد الإلكتروني" required />
        <input class="input" v-model="password" type="password" placeholder="كلمة المرور" required />
        <button class="btn" :disabled="loading">دخول</button>
        <p v-if="error" class="error-text">{{ error }}</p>
      </form>
      <p class="auth-alt">ليس لديك حساب؟ <router-link to="/register">إنشاء حساب جديد</router-link></p>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue';
import api from '../services/api';
import { saveToken } from '../services/auth';
import { showToast } from '../services/toast';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');

async function login() {
  loading.value = true; error.value='';
  try {
    const { data } = await api.post('/login', { email: email.value, password: password.value });
  saveToken(data.data.token);
  showToast('success','تم تسجيل الدخول بنجاح');
  router.push('/');
  } catch(e) {
    error.value = e.response?.data?.error?.message || 'فشل الدخول';
  } finally { loading.value=false; }
}
</script>
<style scoped>
/* page-specific overrides if needed */
</style>
