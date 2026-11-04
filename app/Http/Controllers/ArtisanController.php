<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ArtisanController extends Controller
{
    public function index()
    {
        return view('admin.artisan.index');
    }

    public function executeCommand(Request $request)
    {
        $command = $request->input('command');
        $allowedCommands = [
            'optimize:clear',
            'config:clear',
            'cache:clear',
            'view:clear',
            'route:clear',
            'migrate',
            'migrate:status',
            'migrate:fresh',
            'db:seed',
            'db:seed:roles',
            'db:seed:permissions',
            'db:seed:admin',
            'storage:link',
            'key:generate',
            'config:cache',
            'route:cache',
            'view:cache',
            'delete-storage-folder'
        ];

        if (!in_array($command, $allowedCommands)) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الأمر غير مسموح به'
            ], 400);
        }

        try {
            // تسجيل الأمر في السجلات
            Log::info("Artisan command executed: {$command}");

            // معالجة خاصة لحذف مجلد التخزين
            if ($command === 'delete-storage-folder') {
                return $this->deleteStorageFolder();
            }

            // معالجة خاصة لـ db:seed
            if (str_starts_with($command, 'db:seed:')) {
                return $this->executeDbSeed($command);
            }

            // محاولة تنفيذ الأمر باستخدام Artisan facade أولاً
            try {
                $exitCode = Artisan::call($command);
                $output = Artisan::output();

                if ($exitCode === 0) {
                    return response()->json([
                        'success' => true,
                        'message' => "تم تنفيذ الأمر {$command} بنجاح",
                        'output' => $output,
                        'command' => $command
                    ]);
                } else {
                    // إذا فشل Artisan facade، جرب الطريقة البديلة
                    return $this->executeCommandAlternative($command);
                }
            } catch (\Exception $e) {
                Log::warning("Artisan facade failed for command: {$command}, trying alternative method", [
                    'error' => $e->getMessage()
                ]);
                
                // جرب الطريقة البديلة
                return $this->executeCommandAlternative($command);
            }

        } catch (\Exception $e) {
            Log::error("Error executing artisan command: {$command}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => "خطأ في تنفيذ الأمر: " . $e->getMessage(),
                'command' => $command
            ], 500);
        }
    }

    /**
     * طريقة بديلة لتنفيذ الأوامر باستخدام Process
     */
    private function executeCommandAlternative($command)
    {
        try {
            // الحصول على مسار PHP
            $phpPath = $this->getPhpPath();
            $artisanPath = base_path('artisan');
            
            if (!file_exists($artisanPath)) {
                throw new \Exception('ملف artisan غير موجود');
            }

            // إنشاء Process جديد
            $process = new Process([$phpPath, $artisanPath, $command]);
            $process->setWorkingDirectory(base_path());
            $process->setTimeout(300); // 5 دقائق timeout
            
            // تنفيذ العملية
            $process->run();

            if ($process->isSuccessful()) {
                $output = $process->getOutput();
                
                return response()->json([
                    'success' => true,
                    'message' => "تم تنفيذ الأمر {$command} بنجاح (طريقة بديلة)",
                    'output' => $output,
                    'command' => $command,
                    'method' => 'process'
                ]);
            } else {
                $errorOutput = $process->getErrorOutput();
                throw new \Exception("فشل في تنفيذ الأمر: " . $errorOutput);
            }
            
        } catch (\Exception $e) {
            Log::error("Alternative method failed for command: {$command}", [
                'error' => $e->getMessage()
            ]);
            
            // محاولة أخيرة باستخدام shell_exec
            return $this->executeCommandShell($command);
        }
    }

    /**
     * طريقة ثالثة باستخدام shell_exec
     */
    private function executeCommandShell($command)
    {
        try {
            if (!function_exists('shell_exec')) {
                throw new \Exception('shell_exec غير متاح على هذا السيرفر');
            }

            $phpPath = $this->getPhpPath();
            $artisanPath = base_path('artisan');
            
            $fullCommand = "cd " . base_path() . " && {$phpPath} {$artisanPath} {$command} 2>&1";
            
            $output = shell_exec($fullCommand);
            
            if ($output === null) {
                throw new \Exception('فشل في تنفيذ الأمر عبر shell_exec');
            }

            return response()->json([
                'success' => true,
                'message' => "تم تنفيذ الأمر {$command} بنجاح (طريقة shell)",
                'output' => $output,
                'command' => $command,
                'method' => 'shell'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Shell method failed for command: {$command}", [
                'error' => $e->getMessage()
            ]);
            
            throw new \Exception("جميع الطرق فشلت في تنفيذ الأمر: " . $e->getMessage());
        }
    }

    /**
     * الحصول على مسار PHP
     */
    private function getPhpPath()
    {
        // محاولة الحصول على مسار PHP من عدة مصادر
        $possiblePaths = [
            'php',
            '/usr/bin/php',
            '/usr/local/bin/php',
            '/opt/homebrew/bin/php',
            'C:\php\php.exe',
            'C:\xampp\php\php.exe',
            'C:\wamp\bin\php\php8.2.0\php.exe'
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isPhpExecutable($path)) {
                return $path;
            }
        }

        // إذا لم نجد، استخدم 'php' كافتراضي
        return 'php';
    }

    /**
     * التحقق من أن PHP قابل للتنفيذ
     */
    private function isPhpExecutable($path)
    {
        try {
            $process = new Process([$path, '--version']);
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * حذف مجلد التخزين
     */
    private function deleteStorageFolder()
    {
        try {
            $storagePath = public_path('storage');
            
            if (is_dir($storagePath)) {
                // حذف المجلد بالكامل
                File::deleteDirectory($storagePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف مجلد التخزين بنجاح',
                    'output' => 'تم حذف المجلد: ' . $storagePath,
                    'command' => 'delete-storage-folder'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'مجلد التخزين غير موجود',
                    'output' => 'المسار: ' . $storagePath,
                    'command' => 'delete-storage-folder'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في حذف مجلد التخزين: ' . $e->getMessage(),
                'command' => 'delete-storage-folder'
            ], 500);
        }
    }

    /**
     * تنفيذ أوامر db:seed
     */
    private function executeDbSeed($command)
    {
        try {
            $seederMap = [
                'db:seed:roles' => 'RoleSeeder',
                'db:seed:permissions' => 'PermissionSeeder',
                'db:seed:admin' => 'AdminSeeder'
            ];

            $seederClass = $seederMap[$command] ?? null;
            if (!$seederClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'فئة البذور غير معروفة',
                    'command' => $command
                ], 400);
            }

            // محاولة تنفيذ الأمر باستخدام Artisan facade أولاً
            try {
                $exitCode = Artisan::call('db:seed', ['--class' => $seederClass]);
                $output = Artisan::output();

                if ($exitCode === 0) {
                    return response()->json([
                        'success' => true,
                        'message' => "تم إنشاء البيانات باستخدام {$seederClass} بنجاح",
                        'output' => $output,
                        'command' => $command
                    ]);
                } else {
                    // إذا فشل، جرب الطريقة البديلة
                    return $this->executeDbSeedAlternative($seederClass, $command);
                }
            } catch (\Exception $e) {
                return $this->executeDbSeedAlternative($seederClass, $command);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في تنفيذ البذور: ' . $e->getMessage(),
                'command' => $command
            ], 500);
        }
    }

    /**
     * طريقة بديلة لتنفيذ البذور
     */
    private function executeDbSeedAlternative($seederClass, $command)
    {
        try {
            $phpPath = $this->getPhpPath();
            $artisanPath = base_path('artisan');
            
            $fullCommand = "cd " . base_path() . " && {$phpPath} {$artisanPath} db:seed --class={$seederClass} 2>&1";
            
            $output = shell_exec($fullCommand);
            
            if ($output === null) {
                throw new \Exception('فشل في تنفيذ البذور');
            }

            return response()->json([
                'success' => true,
                'message' => "تم إنشاء البيانات باستخدام {$seederClass} بنجاح (طريقة بديلة)",
                'output' => $output,
                'command' => $command,
                'method' => 'shell'
            ]);
            
        } catch (\Exception $e) {
            throw new \Exception('فشل في تنفيذ البذور: ' . $e->getMessage());
        }
    }

    public function getSystemInfo()
    {
        try {
            $info = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'app_environment' => app()->environment(),
                'app_debug' => config('app.debug'),
                'app_url' => config('app.url'),
                'database_connection' => config('database.default'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'php_executable' => $this->getPhpPath(),
                'artisan_exists' => file_exists(base_path('artisan')),
                'storage_writable' => is_writable(storage_path()),
                'bootstrap_writable' => is_writable(base_path('bootstrap')),
                'shell_exec_available' => function_exists('shell_exec'),
                'process_available' => class_exists('Symfony\Component\Process\Process'),
            ];

            return response()->json($info);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'خطأ في الحصول على معلومات النظام: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * اختبار تنفيذ الأوامر
     */
    public function testCommand()
    {
        try {
            $testCommands = [
                'config:clear' => 'مسح الإعدادات',
                'cache:clear' => 'مسح الكاش',
                'view:clear' => 'مسح العرض'
            ];

            $results = [];
            
            foreach ($testCommands as $command => $description) {
                try {
                    $exitCode = Artisan::call($command);
                    $output = Artisan::output();
                    
                    $results[$command] = [
                        'success' => $exitCode === 0,
                        'output' => $output,
                        'description' => $description
                    ];
                } catch (\Exception $e) {
                    $results[$command] = [
                        'success' => false,
                        'error' => $e->getMessage(),
                        'description' => $description
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تشخيص شامل للنظام
     */
    public function diagnoseSystem()
    {
        try {
            $diagnosis = [
                'system_info' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'app_environment' => app()->environment(),
                    'app_debug' => config('app.debug'),
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'غير محدد',
                    'server_name' => $_SERVER['SERVER_NAME'] ?? 'غير محدد',
                    'server_addr' => $_SERVER['SERVER_ADDR'] ?? 'غير محدد',
                    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'غير محدد',
                ],
                'file_permissions' => [
                    'artisan_exists' => file_exists(base_path('artisan')),
                    'artisan_readable' => is_readable(base_path('artisan')),
                    'storage_writable' => is_writable(storage_path()),
                    'bootstrap_writable' => is_writable(base_path('bootstrap')),
                    'public_writable' => is_writable(public_path()),
                ],
                'php_functions' => [
                    'shell_exec_available' => function_exists('shell_exec'),
                    'exec_available' => function_exists('exec'),
                    'system_available' => function_exists('system'),
                    'passthru_available' => function_exists('passthru'),
                ],
                'classes_available' => [
                    'process_class' => class_exists('Symfony\Component\Process\Process'),
                    'artisan_facade' => class_exists('Illuminate\Support\Facades\Artisan'),
                ],
                'directory_structure' => [
                    'base_path' => base_path(),
                    'storage_path' => storage_path(),
                    'bootstrap_path' => base_path('bootstrap'),
                    'public_path' => public_path(),
                ],
                'environment_variables' => [
                    'app_env' => env('APP_ENV'),
                    'app_debug' => env('APP_DEBUG'),
                    'app_url' => env('APP_URL'),
                    'database_connection' => env('DB_CONNECTION'),
                ]
            ];

            return response()->json([
                'success' => true,
                'diagnosis' => $diagnosis,
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    /**
     * اختبار بسيط للاتصال
     */
    public function ping()
    {
        return response()->json([
            'success' => true,
            'message' => 'النظام يعمل بشكل صحيح',
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'server' => $_SERVER['SERVER_NAME'] ?? 'غير محدد',
            'environment' => app()->environment()
        ]);
    }
}
