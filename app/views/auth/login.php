<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login · Barangay Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-pink: #ff1493;
            --secondary-purple: #8a2be2;
            --accent-purple: #4b0082;
            --dark-black: #1a1a1a;
            --light-white: #ffffff;
            --soft-pink: #ffe6f2;
            --gradient-pink: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            --gradient-purple: linear-gradient(135deg, #8a2be2 0%, #4b0082 100%);
            --shadow: 0 15px 35px rgba(255, 20, 147, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--soft-pink);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(255, 20, 147, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(138, 43, 226, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 105, 180, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-10px) rotate(1deg); }
            66% { transform: translateY(10px) rotate(-1deg); }
        }

        .login-container {
            background: var(--light-white);
            padding: 50px 40px;
            border-radius: 25px;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 20, 147, 0.1);
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-pink);
        }

        .login-container::after {
            content: '';
            position: absolute;
            bottom: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(from 0deg, transparent, rgba(255, 20, 147, 0.03), transparent);
            animation: rotate 25s linear infinite;
            pointer-events: none;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-header {
            margin-bottom: 40px;
            position: relative;
        }

        .login-header h2 {
            font-size: 2.2rem;
            font-weight: 700;
            background: var(--gradient-pink);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--secondary-purple);
            font-size: 1rem;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-black);
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 15px;
            border: 2px solid rgba(255, 20, 147, 0.2);
            box-sizing: border-box;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(255, 20, 147, 0.1);
            transform: translateY(-2px);
        }

        .form-group input::placeholder {
            color: rgba(138, 43, 226, 0.5);
            font-weight: 400;
        }

        .form-group .password-wrapper {
            position: relative;
        }

        .form-group .password-wrapper input {
            padding-right: 50px;
        }

        .form-group .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-purple);
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 3;
        }

        .form-group .password-wrapper .toggle-password:hover {
            color: var(--primary-pink);
            transform: translateY(-50%) scale(1.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            border-radius: 15px;
            border: none;
            background: var(--gradient-pink);
            color: var(--light-white);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 25px rgba(255, 20, 147, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 20, 147, 0.4);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .links {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid rgba(255, 20, 147, 0.1);
            font-size: 0.95rem;
        }

        .links a {
            color: var(--secondary-purple);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .links a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-pink);
            transition: width 0.3s ease;
        }

        .links a:hover {
            color: var(--primary-pink);
        }

        .links a:hover::after {
            width: 100%;
        }

        .error-message {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 62, 140, 0.1));
            color: #dc3545;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(220, 53, 69, 0.2);
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        .success-message {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
            color: #28a745;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            border: 1px solid rgba(40, 167, 69, 0.2);
            font-weight: 500;
            backdrop-filter: blur(10px);
        }

        /* Floating elements animation */
        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 20, 147, 0.1);
            animation: float-shapes 15s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 20%;
            right: 10%;
            animation-delay: 5s;
            background: rgba(138, 43, 226, 0.1);
        }

        .shape:nth-child(3) {
            width: 100px;
            height: 100px;
            bottom: 20%;
            left: 20%;
            animation-delay: 10s;
            background: rgba(255, 105, 180, 0.1);
        }

        @keyframes float-shapes {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.5;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 40px 30px;
                max-width: none;
            }

            .login-header h2 {
                font-size: 1.8rem;
            }

            .form-group input {
                padding: 12px 16px;
            }

            .btn-login {
                padding: 14px;
                font-size: 1rem;
            }
        }

        /* Loading animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        /* Focus animations */
        .form-group input:focus + label,
        .form-group input:not(:placeholder-shown) + label {
            color: var(--primary-pink);
            font-weight: 700;
        }
    </style>
</head>

<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Sign in to your Barangay Management System account</p>
        </div>

        <?php if (!empty($error)) : ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)) : ?>
            <div class="success-message">
                <i class="fas fa-check-circle mr-2"></i>
                <?= $success ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('auth/login') ?>" id="loginForm">
            <div class="form-group">
                <label for="username">
                    <i class="fas fa-user mr-2"></i>Username
                </label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
            </div>
            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock mr-2"></i>Password
                </label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
                </div>
            </div>
            <button type="submit" class="btn-login" id="loginBtn">
                <i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard
            </button>
        </form>

        <div class="links">
            <p>Don't have an account? <a href="<?= site_url('auth/register'); ?>">
                <i class="fas fa-user-plus mr-1"></i>Create Account
            </a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const loginForm = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');

            togglePassword.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });

            // Form submission with loading animation
            loginForm.addEventListener('submit', function(e) {
                loginBtn.classList.add('loading');
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
                loginBtn.disabled = true;

                // Re-enable after 3 seconds (in case of error)
                setTimeout(() => {
                    loginBtn.classList.remove('loading');
                    loginBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Login to Dashboard';
                    loginBtn.disabled = false;
                }, 3000);
            });

            // Input focus animations
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                });
            });

            // Floating shapes animation enhancement
            const shapes = document.querySelectorAll('.shape');
            shapes.forEach((shape, index) => {
                shape.style.animationDelay = `${index * 2}s`;
            });

            // Add subtle entrance animation to the container
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(30px)';

            setTimeout(() => {
                container.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
                container.style.opacity = '1';
                container.style.transform = 'translateY(0)';
            }, 100);

            // Add ripple effect to button
            loginBtn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = (e.offsetX - 10) + 'px';
                ripple.style.top = (e.offsetY - 10) + 'px';
                ripple.style.width = '20px';
                ripple.style.height = '20px';

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });

            // Add ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</body>

</html>