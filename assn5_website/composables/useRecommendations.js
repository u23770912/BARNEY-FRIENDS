export const useRecommendations = async (limit = 5) => {
  const config = useRuntimeConfig();
  const url = `${config.public.apiBase}`;

  if (!process.client) return [];

  const storedUser = localStorage.getItem('user');
  if (!storedUser) {
    console.warn('No user data found in localStorage');
    return [];
  }

  const user = JSON.parse(storedUser);
  const userId = user?.id;
  console.log("User ID from localStorage:", userId);

  if (!userId) {
    console.warn('User ID is missing from stored user object');
    return [];
  }

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        type: 'GetRecommendations',
        user_id: userId,
        limit
      })
    });

    if (!res.ok) throw new Error('Failed to load recommendations');

    const data = await res.json();
    console.log("API response:", data);
    return data.status === 'success' ? data.products : [];
  } catch (err) {
    console.error('Fetch error:', err.message);
    return [];
  }
};
