<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPANDU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .slide-in {
            animation: slideInFromTop 0.8s ease-out forwards;
        }
        @keyframes slideInFromTop {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .input-focus:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
        }
        .alert-message {
            animation: fadeInOut 5s ease-in-out forwards;
        }
        @keyframes fadeInOut {
            0%, 100% { opacity: 0; }
            10%, 90% { opacity: 1; }
        }
    </style>
</head>
<body class="login-gradient min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8 slide-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4 shadow-lg">
                <i class="fas fa-boxes text-blue-600 text-3xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">SIPANDU</h1>
            <p class="text-blue-200">Silakan login untuk melanjutkan</p>
        </div>

        @if(session('notification'))
            <div class="alert-message fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm {{ session('notification')['type'] === 'success' ? 'bg-green-500' : 'bg-red-500' }} text-white slide-in">
                <div class="flex items-center">
                    <i class="fas {{ session('notification')['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle' }} mr-3"></i>
                    <span>{{ session('notification')['message'] }}</span>
                </div>
            </div>
        @endif

        <div class="glass-effect rounded-2xl p-8 shadow-2xl slide-in" style="animation-delay: 0.2s;">
            <form action="{{ route('login_process') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-white text-sm font-medium mb-2" for="username">
                        <i class="fas fa-user mr-2"></i>Username
                    </label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="input-focus w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:border-white transition-all duration-300"
                        placeholder="Masukkan username"
                        required
                    >
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-white text-sm font-medium mb-2" for="password">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input-focus w-full px-4 py-3 bg-white bg-opacity-20 border border-white border-opacity-30 rounded-lg text-white placeholder-blue-200 focus:outline-none focus:border-white transition-all duration-300 pr-12"
                            placeholder="Masukkan password"
                            required
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-200 hover:text-white transition-colors"
                        >
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-blue-200">
                        <input type="checkbox" name="remember" class="mr-2 rounded">
                        <span class="text-sm">Ingat saya</span>
                    </label>
                    <a href="#" class="text-blue-200 hover:text-white text-sm transition-colors">
                        Lupa password?
                    </a>
                </div>

                <button
                    type="submit"
                    class="w-full bg-white text-blue-600 py-3 px-4 rounded-lg font-semibold hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300 transform hover:scale-105"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <div class="flex items-center my-6">
                <div class="flex-grow border-t border-white border-opacity-30"></div>
                <span class="flex-shrink mx-4 text-white text-sm font-medium">atau</span>
                <div class="flex-grow border-t border-white border-opacity-30"></div>
            </div>

            {{-- <a href="{{ route('register.google') }}" class="w-full flex items-center justify-center bg-white text-blue-600 py-3 px-4 rounded-lg font-semibold hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300 transform hover:scale-105">
                <i class="fab fa-google mr-2"></i>
                Masuk dengan Google
            </a> --}}

            <p class="text-center text-blue-200 mt-6 text-sm">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-bold hover:text-white transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </div>

    <script>
        // HANYA JALANKAN LOGIKA YANG BERKAITAN DENGAN UI
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.className = type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
                });
            }

        });
    </script>
</body>
</html>
