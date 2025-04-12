// ✅ Hàm kiểm tra môi trường (localhost vs production)
function getApiBaseUrl() {
    const host = window.location.hostname;
    if (host === 'localhost' || host === '127.0.0.1') {
        return 'http://127.0.0.1:8000/api';
    }
    return 'https://testphp.loophole.site/api';
}

// ✅ Gửi login request
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const data = { email, password };

    const apiBaseUrl = getApiBaseUrl();

    fetch(`${apiBaseUrl}/auth/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Login response:', data);

        if (data.success && data.user && Array.isArray(data.user.roles)) {
            localStorage.setItem('access_token', data.access_token);

            if (data.user.roles.includes('Admin')) {
                window.location.href = '/admin/dashboard';
            } else {
                showErrorMessage('Bạn không có quyền truy cập trang quản trị.');
            }
        } else {
            showErrorMessage('Đăng nhập thất bại: ' + (data.message || 'Vui lòng kiểm tra lại thông tin.'));
        }
    })
    .catch(error => {
        console.error('Lỗi:', error);
        showErrorMessage('Có lỗi xảy ra, vui lòng thử lại sau.');
    });
});

// ✅ Hiển thị thông báo lỗi
function showErrorMessage(message) {
    const errorMessageElement = document.getElementById('error-message');
    const errorTextElement = document.getElementById('error-text');
    if (errorMessageElement && errorTextElement) {
        errorTextElement.textContent = message;
        errorMessageElement.classList.remove('d-none');
    }
}

// ✅ Ẩn thông báo lỗi
function hideErrorMessage() {
    const errorMessageElement = document.getElementById('error-message');
    if (errorMessageElement) {
        errorMessageElement.classList.add('d-none');
    }
}

// ✅ Gắn sự kiện nút đóng thông báo lỗi
document.getElementById('close-error-message')?.addEventListener('click', hideErrorMessage);

// ✅ Hiển thị/ẩn mật khẩu
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');

    if (!passwordField || !toggleIcon) return;

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}