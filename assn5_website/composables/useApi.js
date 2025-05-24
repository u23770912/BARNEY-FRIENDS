export const useApi = async (payload) => {
  const config = useRuntimeConfig();
  const url = config.public.apiBase; // e.g., http://localhost:3001/api

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    if (!res.ok) {
      const errorData = await res.json().catch(() => ({ message: 'Network response was not ok' }));
      throw new Error(errorData.message || 'API Error');
    }

    return await res.json();
  } catch (error) {
    console.error('API call failed:', error);
    throw error;
  }
};