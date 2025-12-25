<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Affiliate</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .affiliate-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-center">
            <div class="inline-block p-4 bg-white rounded-full mb-4">
                <i class="fas fa-user-tie text-4xl affiliate-icon"></i>
            </div>
            <h1 class="text-3xl font-bold text-white">Affiliate Login</h1>
            <p class="text-blue-100 mt-2">Masukkan kode affiliate Anda</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                        <div>
                            @foreach($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <p class="text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('affiliate.login.process') }}">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="kode">
                        Kode Affiliate
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input 
                            type="text" 
                            id="kode" 
                            name="kode" 
                            required
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                            placeholder="Masukkan kode affiliate"
                            autofocus
                            value="{{ old('kode') }}"
                        >
                    </div>
                    <p class="text-gray-500 text-xs mt-2 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>
                        Contoh: ABC-12345
                    </p>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 flex items-center justify-center"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk ke Dashboard
                </button>

                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="text-center">
                        <a href="/" class="text-gray-600 hover:text-blue-600 text-sm flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke halaman utama
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 px-8 py-4 text-center">
            <p class="text-gray-500 text-sm">
                <i class="fas fa-shield-alt mr-1"></i>
                Akses terbatas hanya untuk affiliate terdaftar
            </p>
        </div>
    </div>

    <script>
        // Auto uppercase untuk kode
        document.getElementById('kode').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9\-]/g, '');
        });

        // Enter untuk submit
        document.getElementById('kode').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });

        // Focus ke input saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('kode').focus();
        });
    </script>
</body>
</html>