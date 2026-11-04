@extends('layouts.app')

@section('title', 'إدارة أوامر Artisan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <!-- العنوان الرئيسي -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-terminal text-blue-600 mr-3"></i>
                إدارة أوامر Artisan
            </h1>
            <p class="text-gray-600 mt-2">تنفيذ أوامر Laravel Artisan من خلال المتصفح</p>
        </div>

        <!-- معلومات النظام -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                    <h2 class="text-xl font-semibold text-gray-800">معلومات النظام</h2>
                </div>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="loadSystemInfo()">
                    <i class="fas fa-sync-alt mr-2"></i>
                    تحديث
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="systemInfo">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">إصدار PHP</div>
                    <div class="font-semibold text-gray-900" id="php-version">جاري التحميل...</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">إصدار Laravel</div>
                    <div class="font-semibold text-gray-900" id="laravel-version">جاري التحميل...</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">بيئة التطبيق</div>
                    <div class="font-semibold text-gray-900" id="app-environment">جاري التحميل...</div>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">وضع التصحيح</div>
                    <div class="font-semibold text-gray-900" id="app-debug">جاري التحميل...</div>
                </div>
            </div>
            
            <!-- معلومات إضافية للنظام -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-cogs text-purple-500 text-xl mr-3"></i>
                    معلومات تقنية إضافية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="technicalInfo">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">مسار PHP</div>
                        <div class="font-semibold text-gray-900" id="php-executable">جاري التحميل...</div>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">ملف Artisan</div>
                        <div class="font-semibold text-gray-900" id="artisan-exists">جاري التحميل...</div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">صلاحيات التخزين</div>
                        <div class="font-semibold text-gray-900" id="storage-writable">جاري التحميل...</div>
                    </div>
                </div>
            </div>
            
            <!-- معلومات السيرفر -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-server text-indigo-500 text-xl mr-3"></i>
                    معلومات السيرفر
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="serverInfo">
                    <div class="bg-indigo-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">اسم السيرفر</div>
                        <div class="font-semibold text-gray-900" id="server-name">جاري التحميل...</div>
                    </div>
                    <div class="bg-pink-50 p-4 rounded-lg">
                        <div class="text-sm text-gray-600 mb-1">نوع السيرفر</div>
                        <div class="font-semibold text-gray-900" id="server-type">جاري التحميل...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الأوامر السريعة -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center mb-6">
                <i class="fas fa-bolt text-yellow-500 text-xl mr-3"></i>
                <h2 class="text-xl font-semibold text-gray-800">أوامر سريعة</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <button class="quick-command bg-yellow-500 hover:bg-yellow-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="optimize:clear">
                    <i class="fas fa-broom text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">مسح الكاش</span>
                </button>
                <button class="quick-command bg-blue-500 hover:bg-blue-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="config:clear">
                    <i class="fas fa-cog text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">مسح الإعدادات</span>
                </button>
                <button class="quick-command bg-green-500 hover:bg-green-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="view:clear">
                    <i class="fas fa-eye text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">مسح العرض</span>
                </button>
                <button class="quick-command bg-indigo-500 hover:bg-indigo-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="route:clear">
                    <i class="fas fa-route text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">مسح المسارات</span>
                </button>
                <button class="quick-command bg-gray-500 hover:bg-gray-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="cache:clear">
                    <i class="fas fa-trash text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">مسح الكاش</span>
                </button>
                <button class="quick-command bg-purple-500 hover:bg-purple-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="storage:link">
                    <i class="fas fa-link text-2xl mb-2 block"></i>
                    <span class="text-sm font-medium">رابط التخزين</span>
                </button>
            </div>
            
            <!-- أوامر إنشاء البيانات الأساسية -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-database text-green-500 text-xl mr-3"></i>
                    إنشاء البيانات الأساسية
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <button class="quick-command bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg transition-colors duration-200" data-command="db:seed:roles">
                        <i class="fas fa-user-tag text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">إنشاء الأدوار</span>
                        <span class="text-xs opacity-75 block">RoleSeeder</span>
                    </button>
                    <button class="quick-command bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg transition-colors duration-200" data-command="db:seed:permissions">
                        <i class="fas fa-shield-alt text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">إنشاء الصلاحيات</span>
                        <span class="text-xs opacity-75 block">PermissionSeeder</span>
                    </button>
                    <button class="quick-command bg-indigo-600 hover:bg-indigo-700 text-white p-4 rounded-lg transition-colors duration-200" data-command="db:seed:admin">
                        <i class="fas fa-user-shield text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">إنشاء المدير</span>
                        <span class="text-xs opacity-75 block">AdminSeeder</span>
                    </button>
                    <button class="quick-command bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-lg transition-colors duration-200" data-command="db:seed">
                        <i class="fas fa-rocket text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">إنشاء الكل</span>
                        <span class="text-xs opacity-75 block">جميع البذور</span>
                    </button>
                </div>
            </div>
            
            <!-- أوامر متقدمة -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                    أوامر متقدمة (احترس!)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <button class="quick-command bg-red-500 hover:bg-red-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="migrate:fresh">
                        <i class="fas fa-database text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">إعادة إنشاء قاعدة البيانات</span>
                        <span class="text-xs opacity-75 block">سيحذف جميع البيانات!</span>
                    </button>
                    <button class="quick-command bg-orange-500 hover:bg-orange-600 text-white p-4 rounded-lg transition-colors duration-200" data-command="delete-storage-folder">
                        <i class="fas fa-folder-minus text-2xl mb-2 block"></i>
                        <span class="text-sm font-medium">حذف مجلد التخزين</span>
                        <span class="text-xs opacity-75 block">سيحذف جميع الصور!</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- اختبار الأوامر -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="fas fa-vial text-orange-500 text-xl mr-3"></i>
                    <h2 class="text-xl font-semibold text-gray-800">اختبار الأوامر</h2>
                </div>
                <button type="button" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="testCommands()">
                    <i class="fas fa-play mr-2"></i>
                    اختبار الأوامر
                </button>
            </div>
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                    <p class="text-orange-800 text-sm">
                        هذا القسم لاختبار الأوامر الأساسية والتأكد من عمل النظام بشكل صحيح
                    </p>
                </div>
            </div>
        </div>

        <!-- تشخيص النظام -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="fas fa-stethoscope text-red-500 text-xl mr-3"></i>
                    <h2 class="text-xl font-semibold text-gray-800">تشخيص النظام</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="pingSystem()">
                        <i class="fas fa-heartbeat mr-2"></i>
                        اختبار الاتصال
                    </button>
                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" onclick="diagnoseSystem()">
                        <i class="fas fa-search mr-2"></i>
                        تشخيص شامل
                    </button>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-red-500 mr-2"></i>
                    <p class="text-red-800 text-sm">
                        هذا القسم لتشخيص شامل للنظام واكتشاف أي مشاكل محتملة
                    </p>
                </div>
            </div>
        </div>

        <!-- تنفيذ أوامر مخصصة -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-center mb-6">
                <i class="fas fa-code text-green-500 text-xl mr-3"></i>
                <h2 class="text-xl font-semibold text-gray-800">تنفيذ أوامر مخصصة</h2>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <select class="flex-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="customCommand">
                    <option value="">-- اختر الأمر --</option>
                    <option value="optimize:clear">optimize:clear - مسح جميع الكاش</option>
                    <option value="config:clear">config:clear - مسح كاش الإعدادات</option>
                    <option value="cache:clear">cache:clear - مسح كاش التطبيق</option>
                    <option value="view:clear">view:clear - مسح كاش العرض</option>
                    <option value="route:clear">route:clear - مسح كاش المسارات</option>
                    <option value="migrate">migrate - تشغيل الهجرات</option>
                    <option value="migrate:status">migrate:status - حالة الهجرات</option>
                    <option value="migrate:fresh">migrate:fresh - إعادة إنشاء قاعدة البيانات (خطر!)</option>
                    <option value="db:seed">db:seed - تشغيل جميع البذور</option>
                    <option value="db:seed:roles">db:seed:roles - إنشاء الأدوار الأساسية</option>
                    <option value="db:seed:permissions">db:seed:permissions - إنشاء الصلاحيات</option>
                    <option value="db:seed:admin">db:seed:admin - إنشاء المدير الأساسي</option>
                    <option value="storage:link">storage:link - إنشاء رابط التخزين</option>
                    <option value="delete-storage-folder">delete-storage-folder - حذف مجلد التخزين (خطر!)</option>
                    <option value="key:generate">key:generate - إنشاء مفتاح جديد</option>
                    <option value="config:cache">config:cache - كاش الإعدادات</option>
                    <option value="route:cache">route:cache - كاش المسارات</option>
                    <option value="view:cache">view:cache - كاش العرض</option>
                </select>
                <button type="button" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg transition-colors duration-200 flex items-center" id="executeCustomCommand">
                    <i class="fas fa-play mr-2"></i>
                    تنفيذ الأمر
                </button>
            </div>
        </div>

        <!-- نتائج التنفيذ -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <i class="fas fa-list text-purple-500 text-xl mr-3"></i>
                    <h2 class="text-xl font-semibold text-gray-800">نتائج التنفيذ</h2>
                </div>
                <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 flex items-center" id="clearResults">
                    <i class="fas fa-trash mr-2"></i>
                    مسح النتائج
                </button>
            </div>
            <div id="commandResults">
                <div class="text-center text-gray-500 py-12">
                    <i class="fas fa-terminal text-6xl mb-4 block"></i>
                    <p class="text-lg">لم يتم تنفيذ أي أوامر بعد</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-8 text-center">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto mb-4"></div>
        <p class="text-gray-700">جاري تنفيذ الأمر...</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Artisan page loaded, jQuery version:', $.fn.jquery);
    
    // تحميل معلومات النظام
    loadSystemInfo();

    // الأوامر السريعة
    $('.quick-command').click(function() {
        const command = $(this).data('command');
        console.log('Quick command clicked:', command);
        executeCommand(command);
    });

    // تنفيذ أمر مخصص
    $('#executeCustomCommand').click(function() {
        const command = $('#customCommand').val();
        if (command) {
            console.log('Custom command selected:', command);
            executeCommand(command);
        } else {
            showAlert('يرجى اختيار أمر أولاً', 'warning');
        }
    });

    // مسح النتائج
    $('#clearResults').click(function() {
        $('#commandResults').html(`
            <div class="text-center text-gray-500 py-12">
                <i class="fas fa-terminal text-6xl mb-4 block"></i>
                <p class="text-lg">لم يتم تنفيذ أي أوامر بعد</p>
            </div>
        `);
    });
});

// دالة اختبار الأوامر
function testCommands() {
    console.log('Testing commands...');
    
    // إظهار modal التحميل
    $('#loadingModal').removeClass('hidden');
    
    $.ajax({
        url: '{{ route("artisan.test") }}',
        method: 'GET',
        success: function(response) {
            console.log('Test results:', response);
            $('#loadingModal').addClass('hidden');
            
            if (response.success) {
                let testResultsHtml = '<div class="space-y-4">';
                
                Object.keys(response.results).forEach(command => {
                    const result = response.results[command];
                    const statusClass = result.success ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                    const statusIcon = result.success ? 'check-circle' : 'exclamation-triangle';
                    const statusText = result.success ? 'نجح' : 'فشل';
                    
                    testResultsHtml += `
                        <div class="border-l-4 p-4 ${statusClass}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h6 class="font-semibold mb-2">
                                        <i class="fas fa-${statusIcon} mr-2"></i>
                                        ${result.description} (${command})
                                    </h6>
                                    <p class="text-sm opacity-75">الحالة: ${statusText}</p>
                                    ${result.output ? `<pre class="bg-gray-100 p-2 rounded text-xs mt-2 overflow-x-auto"><code>${result.output}</code></pre>` : ''}
                                    ${result.error ? `<p class="text-red-600 text-sm mt-1">${result.error}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                testResultsHtml += '</div>';
                
                // إضافة نتائج الاختبار إلى قسم النتائج
                if ($('#commandResults .text-center.text-gray-500').length > 0) {
                    $('#commandResults').empty();
                }
                
                $('#commandResults').prepend(`
                    <div class="border-l-4 p-4 bg-blue-100 border-blue-400 text-blue-700 mb-4">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-vial mr-2"></i>
                            نتائج اختبار الأوامر
                        </h6>
                        <p class="text-sm">تم اختبار الأوامر الأساسية للتأكد من عمل النظام</p>
                    </div>
                    ${testResultsHtml}
                `);
                
                showAlert('تم اختبار الأوامر بنجاح', 'success');
            } else {
                showAlert('فشل في اختبار الأوامر: ' + (response.error || 'خطأ غير معروف'), 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error testing commands:', error);
            $('#loadingModal').addClass('hidden');
            
            let message = 'حدث خطأ أثناء اختبار الأوامر';
            if (xhr.responseJSON) {
                message = xhr.responseJSON.error || message;
            }
            
            showAlert(message, 'danger');
        }
    });
}

// دالة اختبار الاتصال
function pingSystem() {
    console.log('Pinging system...');
    
    $.ajax({
        url: '{{ route("artisan.ping") }}',
        method: 'GET',
        success: function(response) {
            console.log('Ping response:', response);
            
            if (response.success) {
                showAlert('✅ ' + response.message, 'success');
                
                // إضافة نتيجة الاتصال إلى قسم النتائج
                if ($('#commandResults .text-center.text-gray-500').length > 0) {
                    $('#commandResults').empty();
                }
                
                $('#commandResults').prepend(`
                    <div class="border-l-4 p-4 bg-green-100 border-green-400 text-green-700 mb-4">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-heartbeat mr-2"></i>
                            اختبار الاتصال
                        </h6>
                        <p class="text-sm">${response.message}</p>
                        <p class="text-xs opacity-75 mt-1">السيرفر: ${response.server} | البيئة: ${response.environment} | الوقت: ${response.timestamp}</p>
                    </div>
                `);
            } else {
                showAlert('❌ فشل في اختبار الاتصال', 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error pinging system:', error);
            showAlert('❌ خطأ في اختبار الاتصال: ' + error, 'danger');
        }
    });
}

// دالة التشخيص الشامل
function diagnoseSystem() {
    console.log('Diagnosing system...');
    
    // إظهار modal التحميل
    $('#loadingModal').removeClass('hidden');
    
    $.ajax({
        url: '{{ route("artisan.diagnose") }}',
        method: 'GET',
        success: function(response) {
            console.log('Diagnosis results:', response);
            $('#loadingModal').addClass('hidden');
            
            if (response.success) {
                const diagnosis = response.diagnosis;
                let diagnosisHtml = '<div class="space-y-6">';
                
                // معلومات النظام
                diagnosisHtml += `
                    <div class="border-l-4 p-4 bg-blue-100 border-blue-400 text-blue-700">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-server mr-2"></i>
                            معلومات النظام
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <p><strong>PHP:</strong> ${diagnosis.system_info.php_version}</p>
                            <p><strong>Laravel:</strong> ${diagnosis.system_info.laravel_version}</p>
                            <p><strong>البيئة:</strong> ${diagnosis.system_info.app_environment}</p>
                            <p><strong>السيرفر:</strong> ${diagnosis.system_info.server_name}</p>
                        </div>
                    </div>
                `;
                
                // صلاحيات الملفات
                diagnosisHtml += `
                    <div class="border-l-4 p-4 ${diagnosis.file_permissions.artisan_exists && diagnosis.file_permissions.storage_writable ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'}">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-file-alt mr-2"></i>
                            صلاحيات الملفات
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <p><strong>ملف Artisan:</strong> ${diagnosis.file_permissions.artisan_exists ? '✅ موجود' : '❌ غير موجود'}</p>
                            <p><strong>قابل للقراءة:</strong> ${diagnosis.file_permissions.artisan_readable ? '✅ نعم' : '❌ لا'}</p>
                            <p><strong>التخزين قابل للكتابة:</strong> ${diagnosis.file_permissions.storage_writable ? '✅ نعم' : '❌ لا'}</p>
                            <p><strong>Bootstrap قابل للكتابة:</strong> ${diagnosis.file_permissions.bootstrap_writable ? '✅ نعم' : '❌ لا'}</p>
                        </div>
                    </div>
                `;
                
                // الدوال المتاحة
                diagnosisHtml += `
                    <div class="border-l-4 p-4 ${diagnosis.php_functions.shell_exec_available || diagnosis.php_functions.exec_available ? 'bg-green-100 border-green-400 text-green-700' : 'bg-yellow-100 border-yellow-400 text-yellow-700'}">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-code mr-2"></i>
                            الدوال المتاحة
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <p><strong>shell_exec:</strong> ${diagnosis.php_functions.shell_exec_available ? '✅ متاح' : '❌ غير متاح'}</p>
                            <p><strong>exec:</strong> ${diagnosis.php_functions.exec_available ? '✅ متاح' : '❌ غير متاح'}</p>
                            <p><strong>system:</strong> ${diagnosis.php_functions.system_available ? '✅ متاح' : '❌ غير متاح'}</p>
                            <p><strong>passthru:</strong> ${diagnosis.php_functions.passthru_available ? '✅ متاح' : '❌ غير متاح'}</p>
                        </div>
                    </div>
                `;
                
                // الكلاسات المتاحة
                diagnosisHtml += `
                    <div class="border-l-4 p-4 ${diagnosis.classes_available.process_class && diagnosis.classes_available.artisan_facade ? 'bg-green-100 border-green-400 text-green-700' : 'bg-yellow-100 border-yellow-400 text-yellow-700'}">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-cubes mr-2"></i>
                            الكلاسات المتاحة
                        </h6>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <p><strong>Process Class:</strong> ${diagnosis.classes_available.process_class ? '✅ متاح' : '❌ غير متاح'}</p>
                            <p><strong>Artisan Facade:</strong> ${diagnosis.classes_available.artisan_facade ? '✅ متاح' : '❌ غير متاح'}</p>
                        </div>
                    </div>
                `;
                
                diagnosisHtml += '</div>';
                
                // إضافة نتائج التشخيص إلى قسم النتائج
                if ($('#commandResults .text-center.text-gray-500').length > 0) {
                    $('#commandResults').empty();
                }
                
                $('#commandResults').prepend(`
                    <div class="border-l-4 p-4 bg-purple-100 border-purple-400 text-purple-700 mb-4">
                        <h6 class="font-semibold mb-2">
                            <i class="fas fa-stethoscope mr-2"></i>
                            نتائج التشخيص الشامل
                        </h6>
                        <p class="text-sm">تم تشخيص النظام واكتشاف جميع المشاكل المحتملة</p>
                        <p class="text-xs opacity-75 mt-1">الوقت: ${response.timestamp}</p>
                    </div>
                    ${diagnosisHtml}
                `);
                
                showAlert('تم تشخيص النظام بنجاح', 'success');
            } else {
                showAlert('فشل في تشخيص النظام: ' + (response.error || 'خطأ غير معروف'), 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error diagnosing system:', error);
            $('#loadingModal').addClass('hidden');
            
            let message = 'حدث خطأ أثناء تشخيص النظام';
            if (xhr.responseJSON) {
                message = xhr.responseJSON.error || message;
            }
            
            showAlert(message, 'danger');
        }
    });
}

function loadSystemInfo() {
    console.log('Loading system info...');
    $.ajax({
        url: '{{ route("artisan.system-info") }}',
        method: 'GET',
        success: function(response) {
            console.log('System info loaded:', response);
            
            // معلومات النظام الأساسية
            $('#php-version').text(response.php_version || 'غير متاح');
            $('#laravel-version').text(response.laravel_version || 'غير متاح');
            $('#app-environment').text(response.app_environment || 'غير متاح');
            $('#app-debug').text(response.app_debug ? 'مفعل' : 'معطل');
            
            // المعلومات التقنية الإضافية
            $('#php-executable').text(response.php_executable || 'غير متاح');
            $('#artisan-exists').text(response.artisan_exists ? 'موجود' : 'غير موجود');
            $('#storage-writable').text(response.storage_writable ? 'قابل للكتابة' : 'غير قابل للكتابة');
            
            // إضافة ألوان للمعلومات
            if (response.artisan_exists) {
                $('#artisan-exists').removeClass('text-red-600').addClass('text-green-600');
            } else {
                $('#artisan-exists').removeClass('text-green-600').addClass('text-red-600');
            }
            
            if (response.storage_writable) {
                $('#storage-writable').removeClass('text-red-600').addClass('text-green-600');
            } else {
                $('#storage-writable').removeClass('text-green-600').addClass('text-red-600');
            }
            
            // إضافة معلومات إضافية إذا كانت متاحة
            if (response.shell_exec_available !== undefined) {
                const shellStatus = response.shell_exec_available ? 'متاح' : 'غير متاح';
                const shellColor = response.shell_exec_available ? 'text-green-600' : 'text-red-600';
                
                if (!$('#shell-exec-status').length) {
                    $('#technicalInfo').append(`
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600 mb-1">shell_exec</div>
                            <div class="font-semibold text-gray-900 ${shellColor}" id="shell-exec-status">${shellStatus}</div>
                        </div>
                    `);
                } else {
                    $('#shell-exec-status').text(shellStatus).removeClass('text-red-600 text-green-600').addClass(shellColor);
                }
            }
            
            // تحديث معلومات السيرفر
            if (response.app_url) {
                const serverName = response.app_url.replace(/^https?:\/\//, '').replace(/\/.*$/, '');
                $('#server-name').text(serverName || 'localhost');
                
                // تحديد نوع السيرفر
                let serverType = 'غير محدد';
                if (serverName === 'localhost' || serverName === '127.0.0.1') {
                    serverType = 'محلي (Local)';
                } else if (serverName.includes('test') || serverName.includes('dev')) {
                    serverType = 'اختبار (Test)';
                } else {
                    serverType = 'إنتاج (Production)';
                }
                $('#server-type').text(serverType);
            }
            
        },
        error: function(xhr, status, error) {
            console.error('Error loading system info:', error);
            $('#systemInfo div').text('غير متاح');
            $('#technicalInfo div').text('غير متاح');
        }
    });
}

function executeCommand(command) {
    console.log('Executing command:', command);
    
    // تأكيد للأوامر الخطرة
    const dangerousCommands = ['migrate:fresh', 'delete-storage-folder'];
    if (dangerousCommands.includes(command)) {
        const commandNames = {
            'migrate:fresh': 'إعادة إنشاء قاعدة البيانات',
            'delete-storage-folder': 'حذف مجلد التخزين'
        };
        
        const confirmMessage = `⚠️ تحذير! هذا الأمر خطير جداً!\n\n` +
            `الأمر: ${commandNames[command]}\n` +
            `سيؤدي إلى:\n` +
            (command === 'migrate:fresh' ? 
                '• حذف جميع البيانات من قاعدة البيانات\n• إعادة إنشاء جميع الجداول\n• فقدان جميع المعلومات!' :
                '• حذف جميع الصور والملفات\n• فقدان جميع الملفات المرفوعة!'
            ) + `\n\nهل أنت متأكد من أنك تريد المتابعة؟`;
        
        if (!confirm(confirmMessage)) {
            console.log('Command cancelled by user');
            return;
        }
    }
    
    // إظهار modal التحميل
    $('#loadingModal').removeClass('hidden');

    $.ajax({
        url: '{{ route("artisan.execute") }}',
        method: 'POST',
        data: {
            command: command,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            console.log('Command executed successfully:', response);
            $('#loadingModal').addClass('hidden');
            
            if (response.success) {
                addCommandResult(command, response.message, 'success', response.output);
                showAlert(response.message, 'success');
            } else {
                addCommandResult(command, response.message, 'danger', response.output);
                showAlert(response.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error executing command:', error);
            $('#loadingModal').addClass('hidden');
            
            let message = 'حدث خطأ أثناء تنفيذ الأمر';
            let output = '';
            
            if (xhr.responseJSON) {
                message = xhr.responseJSON.message || message;
                output = xhr.responseJSON.output || '';
            }
            
            addCommandResult(command, message, 'danger', output);
            showAlert(message, 'danger');
        }
    });
}

function addCommandResult(command, message, type, output) {
    const timestamp = new Date().toLocaleString('ar-SA');
    const icon = type === 'success' ? 'check-circle' : 'exclamation-triangle';
    const alertClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    const resultHtml = `
        <div class="border-l-4 p-4 mb-4 ${alertClass}">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h6 class="font-semibold mb-2">
                        <i class="fas fa-${icon} mr-2"></i>
                        ${message}
                    </h6>
                    <p class="mb-1"><strong>الأمر:</strong> <code class="bg-gray-200 px-2 py-1 rounded">${command}</code></p>
                    <p class="mb-1"><strong>الوقت:</strong> ${timestamp}</p>
                </div>
                <button type="button" class="text-gray-500 hover:text-gray-700 ml-4" onclick="$(this).closest('.border-l-4').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            ${output ? `<hr class="my-3"><pre class="bg-gray-100 p-3 rounded text-sm overflow-x-auto"><code>${output}</code></pre>` : ''}
        </div>
    `;
    
    // إزالة الرسالة الافتراضية إذا كانت موجودة
    if ($('#commandResults .text-center.text-gray-500').length > 0) {
        $('#commandResults').empty();
    }
    
    $('#commandResults').prepend(resultHtml);
}

function showAlert(message, type) {
    let alertClass, icon;
    
    switch(type) {
        case 'success':
            alertClass = 'bg-green-100 border-green-400 text-green-700';
            icon = 'check-circle';
            break;
        case 'danger':
            alertClass = 'bg-red-100 border-red-400 text-red-700';
            icon = 'exclamation-triangle';
            break;
        case 'warning':
            alertClass = 'bg-yellow-100 border-yellow-400 text-yellow-700';
            icon = 'exclamation-circle';
            break;
        default:
            alertClass = 'bg-blue-100 border-blue-400 text-blue-700';
            icon = 'info-circle';
    }
    
    const alertHtml = `
        <div class="fixed top-20 right-4 z-50 border-l-4 p-4 ${alertClass} rounded shadow-lg max-w-md">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="mr-4 text-gray-500 hover:text-gray-700" onclick="$(this).closest('.fixed').remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    // إزالة التنبيهات السابقة
    $('.fixed.top-20.right-4').remove();
    
    // إضافة التنبيه
    $('body').append(alertHtml);
    
    // إزالة التنبيه تلقائياً بعد 5 ثواني
    setTimeout(function() {
        $('.fixed.top-20.right-4').fadeOut();
    }, 5000);
}
</script>
@endpush
