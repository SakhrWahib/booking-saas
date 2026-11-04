@extends('layouts.app')

@section('content')
@php
    // دالة الترجمة المحلية
    function t($key, $replace = []) {
        $translation = __('web.languages.' . $key, $replace);
        return $translation;
    }
@endphp

<div class="p-2 sm:p-4 lg:p-8 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- البطاقات الإحصائية -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 mb-4">
        <!-- إجمالي اللغات -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-indigo-50 p-2 rounded-lg">
                    <i class="fas fa-language text-indigo-600 text-base"></i>
                </div>
                <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md">{{ t('total_languages') }}</span>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-0.5">{{ $languages->count() }}</h3>
            <p class="text-xs text-gray-500">{{ t('total_languages') }}</p>
            <div class="mt-1.5 flex items-center text-xs text-green-600">
                <i class="fas fa-globe ml-1"></i>
                <span>{{ t('multilingual') }}</span>
            </div>
        </div>

        <!-- اللغات النشطة -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-green-50 p-2 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 text-base"></i>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-md">{{ t('active_languages') }}</span>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-0.5">{{ $activeLanguages }}</h3>
            <p class="text-xs text-gray-500">{{ t('active_languages') }}</p>
            <div class="mt-1.5 flex items-center text-xs text-green-600">
                <i class="fas fa-chart-line ml-1"></i>
                <span>{{ t('available') }}</span>
            </div>
        </div>

        <!-- اللغة الافتراضية -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-blue-50 p-2 rounded-lg">
                    <i class="fas fa-star text-blue-600 text-base"></i>
                </div>
                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">{{ t('default_language') }}</span>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-0.5">{{ $defaultLanguage->native_name }}</h3>
            <p class="text-xs text-gray-500">{{ t('default_language') }}</p>
            <div class="mt-1.5 flex items-center text-xs text-blue-600">
                <i class="fas fa-check ml-1"></i>
                <span>{{ $defaultLanguage->code }}</span>
            </div>
        </div>

        <!-- إجمالي الترجمات -->
        <div class="bg-white rounded-lg shadow-sm p-3 border border-gray-100 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-amber-50 p-2 rounded-lg">
                    <i class="fas fa-translate text-amber-600 text-base"></i>
                </div>
                <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">{{ t('total_translations') }}</span>
            </div>
            <h3 class="text-lg font-bold text-gray-700 mb-0.5">{{ $totalTranslations }}</h3>
            <p class="text-xs text-gray-500">{{ t('total_translations') }}</p>
            <div class="mt-1.5 flex items-center text-xs text-amber-600">
                <i class="fas fa-list ml-1"></i>
                <span>{{ t('across_languages') }}</span>
            </div>
        </div>
    </div>

    <!-- رأس الصفحة -->
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex items-center w-full sm:w-auto">
                <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-2 sm:p-3 rounded-xl ml-3 sm:ml-4">
                    <i class="fas fa-language text-white text-lg sm:text-xl"></i>
                </div>
                <div class="flex-grow sm:flex-grow-0">
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-800">{{ t('management') }}</h2>
                    <p class="text-xs sm:text-sm text-gray-500">{{ t('management_description') }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 items-center">
                <!-- فلتر الحالة -->
                <select id="statusFilter" onchange="applyFilters()" class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 bg-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">{{ t('status.all') }}</option>
                    <option value="active">{{ t('status.active') }}</option>
                    <option value="inactive">{{ t('status.inactive') }}</option>
                </select>

                <div class="relative flex-grow sm:flex-grow-0">
                    <input type="text" 
                        id="searchInput" 
                        placeholder="{{ t('search_placeholder') }}" 
                        class="w-full pl-10 pr-4 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <i class="fas fa-search absolute right-3 top-2.5 text-gray-400"></i>
                </div>
                <button onclick="openCreateModal()" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-4 sm:px-6 py-1.5 rounded-lg flex items-center justify-center text-sm">
                    <i class="fas fa-plus-circle ml-2"></i>
                    {{ t('add_new') }}
                </button>
            </div>
        </div>
    </div>

    <!-- جدول اللغات -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200 hidden md:block">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.flag') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.code') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.name') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.native_name') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.translations') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.status') }}</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ t('table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($languages as $language)
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($language->flag && Storage::disk('public')->exists($language->flag))
                                <img class="h-8 w-8 rounded-lg object-cover" src="{{ Storage::url($language->flag) }}" alt="{{ $language->name }}">
                            @else
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <i class="fas fa-flag text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg">
                                {{ strtoupper($language->code) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $language->name }}
                                @if($language->is_default)
                                    <span class="mr-1 text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">{{ t('status.default') }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">{{ $language->native_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg">
                                {{ $language->translations_count }} {{ t('table.translations') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="toggleStatus({{ $language->id }})" 
                                class="px-3 py-1 rounded-lg text-xs font-semibold transition-colors duration-150
                                {{ $language->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                {{ $language->is_active ? t('status.active') : t('status.inactive') }}
                            </button>
                        </td>                                                                                         
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-1 space-x-reverse">
                            <button onclick="editLanguage({{ $language->id }})" class="bg-amber-100 text-amber-600 hover:bg-amber-200 p-2 rounded-lg transition duration-150 ease-in-out" title="{{ t('actions.edit') }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if(!$language->is_default)
                            <button onclick="deleteLanguage({{ $language->id }})" class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg transition duration-150 ease-in-out" title="{{ t('actions.delete') }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- عرض البطاقات للشاشات الصغيرة -->
    <div class="space-y-4 md:hidden">
        @foreach($languages as $language)
        <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center space-x-3 space-x-reverse">
                    @if($language->flag && Storage::disk('public')->exists($language->flag))
                        <img class="h-12 w-12 rounded-xl object-cover" src="{{ Storage::url($language->flag) }}" alt="{{ $language->name }}">
                    @else
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <i class="fas fa-flag text-gray-400"></i>
                        </div>
                    @endif
                    <div>
                        <div class="font-medium text-gray-900">
                            {{ $language->name }}
                            @if($language->is_default)
                                <span class="mr-1 text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">{{ t('status.default') }}</span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">{{ $language->native_name }}</div>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-lg">
                    {{ strtoupper($language->code) }}
                </span>
            </div>
            <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                <div>
                    <span class="text-gray-500">{{ t('table.translations') }}:</span>
                    <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-lg">
                        {{ $language->translations_count }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-500">{{ t('table.status') }}:</span>
                    <button onclick="toggleStatus({{ $language->id }})" 
                        class="px-2 py-0.5 rounded-lg text-xs font-semibold transition-colors duration-150
                        {{ $language->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $language->is_active ? t('status.active') : t('status.inactive') }}
                    </button>
                </div>
            </div>
            <div class="flex justify-end space-x-2 space-x-reverse">
                <button onclick="editLanguage({{ $language->id }})" class="bg-amber-100 text-amber-600 hover:bg-amber-200 p-2 rounded-lg">
                    <i class="fas fa-edit"></i>
                </button>
                @if(!$language->is_default)
                <button onclick="deleteLanguage({{ $language->id }})" class="bg-red-100 text-red-600 hover:bg-red-200 p-2 rounded-lg">
                    <i class="fas fa-trash"></i>
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- نافذة إضافة لغة جديدة -->
<div id="createModal" tabindex="-1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="bg-white rounded-xl w-full sm:w-11/12 md:w-3/4 lg:w-1/2 mx-auto my-8 shadow-xl">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">إضافة لغة جديدة</h3>
            <button onclick="closeModal('createModal')" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6">
            <form id="createForm" action="{{ route('admin.languages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رمز اللغة</label>
                            <input type="text" name="code" required maxlength="5" placeholder="مثال: ar" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم اللغة</label>
                            <input type="text" name="name" required placeholder="مثال: Arabic" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم المحلي</label>
                            <input type="text" name="native_name" required placeholder="مثال: العربية" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">علم اللغة</label>
                            <input type="file" name="flag" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select name="is_active" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اللغة الافتراضية</label>
                            <select name="is_default" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="0">لا</option>
                                <option value="1">نعم</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('createModal')" 
                        class="px-4 sm:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm sm:text-base">
                        إلغاء
                    </button>
                    <button type="submit" 
                        class="px-4 sm:px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg text-sm sm:text-base">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- نافذة تعديل اللغة -->
<div id="editModal" tabindex="-1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
    <div class="bg-white rounded-xl w-full sm:w-11/12 md:w-3/4 lg:w-1/2 mx-auto my-8 shadow-xl">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-xl flex justify-between items-center">
            <h3 class="text-lg font-bold">تعديل اللغة</h3>
            <button onclick="closeModal('editModal')" class="text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-4 sm:p-6">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رمز اللغة</label>
                            <input type="text" name="code" id="edit_code" required maxlength="5" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اسم اللغة</label>
                            <input type="text" name="name" id="edit_name" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم المحلي</label>
                            <input type="text" name="native_name" id="edit_native_name" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">علم اللغة</label>
                            <input type="file" name="flag" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <div id="current_flag_container" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
                            <select name="is_active" id="edit_is_active" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="1">نشط</option>
                                <option value="0">غير نشط</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">اللغة الافتراضية</label>
                            <select name="is_default" id="edit_is_default" required 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="0">لا</option>
                                <option value="1">نعم</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                    <button type="button" onclick="closeModal('editModal')" 
                        class="px-4 sm:px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-sm sm:text-base">
                        إلغاء
                    </button>
                    <button type="submit" 
                        class="px-4 sm:px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg text-sm sm:text-base">
                        حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div class="fixed bottom-4 left-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg" id="success-message">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="fixed bottom-4 left-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg" id="error-message">
    {{ session('error') }}
</div>
@endif

@push('scripts')
<script>
// تحديث النصوص في JavaScript
const translations = {
    confirmStatus: "{{ t('messages.confirm_status') }}",
    confirmDelete: "{{ t('messages.confirm_delete') }}",
    loading: "{{ t('messages.loading') }}",
    success: {
        add: "{{ t('messages.success.add') }}",
        update: "{{ t('messages.success.update') }}",
        delete: "{{ t('messages.success.delete') }}",
        status: "{{ t('messages.success.status') }}"
    },
    error: {
        add: "{{ t('messages.error.add') }}",
        update: "{{ t('messages.error.update') }}",
        delete: "{{ t('messages.error.delete') }}",
        status: "{{ t('messages.error.status') }}",
        fetch: "{{ t('messages.error.fetch') }}"
    }
};

// فتح نافذة إضافة لغة جديدة
function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
}

// فتح نافذة تعديل اللغة
function openEditModal(language) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    
    form.action = `/admin/languages/${language.id}`;
    
    document.getElementById('edit_code').value = language.code;
    document.getElementById('edit_name').value = language.name;
    document.getElementById('edit_native_name').value = language.native_name;
    document.getElementById('edit_is_active').value = language.is_active ? '1' : '0';
    document.getElementById('edit_is_default').value = language.is_default ? '1' : '0';
    
    const flagContainer = document.getElementById('current_flag_container');
    if (language.flag) {
        flagContainer.innerHTML = `
            <div class="mt-2">
                <p class="text-sm text-gray-500 mb-1">العلم الحالي</p>
                <img src="/storage/${language.flag}" alt="${language.name}" class="h-16 w-16 rounded-lg object-cover">
            </div>
        `;
    } else {
        flagContainer.innerHTML = `
            <p class="text-sm text-gray-500">لا يوجد علم</p>
        `;
    }
    
    modal.classList.remove('hidden');
}

// إغلاق النوافذ المنبثقة
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'editModal') {
        document.getElementById('editForm').reset();
        document.getElementById('current_flag_container').innerHTML = '';
    }
}

// تغيير حالة اللغة
function toggleStatus(languageId) {
    if (confirm('هل أنت متأكد من تغيير حالة اللغة؟')) {
        showLoading();
        
        fetch(`/admin/languages/${languageId}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                showSuccess('تم تحديث حالة اللغة بنجاح');
                setTimeout(() => location.reload(), 1000);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideLoading();
            showError('حدث خطأ أثناء تحديث حالة اللغة');
        });
    }
}

// حذف لغة
function deleteLanguage(languageId) {
    if (confirm('هل أنت متأكد من حذف هذه اللغة؟')) {
        showLoading();
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/languages/${languageId}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}

// تعديل لغة
function editLanguage(languageId) {
    showLoading();
    
    fetch(`/admin/languages/${languageId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            openEditModal(data.language);
        } else {
            showError(data.message);
        }
    })
    .catch(error => {
        hideLoading();
        showError('حدث خطأ أثناء جلب بيانات اللغة');
    });
}

// عرض مؤشر التحميل
function showLoading() {
    const loading = document.createElement('div');
    loading.id = 'loading-indicator';
    loading.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    loading.innerHTML = `
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="animate-spin rounded-full h-8 w-8 border-4 border-purple-500 border-t-transparent"></div>
            <p class="mt-2 text-gray-700">جاري التحميل...</p>
        </div>
    `;
    document.body.appendChild(loading);
}

// إخفاء مؤشر التحميل
function hideLoading() {
    const loading = document.getElementById('loading-indicator');
    if (loading) {
        loading.remove();
    }
}

// عرض رسالة نجاح
function showSuccess(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed bottom-4 left-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    successDiv.textContent = message;
    document.body.appendChild(successDiv);
    
    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// عرض رسالة خطأ
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed bottom-4 left-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

// البحث المباشر
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchText = e.target.value.toLowerCase();
    
    // البحث في جدول الشاشات الكبيرة
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        const code = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const name = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        const nativeName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        
        if (code.includes(searchText) || name.includes(searchText) || nativeName.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // البحث في بطاقات الشاشات الصغيرة
    const cards = document.querySelectorAll('.md\\:hidden > div');
    cards.forEach(card => {
        const name = card.querySelector('.font-medium').textContent.toLowerCase();
        const nativeName = card.querySelector('.text-gray-500').textContent.toLowerCase();
        const code = card.querySelector('.bg-gray-100').textContent.toLowerCase();
        
        if (name.includes(searchText) || nativeName.includes(searchText) || code.includes(searchText)) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
});

// تطبيق الفلاتر
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;

    showLoading();

    const params = new URLSearchParams({
        status: status,
        search: search
    });

    window.location.href = `${window.location.pathname}?${params.toString()}`;
}

// تحديث قيم الفلاتر عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    document.getElementById('statusFilter').value = urlParams.get('status') || '';
    document.getElementById('searchInput').value = urlParams.get('search') || '';
    
    // إخفاء رسائل النجاح والخطأ تلقائياً
    setTimeout(() => {
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        if (successMessage) successMessage.style.display = 'none';
        if (errorMessage) errorMessage.style.display = 'none';
    }, 3000);
});

// معالجة نموذج الإضافة
document.getElementById('createForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        showLoading();
        
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        hideLoading();
        
        if (result.success) {
            showSuccess('تم إضافة اللغة بنجاح');
            closeModal('createModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(result.message || 'حدث خطأ أثناء إضافة اللغة');
        }
    } catch (error) {
        hideLoading();
        showError(error.message);
    }
});

// معالجة نموذج التعديل
document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        showLoading();
        
        const formData = new FormData(this);
        const response = await fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        hideLoading();
        
        if (result.success) {
            showSuccess('تم تحديث اللغة بنجاح');
            closeModal('editModal');
            setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(result.message || 'حدث خطأ أثناء تحديث اللغة');
        }
    } catch (error) {
        hideLoading();
        showError(error.message);
    }
});
</script>
@endpush
@endsection 