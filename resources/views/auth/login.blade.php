<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نضام حجوزات {{ config('brand.name') }}</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f8 100%);
        }
        .login-container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .input-icon {
            color: #4F46E5;
        }
        .form-input:focus {
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-container max-w-md w-full space-y-8 p-8 rounded-2xl shadow-2xl">
        <div class="text-center space-y-6">
            <div class="flex justify-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-cat text-4xl text-purple-600"></i>
                </div>
            </div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">
                نضام حجوزات {{ config('brand.name') }}
            </h1>
            <p class="text-gray-600 text-sm">
                مرحباً بك في لوحة التحكم. يرجى تسجيل الدخول للمتابعة
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">البريد الإلكتروني</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-indigo-600"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="form-input block w-full pr-10 py-3 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-right"
                            placeholder="أدخل بريدك الإلكتروني"
                            value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">كلمة المرور</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-shield-alt text-indigo-600"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="form-input block w-full pr-10 py-3 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 text-right"
                            placeholder="أدخل كلمة المرور">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="mr-2 block text-sm font-medium text-gray-700">
                        تذكرني
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 ease-in-out">
                    <span class="absolute right-0 inset-y-0 flex items-center pr-3">
                        <i class="fas fa-arrow-right-to-bracket text-indigo-300 group-hover:text-indigo-200 transition-colors duration-200"></i>
                    </span>
                    تسجيل الدخول
                </button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <div class="flex items-center justify-center space-x-2 space-x-reverse text-sm">
                <i class="fas fa-lock text-purple-500"></i>
                <span class="text-gray-500">تسجيل دخول آمن</span>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="fixed bottom-4 left-4 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg">
        <div class="flex items-center space-x-2 space-x-reverse">
            <i class="fas fa-circle-exclamation text-red-500"></i>
            <p class="text-sm font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif
</body>
</html>
