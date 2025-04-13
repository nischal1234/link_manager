document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const resultDiv = document.getElementById('results');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    searchInput.addEventListener('input', function() {
        const query = encodeURIComponent(searchInput.value.trim());

        if (query.length > 2) {
            fetch('ajax/search_link.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `query=${query}&csrf_token=${csrfToken}`
            })
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = sanitizeHTML(data);
            })
            .catch(err => {
                console.error('Error fetching search data:', err);
                resultDiv.innerHTML = 'An error occurred. Please try again later.';
            });
        } else {
            resultDiv.innerHTML = '';
        }
    });
});

// Basic HTML Sanitization to prevent XSS
function sanitizeHTML(str) {
    const tempDiv = document.createElement('div');
    tempDiv.textContent = str;
    return tempDiv.innerHTML;
}
