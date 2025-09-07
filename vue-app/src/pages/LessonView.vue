<template>
  <div class="lesson-view" v-if="lesson">
    <header class="head">
      <h2>{{ lesson.title }}</h2>
      <div class="status-line">
        <span :class="['badge', lesson.status]">{{ lesson.status }}</span>
        <span v-if="lesson.status==='failed' && lesson.failure_reason" class="fail-reason" :title="lesson.failure_reason">!</span>
        <div v-if="lesson.status!=='ready' && lesson.status!=='failed'" class="progress mini"><div class="bar" :style="{width:(lesson.progress||0)+'%'}"></div></div>
        <button v-if="lesson.status==='failed'" class="btn danger sm" @click="reprocess">إعادة المعالجة</button>
      </div>
    </header>

    <div class="tabs">
      <button v-for="t in tabList" :key="t.key" :class="['tab', {active:activeTab===t.key}]" @click="activeTab=t.key">{{ t.label }}</button>
    </div>

    <div class="tab-panels">
      <div v-show="activeTab==='media'" class="panel media" v-if="mediaUrl">
        <video v-if="lesson.type==='video'" :src="mediaUrl" controls />
        <audio v-else-if="lesson.type==='audio'" :src="mediaUrl" controls />
        <iframe v-else-if="lesson.type==='pdf'" :src="mediaUrl" />
        <img v-else-if="lesson.type==='image'" :src="mediaUrl" />
        <a v-else :href="mediaUrl" target="_blank">فتح الملف</a>
      </div>
      <div v-show="activeTab==='summaries'" class="panel" v-if="summaries.length">
        <ul class="summary-list"><li v-for="s in summaries" :key="s.id"><strong>{{ s.style }}:</strong> {{ s.text }}</li></ul>
      </div>
      <div v-show="activeTab==='transcript'" class="panel" v-if="transcript">
        <pre>{{ transcript }}</pre>
      </div>
      <div v-show="activeTab==='qa'" class="panel qa-panel">
        <form @submit.prevent="ask" class="qa-form">
          <input v-model="question" placeholder="اكتب سؤالك..." />
          <button class="btn" :disabled="qaLoading">اسأل</button>
        </form>
        <div class="qa-body">
          <div class="sessions" v-if="sessions.length">
            <h4>الأسئلة السابقة</h4>
            <ul>
              <li v-for="s in sessions" :key="s.id" @click="restoreSession(s)" :class="{active: s.id===activeSessionId}">
                {{ s.question.slice(0,40) }}<span class="time">{{ formatTime(s.created_at) }}</span>
              </li>
            </ul>
          </div>
          <div class="answer-box">
            <div v-if="answer" class="answer">{{ answer }}</div>
            <ul v-if="sources.length" class="sources">
              <li v-for="s in sources" :key="s.chunk_id">{{ s.preview }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../services/api';
import { useRoute } from 'vue-router';
import { showToast } from '../services/toast';

const route = useRoute();
const lesson = ref(null);
const transcript = ref('');
const summaries = ref([]);
const question = ref('');
const answer = ref('');
const sources = ref([]);
const mediaUrl = ref('');
const qaLoading = ref(false);
const sessions = ref([]);
const activeTab = ref('media');
const tabList = [
  { key:'media', label:'المادة' },
  { key:'summaries', label:'الملخصات' },
  { key:'transcript', label:'التفريغ' },
  { key:'qa', label:'س/ج' },
];
const activeSessionId = ref(null);

async function loadLesson(){
  const { data } = await api.get(`/lessons/${route.params.id}`);
  lesson.value = data.data.lesson;
  mediaUrl.value = buildMediaUrl(lesson.value);
  if(lesson.value.status==='ready'){
    loadExtra();
  } else if(lesson.value.status!=='failed') {
    // poll كل ثانيتين حتى الجاهزية
    setTimeout(loadLesson, 2000);
  }
}

async function loadExtra(){
  const [tRes, sRes] = await Promise.all([
    api.get(`/lessons/${route.params.id}/transcripts`),
    api.get(`/lessons/${route.params.id}/summaries`),
  ]);
  transcript.value = tRes.data.data.transcripts?.[0]?.text || '';
  summaries.value = sRes.data.data.summaries || [];
}

function buildMediaUrl(l){
  if(!l?.s3_path) return '';
  if(/^[a-zA-Z]+:\/\//.test(l.s3_path)) return l.s3_path; // absolute
  // إزالة public/ أو storage/ من البداية
  let p = l.s3_path.replace(/^public\//,'').replace(/^storage\//,'');
  const base = import.meta.env.VITE_FILES_BASE_URL || (import.meta.env.VITE_API_URL + '/storage');
  return base.replace(/\/$/,'') + '/' + p.replace(/^\//,'');
}

async function ask(){
  if(!question.value || !lesson.value) return;
  qaLoading.value = true; answer.value=''; sources.value=[];
  try {
    const { data } = await api.post('/qa',{ lesson_id: lesson.value.id, question: question.value });
    answer.value = data.data.answer;
    sources.value = data.data.sources || [];
    if(data.data.session){
      sessions.value.unshift(data.data.session);
      activeSessionId.value = data.data.session.id;
    }
    showToast('success','تم الحصول على الإجابة');
  } catch(e){
    answer.value = e.response?.data?.error?.message || 'تعذر الحصول على إجابة';
    showToast('error', answer.value);
  } finally { qaLoading.value=false; }
}

function restoreSession(s){
  activeSessionId.value = s.id;
  question.value = s.question;
  answer.value = s.answer;
  sources.value = s.sources || [];
}

function formatTime(ts){
  try { return new Date(ts).toLocaleTimeString('ar-EG',{hour:'2-digit',minute:'2-digit'}); } catch { return ''; }
}

async function reprocess(){
  if(!lesson.value) return;
  try {
    await api.post(`/lessons/${lesson.value.id}/reprocess`);
    showToast('info','تم إرسال طلب إعادة المعالجة');
    lesson.value.status='processing';
    lesson.value.progress=0;
    lesson.value.failure_reason=null;
    setTimeout(loadLesson, 1500);
  } catch(e){
    showToast('error', e.response?.data?.error?.message || 'تعذر إعادة المعالجة');
  }
}

async function loadSessions(){
  try {
    const { data } = await api.get(`/lessons/${route.params.id}/qa-sessions`).catch(()=>null);
    if(data?.data?.sessions) sessions.value = data.data.sessions;
  } catch {/* ignore */}
}

onMounted(async ()=>{ await loadLesson(); await loadSessions(); });
</script>

<style scoped>
.lesson-view{direction:rtl;max-width:980px;margin:1.5rem auto;font-family:system-ui,Arial}
.head{display:flex;flex-direction:column;gap:.4rem;margin-bottom:.5rem}
.status-line{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap}
.badge{padding:.25rem .6rem;border-radius:4px;font-size:.7rem;background:#ddd;margin-left:.5rem}
.badge.processing{background:#ffb347}
.badge.ready{background:#4caf50;color:#fff}
.badge.failed{background:#e53935;color:#fff}
.progress{background:#f0f0f0;height:6px;border-radius:4px;overflow:hidden;width:160px;display:inline-block;vertical-align:middle;margin-right:.5rem}
.progress.mini{width:120px}
.progress .bar{background:#3f51b5;height:100%;transition:width .5s}
.summaries ul{list-style:none;padding:0;margin:0}
pre{background:#1e293b;color:#e2e8f0;padding:1rem;border:1px solid #334155;border-radius:8px;max-height:340px;overflow:auto;font-size:.72rem;line-height:1.5}
.qa-form{display:flex;gap:.5rem;margin:.5rem 0}
input{padding:.5rem;border:1px solid #ccc;border-radius:4px;flex:1}
button{padding:.5rem 1rem;cursor:pointer}
.sources{list-style:none;margin:.5rem 0;padding:0;font-size:.7rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px}
.sources li{padding:.45rem .6rem;border-top:1px solid #e2e8f0}
.sources li:first-child{border-top:0}
.fail-reason{cursor:help;color:#fff;background:#e53935;padding:0 .4rem;margin-right:.3rem;border-radius:50%;font-size:.6rem;display:inline-block}
.actions{margin:.75rem 0}
.reprocess{background:#1976d2;color:#fff;border:none;padding:.45rem .9rem;border-radius:4px;cursor:pointer}
.reprocess:hover{background:#125ca3}

/* Tabs */
.tabs{display:flex;gap:.4rem;margin:1rem 0 .6rem;flex-wrap:wrap}
.tab{background:#eceff5;color:#333;border:none;padding:.5rem .9rem;border-radius:6px;cursor:pointer;font-size:.7rem;font-weight:500;letter-spacing:.5px}
.tab.active{background:#3f51b5;color:#fff;box-shadow:0 2px 8px -2px rgba(63,81,181,.4)}
.tab-panels .panel{animation:fadeIn .35s}
.panel.media iframe{width:100%;height:520px;border:1px solid #eee;border-radius:8px}
.panel.media video,.panel.media audio,.panel.media img{max-width:100%;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.15)}
.summary-list{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.75rem;font-size:.78rem}
.qa-body{display:flex;gap:1rem;margin-top:.5rem;flex-wrap:wrap}
.sessions{flex:0 0 220px;max-height:360px;overflow:auto;border:1px solid #e2e8f0;background:#fff;border-radius:8px;padding:.5rem}
.sessions h4{margin:.25rem .25rem .5rem;font-size:.7rem;letter-spacing:.5px;color:#555}
.sessions ul{list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:.3rem}
.sessions li{padding:.45rem .5rem;font-size:.65rem;line-height:1.3;background:#f1f5f9;border:1px solid #e2e8f0;border-radius:6px;cursor:pointer;transition:.18s}
.sessions li:hover{background:#e2e8f0}
.sessions li.active{background:#3f51b5;color:#fff;border-color:#3f51b5}
.sessions .time{display:block;font-size:.55rem;opacity:.7;margin-top:.2rem}
.answer-box{flex:1;min-width:260px}
.answer{background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:.75rem;font-size:.78rem;line-height:1.5;box-shadow:0 2px 4px rgba(0,0,0,.04)}
</style>
