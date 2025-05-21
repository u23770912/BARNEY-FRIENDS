document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('signupForm');
  const error = document.getElementById('error');
  const success = document.getElementById('success');

  form.addEventListener('submit', async (e) => {
    e.preventDefault(); //prevents reload
    error.textContent = '';
    success.textContent = '';

    const name = document.getElementById('name').value.trim();
    const surname = document.getElementById('surname').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const userTypeDropdown = document.getElementById('user_type');
    const user_type = userTypeDropdown.options[userTypeDropdown.selectedIndex].value;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

    if (!name || !surname || !email || !password || !user_type) {
      error.textContent = 'All fields are required.';
      return;
    }

    if (!emailRegex.test(email)) {
      error.textContent = 'Please enter a valid email address.';
      return;
    }

    if (!passwordRegex.test(password)) {
      error.textContent = 'Password must be 8+ characters, include uppercase, lowercase, number, and symbol.';
      return;
    }

    try {
      const res = await fetch('https://wheatley.cs.up.ac.za/u23770912/COS221/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          type: 'Register',
          name,
          surname,
          email,
          password
        })
      });

      const result = await res.json();

      if (result.status === 'success') {
        success.textContent = 'Registration successful! You can now log in.';
        localStorage.setItem('apikey', result.data.apikey);
        form.reset();
      } else {
        error.textContent = result.message || 'Email already in use.';
      }
    } catch (err) {
      error.textContent = 'Something went wrong. Please try again later.';
    }
  });
});