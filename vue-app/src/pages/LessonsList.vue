<template>
  <div class="lessons-page rtl fade-in container">
    <div class="card toolbar">
      <div class="filters-row">
        <div class="group">
          <label>الحالة</label>
          <select v-model="status" class="input select">
            <option value="">الكل</option>
            <option value="pending">قيد الانتظار</option>
            <option value="processing">جار المعالجة</option>
            <option value="ready">جاهز</option>
            <option value="failed">فشل</option>
          </select>
        </div>
        <div class="group grow">
          <label>بحث</label>
          <input class="input" v-model="q" placeholder="عنوان الدرس" />
        </div>
        <div class="actions">
          <button class="btn outline" @click="load">تحديث</button>
          <router-link to="/upload" class="btn">رفع درس</router-link>
        </div>
      </div>
    </div>
    <div v-if="lessons.length" class="table-wrapper card">
      <table class="tbl">
        <thead>
          <tr>
            <th>العنوان</th><th>النوع</th><th>الحالة</th><th style="width:160px">التقدم</th><th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="l in lessons" :key="l.id">
            <td>{{ l.title }}</td>
            <td>{{ l.type }}</td>
            <td>
              <span :class="['badge', l.status]">{{ l.status }}</span>
              <span v-if="l.status==='failed' && l.failure_reason" class="fail-reason" :title="l.failure_reason">!</span>
            </td>
            <td>
              <div class="progress"><div class="bar" :style="{width: (l.progress||0)+'%'}"></div></div>
            </td>
            <td><router-link class="btn outline" :to="'/lesson/'+l.id">فتح</router-link></td>
          </tr>
        </tbody>
      </table>
    </div>
    <p v-else class="no-data card" style="text-align:center;font-size:.8rem;color:var(--c-muted)">لا توجد دروس حالياً</p>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { usePoll } from '../composables/usePoll';
import api from '../services/api';
const lessons = ref([]);
const status = ref('');
const q = ref('');

async function load(){
  const params = {};
  if(status.value) params.status = status.value;
  if(q.value) params.q = q.value;
  const { data } = await api.get('/lessons',{ params });
  lessons.value = data.data.items;
}
onMounted(load);
// Auto refresh every 6s
usePoll(async () => {
  if(lessons.value.some(l=> l.status==='processing' || l.status==='pending')){
    await load();
  }
}, 6000);
</script>
<style scoped>
.toolbar{margin-bottom:1.25rem}
.filters-row{display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end}
.group{display:flex;flex-direction:column;gap:.35rem;font-size:.65rem;min-width:140px}
.group label{font-weight:600;color:var(--c-muted);letter-spacing:.5px}
.group.grow{flex:1}
.table-wrapper{padding:0}
table.tbl{margin:0}
.fail-reason{cursor:help;color:#fff;background:#e53935;padding:0 .35rem;margin-right:.25rem;border-radius:50%;font-size:.55rem;display:inline-block}
</style>
