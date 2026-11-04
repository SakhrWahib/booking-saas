@extends('layouts.app')

@section('content')
@if(!isset($roles) || $roles->count() == 0)
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>تحذير:</strong> لا توجد أدوار متاحة. يرجى التأكد من وجود أدوار في قاعدة البيانات.
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>أخطاء في التحقق:</strong>
        <ul class="mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>خطأ:</strong> {{ session('error') }}
    </div>
@endif

<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">إضافة مستخدم جديد</h1>
                        <p class="text-gray-600 mt-1">قم بملء البيانات التالية لإضافة مستخدم جديد للنظام</p>
                    </div>
                    <a href="{{ route('admin.users.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                        <i class="fas fa-arrow-right"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.users.store') }}" id="userForm">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- البيانات الأساسية -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            البيانات الأساسية
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- اسم المستخدم -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المستخدم <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="أدخل اسم المستخدم" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- البريد الإلكتروني -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('email') border-red-500 @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="example@email.com" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كلمة المرور -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('password') border-red-500 @enderror" 
                                   id="password" name="password" 
                                   placeholder="أدخل كلمة المرور" required>
                            <p class="mt-1 text-sm text-gray-500">يجب أن تكون 8 أحرف على الأقل</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="أعد إدخال كلمة المرور" required>
                        </div>
                    </div>
                </div>

                <!-- معلومات الصلاحيات والحالة -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-green-600"></i>
                            الصلاحيات والحالة
                        </h3>
                    </div>
                    
                    <div class="px-6 py-6 space-y-6">
                        <!-- الدور -->
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الدور <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('role_id') border-red-500 @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">اختر دور المستخدم</option>
                                @if(isset($roles) && $roles->count() > 0)
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>لا توجد أدوار متاحة</option>
                                @endif
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- الصلاحيات المخصصة -->
                        <div id="customPermissions" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                الصلاحيات المخصصة
                            </label>
                            <div class="space-y-3 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                @php
                                    $permissions = \App\Models\Permission::getGroupedPermissions();
                                @endphp
                                
                                @foreach($permissions as $category => $categoryPermissions)
                                    <div class="border-b border-gray-100 pb-3 last:border-b-0">
                                        <h4 class="font-medium text-gray-900 mb-2">{{ ucfirst($category) }}</h4>
                                        <div class="space-y-2">
                                            @foreach($categoryPermissions as $permission)
                                                <label class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}"
                                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                    <span class="mr-2 text-sm text-gray-700">{{ $permission->display_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-1 text-sm text-gray-500">يمكنك تحديد صلاحيات مخصصة للمستخدم</p>
                        </div>

                        <!-- حالة الحساب -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                حالة الحساب
                            </label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       id="is_active" name="is_active" value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label for="is_active" class="mr-2 block text-sm text-gray-900">
                                    حساب نشط
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">المستخدمون غير النشطين لا يمكنهم تسجيل الدخول</p>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-blue-900">معلومات مهمة</h4>
                                    <ul class="mt-2 text-sm text-blue-700 space-y-1">
                                        <li>• سيتم إرسال بيانات تسجيل الدخول إلى البريد الإلكتروني</li>
                                        <li>• يمكن للمستخدم تغيير كلمة المرور بعد تسجيل الدخول</li>
                                        <li>• يمكن تعديل الصلاحيات والحالة لاحقاً</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- معاينة الدور -->
                        <div id="rolePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                معاينة الصلاحيات
                            </label>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <div id="roleDescription" class="text-sm text-gray-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        تأكد من صحة البيانات قبل الحفظ
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            إلغاء
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            حفظ المستخدم
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// معاينة الدور
document.getElementById('role_id').addEventListener('change', function() {
    const roleId = this.value;
    const preview = document.getElementById('rolePreview');
    const description = document.getElementById('roleDescription');
    const customPermissions = document.getElementById('customPermissions');
    
    if (roleId) {
        const selectedOption = this.options[this.selectedIndex];
        const roleName = selectedOption.text;
        
        // تحديد الوصف حسب الدور
        let roleDesc = '';
        switch(roleName.trim()) {
            case 'مدير النظام':
                roleDesc = 'صلاحيات كاملة على النظام - إدارة المستخدمين، العملاء، الحجوزات، التقارير';
                customPermissions.classList.add('hidden');
                break;
            case 'مسؤول العملاء والفواتير':
                roleDesc = 'إدارة الحجوزات والعملاء - عرض التقارير الأساسية';
                customPermissions.classList.add('hidden');
                break;
            case 'مسؤول المنتجات':
                roleDesc = 'إدارة المنتجات ومقدمي الخدمة - إدارة جداول العمل';
                customPermissions.classList.add('hidden');
                break;
            default:
                roleDesc = 'صلاحيات محدودة حسب الدور المحدد';
                customPermissions.classList.remove('hidden');
        }
        
        description.textContent = roleDesc;
        preview.classList.remove('hidden');
    } else {
        preview.classList.add('hidden');
        customPermissions.classList.add('hidden');
    }
});

// التحقق من النموذج قبل الإرسال
document.getElementById('userForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const roleId = document.getElementById('role_id').value;
    const isActive = document.getElementById('is_active').checked;

    console.log('Form data:', {
        name: name,
        email: email,
        password: password ? '***' : 'empty',
        password_confirmation: passwordConfirmation ? '***' : 'empty',
        role_id: roleId,
        is_active: isActive
    });

    if (!name) {
        e.preventDefault();
        alert('يرجى إدخال اسم المستخدم');
        document.getElementById('name').focus();
        return;
    }

    if (!email) {
        e.preventDefault();
        alert('يرجى إدخال البريد الإلكتروني');
        document.getElementById('email').focus();
        return;
    }

    if (!password) {
        e.preventDefault();
        alert('يرجى إدخال كلمة المرور');
        document.getElementById('password').focus();
        return;
    }

    if (password.length < 8) {
        e.preventDefault();
        alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
        document.getElementById('password').focus();
        return;
    }

    if (password !== passwordConfirmation) {
        e.preventDefault();
        alert('كلمة المرور غير متطابقة');
        document.getElementById('password_confirmation').focus();
        return;
    }

    if (!roleId) {
        e.preventDefault();
        alert('يرجى اختيار دور المستخدم');
        document.getElementById('role_id').focus();
        return;
    }

    console.log('Form validation passed, submitting...');
});

// إظهار/إخفاء كلمة المرور
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const type = field.type === 'password' ? 'text' : 'password';
    field.type = type;
}
</script>
@endpush
