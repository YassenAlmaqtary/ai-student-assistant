<template>
  <div class="auth-wrapper rtl fade-in">
    <div class="card auth-card">
      <h2 class="auth-title">حساب جديد</h2>
      <form @submit.prevent="register">
        <input class="input" v-model="name" placeholder="الاسم الكامل" required />
        <input class="input" v-model="email" type="email" placeholder="البريد الإلكتروني" required />
        <input class="input" v-model="password" type="password" placeholder="كلمة المرور" required />
        <button class="btn" :disabled="loading">تسجيل</button>
        <p v-if="error" class="error-text">{{ error }}</p>
      </form>
      <p class="auth-alt">لديك حساب؟ <router-link to="/login">تسجيل الدخول</router-link></p>
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
const name = ref('');
const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');
async function register(){
  loading.value=true; error.value='';
  try{ const { data } = await api.post('/register',{name:name.value,email:email.value,password:password.value});
  saveToken(data.data.token);
  showToast('success','تم إنشاء الحساب والدخول');
  router.push('/');
  }catch(e){ error.value = e.response?.data?.error?.message || 'فشل التسجيل'; }
  finally{ loading.value=false; }
}
</script>
<style scoped>
/* page-specific overrides if needed */
</style>
