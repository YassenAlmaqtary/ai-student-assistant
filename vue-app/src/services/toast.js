import { reactive } from 'vue';

export const toasts = reactive([]); // { id, type, message }
let idCounter = 1;

export function showToast(type, message, timeout = 4000) {
  const id = idCounter++;
  toasts.push({ id, type, message });
  if (timeout) {
    setTimeout(() => dismissToast(id), timeout);
  }
}

export function dismissToast(id) {
  const idx = toasts.findIndex(t => t.id === id);
  if (idx !== -1) toasts.splice(idx, 1);
}

export default { showToast, dismissToast, toasts };
