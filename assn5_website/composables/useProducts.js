export const useProducts = async () => {
  const config = useRuntimeConfig();
  const url = `${config.public.apiBase}`;

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ type: 'GetProducts' })
    });

    if (!res.ok) {
      throw new Error('Failed to load products');
    }

    const data = await res.json();

    if (data.status === 'success') {
      return data.data.products || [];
    } else {
      console.error('API error:', data.message);
      return [];
    }
  } catch (error) {
    console.error('Fetch error:', error.message);
    return [];
  }
};