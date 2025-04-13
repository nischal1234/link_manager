document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-form');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(loginForm);
            formData.append('csrf_token', csrfToken);

            fetch('pages/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('success')) {
                    window.location.href = 'pages/dashboard.php';
                } else {
                    document.getElementById('login-error').innerHTML = sanitizeHTML(data);
                }
            })
            .catch(err => {
                console.error('Login Error:', err);
                document.getElementById('login-error').innerHTML = 'An error occurred. Please try again later.';
            });
        });
    }
});

// Basic HTML Sanitization to prevent XSS
function sanitizeHTML(str) {
    const tempDiv = document.createElement('div');
    tempDiv.textContent = str;
    return tempDiv.innerHTML;
}
