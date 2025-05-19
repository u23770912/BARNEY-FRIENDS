document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('loginForm');
  const error = document.getElementById('error');
  const success = document.getElementById('success');

  form.addEventListener('submit', async (e) => {
    e.preventDefault(); //prevents reload
    error.textContent = '';
    success.textContent = '';

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!email || !password) {
      error.textContent = 'All fields are required.';
      return;
    }

    if (!emailRegex.test(email)) {
      error.textContent = 'Please enter a valid email address.';
      return;
    }

    if (!passwordRegex.test(password)) {
      error.textContent = 'Enter a valid password';
      return;
    }

    try {
      console.log('Sending login request…');
    
      const res = await fetch('https://wheatley.cs.up.ac.za/u23770912/api.php', {
        method:  'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          type:     'Login',
          email, 
          password
        })
      });
    
      console.log('HTTP status:', res.status, res.statusText);
    
      // Grab the raw text so we can see if it's valid JSON:
      const text = await res.text();
      console.log('Raw response body:', text);
    
      // Now try to parse it:
      let result;
      try {
        result = JSON.parse(text);
      } catch (parseErr) {
        console.error('Failed to parse JSON:', parseErr);
        throw new Error('Invalid JSON in response');
      }
    
      console.log('Parsed JSON payload:', result);
    
      if (result.status === 'success') {
        localStorage.setItem('apikey', result.data.apikey);
        localStorage.setItem('name',    result.data.name);
        window.location.href = 'index.php';
      } else {
        // the API gave back an error object
        error.textContent = result.message || 'Login failed';
      }
    
    } catch (err) {
      console.error('Fetch / processing error:', err);
      error.textContent = 'Something went wrong. Please try again later.';
    }
  });
});