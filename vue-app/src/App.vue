<template>
  <div class="layout">
    <header class="app-header" v-if="isAuth">
      <nav class="nav">
        <router-link to="/" class="logo">الـدروس</router-link>
        <div class="grow"></div>
        <router-link to="/upload" class="link">رفع</router-link>
        <button class="logout" @click="logout">خروج</button>
      </nav>
    </header>
    <main>
      <router-view />
    </main>
    <Toasts />
  </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import Toasts from './components/Toasts.vue';
import { getToken, clearToken } from './services/auth';
import api from './services/api';
import { showToast } from './services/toast';

const router = useRouter();
const route = useRoute();
const isAuth = ref(false);
const user = ref(null);

async function fetchMe(){
  if(!getToken()) { user.value=null; return; }
  try { const { data } = await api.get('/me'); user.value = data.data.user; }
  catch { /* ignore */ }
}

watchEffect(()=>{
  isAuth.value = !!getToken();
  if(isAuth.value) fetchMe();
});

function logout(){
  clearToken();
  showToast('info','تم تسجيل الخروج');
  if(route.meta.auth) router.push('/login');
}
</script>
<style scoped>
.app-header{background:#1f2937;color:#fff;padding:.6rem .9rem;direction:rtl}
.nav{display:flex;align-items:center;gap:1rem}
.logo{color:#fff;text-decoration:none;font-weight:600}
.link{color:#fff;text-decoration:none}
.grow{flex:1}
.logout{background:#d32f2f;color:#fff;border:none;padding:.45rem .9rem;border-radius:4px;cursor:pointer}
.logout:hover{background:#b71c1c}
main{padding:1rem;direction:rtl}
</style>
