import axios from 'axios';
import dotenv from 'dotenv';

dotenv.config();

console.log('Environment Variables:');
console.log('WHEATLEY_USER:', process.env.WHEATLEY_USER);
console.log('WHEATLEY_PASS:', process.env.WHEATLEY_PASS ? '✅ Set' : '❌ Not Set');

const WHEATLEY_API_URL = 'https://wheatley.cs.up.ac.za/u23770912/COS221/api.php ';

const apiClient = axios.create({
  baseURL: WHEATLEY_API_URL,
  auth: {
    username: process.env.WHEATLEY_USER,
    password: process.env.WHEATLEY_PASS
  },
  headers: {
    'Content-Type': 'application/json'
  },
  timeout: 5000 // 👈 Add timeout so it fails fast
});

export const sendApiRequest = async (data) => {
  console.log('Forwarding data to PHP:', JSON.stringify(data, null, 2));

  try {
    const response = await apiClient.post('', data);
    console.log('PHP Response:', response.data);
    return response.data;
  } catch (error) {
    console.error('API error:', error.message);

    if (error.code === 'ECONNABORTED') {
      console.error('Request timed out');
    }

    if (error.response) {
      console.error('Status Code:', error.response.status);
      console.error('Response Body:', error.response.data);
    } else if (error.request) {
      console.error('No response received from server.');
    }

    return { status: 'error', message: error.message };
  }
};