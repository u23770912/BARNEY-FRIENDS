import express from 'express';
import cors from 'cors';
import bodyParser from 'body-parser';
import { sendApiRequest } from './utils.js';

const app = express();
const PORT = 3001;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Logging middleware
app.use((req, res, next) => {
  console.log('Incoming request:', req.method, req.url);
  next(); // ✅ Required
});

// Proxy route
app.post('/api', async (req, res) => {
  const { type } = req.body;

  if (!type) {
    return res.status(400).json({ status: 'error', message: 'Request type not specified' });
  }

  try {
    const result = await sendApiRequest(req.body);
    res.json(result);
  } catch (err) {
    console.error('Error forwarding request:', err);
    res.status(500).json({ status: 'error', message: 'Internal Server Error' });
  }
});

// Start server
app.listen(PORT, () => {
  console.log(`Local API server running at http://localhost:${PORT}`);
});