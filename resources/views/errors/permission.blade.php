<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ في الصلاحيات - نظام حجوزات {{ config('brand.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f6f8fb 0%, #e9f0f8 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full space-y-8 p-8 bg-white rounded-2xl shadow-2xl">
        <div class="text-center space-y-6">
            <div class="flex justify-center">
                <div class="bg-red-100 p-4 rounded-full">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
            </div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">
                خطأ في النظام
            </h1>
            <p class="text-gray-600 text-lg">
                {{ $message ?? 'ليس لديك صلاحية للوصول لهذه الصفحة' }}
            </p>
            
            @if(isset($permissions) && count($permissions) > 0)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-700 mb-2">الصلاحيات المطلوبة:</p>
                <ul class="text-sm text-gray-600 space-y-1">
                    @foreach($permissions as $permission)
                        <li class="flex items-center">
                            <i class="fas fa-lock text-red-500 ml-2"></i>
                            {{ $permission }}
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <div class="flex gap-4">
            <a href="{{ route('dashboard') }}" 
               class="flex-1 px-4 py-3 bg-indigo-600 text-white text-center font-semibold rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-home ml-2"></i>
                العودة للرئيسية
            </a>
            <button onclick="history.back()" 
                    class="flex-1 px-4 py-3 bg-gray-300 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-400 transition-colors">
                <i class="fas fa-arrow-right ml-2"></i>
                رجوع
            </button>
        </div>

        <div class="text-center text-sm text-gray-500">
            <p>إذا كنت تعتقد أن هذا خطأ، يرجى التواصل مع الإدارة</p>
        </div>
    </div>
</body>
</html> 