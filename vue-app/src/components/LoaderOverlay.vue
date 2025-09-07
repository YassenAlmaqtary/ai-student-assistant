<template>
  <div v-if="show" class="loader-overlay">
    <div class="spinner" />
  </div>
</template>
<script setup>
defineProps({ show: Boolean });
</script>
<style scoped>
.loader-overlay{position:fixed;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.6);backdrop-filter:blur(2px);z-index:999}
.spinner{width:48px;height:48px;border:4px solid #cfd8e3;border-top-color:#3f51b5;border-radius:50%;animation:spin 1s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
