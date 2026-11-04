@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" dir="rtl">
    <div class="p-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                            <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            {{ $user->name }}
                        </h1>
                        <p class="text-gray-600 mt-1">المستخدم رقم #{{ $user->id }} - تاريخ التسجيل: {{ $user->created_at->format('Y/m/d') }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
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

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- User Info & Statistics -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            المعلومات الأساسية
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 p-2 rounded-lg">
                                        <i class="fas fa-hashtag text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">رقم المستخدم</p>
                                        <p class="font-semibold text-gray-900">#{{ $user->id }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <div class="bg-green-100 p-2 rounded-lg">
                                        <i class="fas fa-envelope text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">البريد الإلكتروني</p>
                                        <a href="mailto:{{ $user->email }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                            {{ $user->email }}
                                        </a>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-purple-100 p-2 rounded-lg">
                                        <i class="fas fa-user-tag text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">الدور</p>
                                        @if($user->role)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $user->role->name === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                   ($user->role->name === 'office' ? 'bg-blue-100 text-blue-800' : 
                                                   ($user->role->name === 'operator' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ $user->role->display_name }}
                                            </span>
                                        @else
                                            <p class="font-semibold text-gray-400">غير محدد</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-indigo-100 p-2 rounded-lg">
                                        <i class="fas fa-calendar-plus text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">تاريخ التسجيل</p>
                                        <p class="font-semibold text-gray-900">{{ $user->created_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-orange-100 p-2 rounded-lg">
                                        <i class="fas fa-clock text-orange-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">آخر تحديث</p>
                                        <p class="font-semibold text-gray-900">{{ $user->updated_at->format('Y/m/d H:i') }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="bg-gray-100 p-2 rounded-lg">
                                        <i class="fas fa-sign-in-alt text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">آخر تسجيل دخول</p>
                                        @if($user->last_login_at)
                                            <p class="font-semibold text-gray-900">{{ $user->last_login_at->format('Y/m/d H:i') }}</p>
                                        @else
                                            <p class="font-semibold text-gray-400">لم يسجل دخول</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-chart-line text-green-600"></i>
                            إحصائيات المستخدم
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                                <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-calendar-check text-blue-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-blue-600">{{ (int) abs($user->created_at->diffInDays(now())) }}</div>
                                <div class="text-xs text-blue-600">أيام العضوية</div>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-green-600">{{ (int) abs($user->updated_at->diffInHours(now())) }}</div>
                                <div class="text-xs text-green-600">ساعات منذ آخر تحديث</div>
                            </div>
                            
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center">
                                <div class="bg-purple-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-check-circle text-purple-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-purple-600">{{ $user->is_active ? 'نشط' : 'غير نشط' }}</div>
                                <div class="text-xs text-purple-600">حالة الحساب</div>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                                <div class="bg-yellow-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-2">
                                    <i class="fas fa-shield-alt text-yellow-600"></i>
                                </div>
                                <div class="text-2xl font-bold text-yellow-600">{{ $user->role ? $user->role->name : 'غير محدد' }}</div>
                                <div class="text-xs text-yellow-600">نوع الدور</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                @if($user->role)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-shield-alt text-indigo-600"></i>
                            معلومات الدور والصلاحيات
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start gap-4">
                                <div class="bg-indigo-100 p-3 rounded-lg">
                                    <i class="fas fa-user-shield text-indigo-600 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-2">{{ $user->role->display_name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3">{{ $user->role->description ?? 'لا يوجد وصف للدور' }}</p>
                                    
                                    <!-- الصلاحيات حسب الدور -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @if($user->role->name === 'admin')
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة المستخدمين</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة العملاء</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة الحجوزات</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>عرض التقارير</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة النظام</span>
                                            </div>
                                        @elseif($user->role->name === 'office')
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة الحجوزات</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>إدارة العملاء</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>عرض التقارير الأساسية</span>
                                            </div>
                                        @elseif($user->role->name === 'operator')
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>عرض الحجوزات</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>تحديث حالة الحجوزات</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>عرض الحجوزات الشخصية</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-sm">
                                                <i class="fas fa-check text-green-600"></i>
                                                <span>تحديث البيانات الشخصية</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Account Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-user-check text-red-600"></i>
                            حالة الحساب
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        @if($user->is_active)
                            <div class="text-center">
                                <div class="bg-green-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-green-800 mb-1">حساب نشط</h4>
                                <p class="text-sm text-green-600">يمكن للمستخدم تسجيل الدخول واستخدام النظام</p>
                            </div>
                        @else
                            <div class="text-center">
                                <div class="bg-red-100 p-4 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                                </div>
                                <h4 class="font-semibold text-red-800 mb-1">حساب غير نشط</h4>
                                <p class="text-sm text-red-600">لا يمكن للمستخدم تسجيل الدخول</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-tools text-gray-600"></i>
                            إجراءات سريعة
                        </h3>
                    </div>
                    <div class="px-6 py-6 space-y-3">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-edit"></i>
                            تعديل البيانات
                        </a>
                        
                        <button onclick="resetPassword()" 
                                class="w-full bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-key"></i>
                            إعادة تعيين كلمة المرور
                        </button>
                        
                        <a href="mailto:{{ $user->email }}" 
                           class="w-full bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-envelope"></i>
                            إرسال بريد إلكتروني
                        </a>

                        @if($user->id != auth()->id())
                        <button onclick="toggleUserStatus()" 
                                class="w-full {{ $user->is_active ? 'bg-red-100 hover:bg-red-200 text-red-700' : 'bg-green-100 hover:bg-green-200 text-green-700' }} px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                            {{ $user->is_active ? 'إلغاء التفعيل' : 'تفعيل الحساب' }}
                        </button>
                        @endif

                        @if($user->id != auth()->id() && (!$user->hasRole('admin') || $user->id != auth()->id()))
                        <hr class="my-4">
                        <button onclick="confirmDelete()" 
                                class="w-full bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center justify-center gap-2">
                            <i class="fas fa-trash"></i>
                            حذف المستخدم
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Account Timeline -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-history text-yellow-600"></i>
                            سجل الحساب
                        </h3>
                    </div>
                    <div class="px-6 py-6">
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="bg-blue-100 p-2 rounded-full">
                                    <i class="fas fa-user-plus text-blue-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">تم إنشاء الحساب</p>
                                    <p class="text-xs text-gray-500">{{ $user->created_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-3">
                                <div class="bg-green-100 p-2 rounded-full">
                                    <i class="fas fa-edit text-green-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">آخر تحديث للبيانات</p>
                                    <p class="text-xs text-gray-500">{{ $user->updated_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            
                            @if($user->last_login_at)
                            <div class="flex items-start gap-3">
                                <div class="bg-purple-100 p-2 rounded-full">
                                    <i class="fas fa-sign-in-alt text-purple-600 text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">آخر تسجيل دخول</p>
                                    <p class="text-xs text-gray-500">{{ $user->last_login_at->format('Y/m/d H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <i class="fas fa-key text-green-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">إعادة تعيين كلمة المرور</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">سيتم إرسال كلمة مرور جديدة إلى البريد الإلكتروني <strong>{{ $user->email }}</strong></p>
                <p class="text-sm text-yellow-600 mt-2">سيتم إلغاء تنشيط جميع الجلسات الحالية</p>
            </div>
            <div class="flex gap-3 px-4 py-3">
                <button onclick="closeResetPasswordModal()" 
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    إلغاء
                </button>
                <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700">
                        إعادة التعيين
                    </button>
                </form>
            </div>
        </div>
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
                        class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400">
                    إلغاء
                </button>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700">
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
function resetPassword() {
    document.getElementById('resetPasswordModal').classList.remove('hidden');
}

function closeResetPasswordModal() {
    document.getElementById('resetPasswordModal').classList.add('hidden');
}

function toggleUserStatus() {
    if (confirm('هل أنت متأكد من تغيير حالة هذا المستخدم؟')) {
        // إنشاء نموذج مؤقت لإرسال طلب POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.users.toggle-status', $user->id) }}`;
        
        // إضافة CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // إضافة النموذج للصفحة وإرساله
        document.body.appendChild(form);
        form.submit();
    }
}

function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modals when clicking outside
document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeResetPasswordModal();
    }
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush
