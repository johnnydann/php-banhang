<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('productImages/icon.png') }}">

    <title>Admin Login | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="toast-container">
                <div id="error-message" class="toast-notification toast-error d-none" role="alert">
                    <div class="toast-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="toast-content">
                        <span id="error-text"></span>
                    </div>
                    <button type="button" id="close-error-message" class="toast-close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-shield-alt"></i>
                    <h1>Admin Home</h1>
                </div>
            </div>

            <form id="loginForm" class="login-form">
                <div class="input-group">
                    <label for="email">Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope icon-left"></i>
                        <input type="email" id="email" placeholder="Enter your email" required>
                    </div>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon-left"></i>
                        <input type="password" id="password" placeholder="Enter your password" required>
                        <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                </div>

                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" id="remember"> Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="login-footer">
                <p>Don't have an account? <a href="#">Contact Support</a></p>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>