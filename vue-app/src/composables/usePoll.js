import { ref, onMounted, onBeforeUnmount } from 'vue';

export function usePoll(fn, interval = 3000, immediate = true) {
  const loading = ref(false);
  let timer;
  async function tick(){
    loading.value = true;
    try { await fn(); } finally { loading.value = false; }
    timer = setTimeout(tick, interval);
  }
  onMounted(()=>{ if(immediate) tick(); else timer = setTimeout(tick, interval); });
  onBeforeUnmount(()=> timer && clearTimeout(timer));
  return { loading, stop:()=> timer && clearTimeout(timer) };
}
