// Updated useReview.js composable
export const useReview = async (reviewData) => {
  const config = useRuntimeConfig();
  const url = `${config.public.apiBase}`;

  try {
    const payload = {
      type: 'CreateReview',
      ...reviewData
    };

    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    });

    if (!res.ok) {
      const errorData = await res.json().catch(() => ({ message: 'Network response was not ok' }));
      throw new Error(errorData.message || 'Failed to create review');
    }

    const data = await res.json();

    if (data.status === 'success') {
      return data;
    } else {
      throw new Error(data.message || 'Failed to create review');
    }
  } catch (error) {
    console.error('Create review error:', error.message);
    throw error;
  }
};

// Alternative function to use the existing useApi composable
export const createReviewWithApi = async (reviewData) => {
  return await useApi({
    type: 'CreateReview',
    ...reviewData
  });
};