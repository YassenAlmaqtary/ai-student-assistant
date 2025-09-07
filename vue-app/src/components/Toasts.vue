<template>
  <div class="toasts" v-if="toasts.length">
    <div v-for="t in toasts" :key="t.id" :class="['toast', t.type]" @click="dismiss(t.id)">
      {{ t.message }}
    </div>
  </div>
</template>
<script setup>
import { toasts, dismissToast as dismiss } from '../services/toast';
</script>
<style scoped>
.toasts{position:fixed;top:1rem;right:1rem;display:flex;flex-direction:column;gap:.5rem;z-index:9999;font-family:system-ui,Arial}
.toast{background:#333;color:#fff;padding:.6rem .9rem;border-radius:6px;box-shadow:0 4px 12px rgba(0,0,0,.15);font-size:.85rem;cursor:pointer;min-width:180px;animation:fadeIn .25s}
.toast.info{background:#1976d2}
.toast.success{background:#2e7d32}
.toast.error{background:#d32f2f}
@keyframes fadeIn{from{opacity:0;transform:translateY(-4px)}to{opacity:1;transform:translateY(0)}}
</style>
