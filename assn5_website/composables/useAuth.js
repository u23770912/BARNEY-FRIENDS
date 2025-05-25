import { ref, watch } from 'vue';

const apiKey = ref(null);
const isLoggedIn = ref(false);

// Only run once on client
if (process.client) {
  apiKey.value = localStorage.getItem('apiKey');
  isLoggedIn.value = !!apiKey.value;

  watch(apiKey, (newKey) => {
    if (newKey) {
      localStorage.setItem('apiKey', newKey);
    } else {
      localStorage.removeItem('apiKey');
    }
    isLoggedIn.value = !!newKey; // keep isLoggedIn synced
  });
}

const logout = () => {
  apiKey.value = null;
};

const refreshAuth = () => {
  if (process.client) {
    apiKey.value = localStorage.getItem('apiKey');
    isLoggedIn.value = !!apiKey.value;
  }
};

export const useAuth = () => {
  return {
    apiKey,
    isLoggedIn,
    logout,
    refreshAuth,
  };
};
