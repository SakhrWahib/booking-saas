@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">تعديل بيانات المستخدم</h1>
                        <p class="text-gray-600 mt-1">تعديل بيانات المستخدم: <span class="font-semibold text-blue-600">{{ $user->name }}</span></p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-arrow-right"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('admin.users.update', $user) }}" id="userForm">
            @csrf
            @method('PUT')
            
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
                        <!-- معلومات المستخدم -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $user->name }}</h4>
                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                        <span>رقم المستخدم: <span class="font-semibold">#{{ $user->id }}</span></span>
                                        <span>تاريخ التسجيل: {{ $user->created_at->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- اسم المستخدم -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المستخدم <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('name') border-red-500 @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" 
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
                                   id="email" name="email" value="{{ old('email', $user->email) }}" 
                                   placeholder="example@email.com" required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- كلمة المرور الجديدة -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور الجديدة
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('password') border-red-500 @enderror" 
                                   id="password" name="password" 
                                   placeholder="اتركها فارغة إذا لم ترد تغييرها">
                            <p class="mt-1 text-sm text-gray-500">اتركها فارغة إذا لم ترد تغيير كلمة المرور</p>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- تأكيد كلمة المرور الجديدة -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور الجديدة
                            </label>
                            <input type="password" 
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="أعد إدخال كلمة المرور الجديدة">
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-blue-600">{{ $user->created_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-600">عضو منذ</div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <div class="text-lg font-bold text-green-600">{{ $user->updated_at->diffForHumans() }}</div>
                                <div class="text-xs text-gray-600">آخر تحديث</div>
                            </div>
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
                        <!-- الدور الحالي -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-tag text-gray-600 text-xl"></i>
                                <div>
                                    <h4 class="font-medium text-gray-900">الدور الحالي</h4>
                                    <p class="text-sm text-gray-600">
                                        @if($user->role)
                                            {{ $user->role->display_name }}
                                        @else
                                            غير محدد
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- تغيير الدور -->
                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2">
                                تغيير الدور <span class="text-red-500">*</span>
                            </label>
                            <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right @error('role_id') border-red-500 @enderror" 
                                    id="role_id" name="role_id" required>
                                <option value="">اختر دور المستخدم</option>
                                @foreach($roles ?? [] as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                       {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label for="is_active" class="mr-2 block text-sm text-gray-900">
                                    حساب نشط
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">المستخدمون غير النشطين لا يمكنهم تسجيل الدخول</p>
                        </div>

                        <!-- معلومات الأمان -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-shield-alt text-yellow-600 mt-1"></i>
                                <div>
                                    <h4 class="font-medium text-yellow-900">معلومات الأمان</h4>
                                    <ul class="mt-2 text-sm text-yellow-700 space-y-1">
                                        <li>• سيتم إرسال إشعار للمستخدم عند تغيير كلمة المرور</li>
                                        <li>• يمكن للمستخدم تغيير كلمة المرور من إعدادات الحساب</li>
                                        <li>• تغيير الدور قد يؤثر على الصلاحيات المتاحة</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- الصلاحيات المخصصة -->
                        <div id="customPermissions" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                الصلاحيات المخصصة
                            </label>
                            <div class="space-y-3 max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3">
                                @php
                                    $permissions = \App\Models\Permission::getGroupedPermissions();
                                    $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();
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
                                                           {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}
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

                        <!-- معاينة الدور الجديد -->
                        <div id="rolePreview" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                معاينة الصلاحيات الجديدة
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
                    <div class="flex items-center gap-4">
                        @if(!$user->hasRole('admin') || $user->id != auth()->id())
                        <button type="button" 
                                class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i>
                            حذف المستخدم
                        </button>
                        @else
                        <div class="text-sm text-amber-600 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            لا يمكن حذف حسابك الشخصي
                        </div>
                        @endif
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
                            حفظ التغييرات
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">تأكيد حذف المستخدم</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">هل أنت متأكد من حذف المستخدم <strong>{{ $user->name }}</strong>؟</p>
                <p class="text-sm text-red-500 mt-2">هذا الإجراء لا يمكن التراجع عنه.</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    إلغاء
                </button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        حذف نهائياً
                    </button>
                </form>
            </div>
        </div>
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

// تهيئة الصلاحيات المخصصة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role_id');
    const customPermissions = document.getElementById('customPermissions');
    
    if (roleSelect.value) {
        const selectedOption = roleSelect.options[roleSelect.selectedIndex];
        const roleName = selectedOption.text;
        
        // إظهار الصلاحيات المخصصة إذا كان الدور مخصص
        if (roleName.trim() !== 'مدير النظام' && 
            roleName.trim() !== 'مسؤول العملاء والفواتير' && 
            roleName.trim() !== 'مسؤول المنتجات') {
            customPermissions.classList.remove('hidden');
        }
    }
});

// التحقق من النموذج قبل الإرسال
document.getElementById('userForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    const roleId = document.getElementById('role_id').value;

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

    if (password && password.length < 8) {
        e.preventDefault();
        alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل');
        document.getElementById('password').focus();
        return;
    }

    if (password && password !== passwordConfirmation) {
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
});

// تأكيد الحذف
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
