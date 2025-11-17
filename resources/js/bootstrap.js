// Bootstrap file for Laravel
// Add Laravel CSRF token to all AJAX requests
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.csrfToken = token.content;
}