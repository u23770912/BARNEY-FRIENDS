import { ref, watch } from 'vue';

const apiKey = ref(null);
const isLoggedIn = ref(false);

// Store full user info if needed elsewhere
const user = ref(null);

if (process.client) {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    const parsedUser = JSON.parse(storedUser);
    apiKey.value = parsedUser.apikey;
    isLoggedIn.value = !!parsedUser.apikey;
    user.value = parsedUser;
  }
}

watch(apiKey, (newKey) => {
  if (newKey) {
    const currentUser = {
      ...user.value,
      apikey: newKey
    };
    localStorage.setItem('user', JSON.stringify(currentUser));
  } else {
    localStorage.removeItem('user');
  }
});

const logout = () => {
  apiKey.value = null;
  isLoggedIn.value = false;
  user.value = null;
};

const refreshAuth = () => {
  if (process.client) {
    const storedUser = localStorage.getItem('user');
    if (storedUser) {
      const parsedUser = JSON.parse(storedUser);
      apiKey.value = parsedUser.apikey;
      isLoggedIn.value = !!parsedUser.apikey;
      user.value = parsedUser;
    } else {
      apiKey.value = null;
      isLoggedIn.value = false;
      user.value = null;
    }
  }
};

export const useAuth = () => {
  return {
    apiKey,
    isLoggedIn,
    user,
    logout,
    refreshAuth
  };
};