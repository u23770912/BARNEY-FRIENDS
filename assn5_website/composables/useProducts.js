export const useProducts = async (sort = 'brand', order = 'ASC') => {
  const config = useRuntimeConfig();
  const url = `${config.public.apiBase}`;

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        type: 'GetAllProducts',
        sort,
        order
      })
    });

    if (!res.ok) {
      throw new Error('Failed to load products');
    }

    const data = await res.json();

    if (data.status === 'success') {
      // Inject retailer_name into each price
      const products = data.products.map(product => ({
        ...product,
        prices: product.prices.map(price => ({
          ...price,
          retailer_name: getRetailerNameById(price.retailer_id, product)
        }))
      }));

      return products;
    } else {
      console.error('API error:', data.message);
      return [];
    }
  } catch (error) {
    console.error('Fetch error:', error.message);
    return [];
  }
};

function getRetailerNameById(retailerId, product) {
  // If the product's main retailer_id matches, use retailer_name
  if (product.retailer_id === retailerId) {
    return product.retailer_name;
  }

  // Optional: map known retailer_id to names
  const retailerMap = {
    '1': 'Shoe City',
    '2': 'Sportscene',
    '3': 'Superbalist'
    // Add more if needed
  };

  return retailerMap[retailerId] || 'Unknown Retailer';
}
