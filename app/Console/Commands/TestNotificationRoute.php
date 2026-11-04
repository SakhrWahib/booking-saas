<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Console\Command;

class TestNotificationRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notification-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'اختبار مسار الإشعارات';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('اختبار مسار الإشعارات...');
        
        $controller = new NotificationController();
        
        // اختبار getAllForDropdown
        $this->info('اختبار getAllForDropdown...');
        $response = $controller->getAllForDropdown();
        $data = json_decode($response->getContent(), true);
        
        $this->info('عدد الإشعارات: ' . count($data['notifications']));
        $this->info('عدد الإشعارات غير المقروءة: ' . $data['count']);
        
        foreach ($data['notifications'] as $notification) {
            $this->line("ID: {$notification['id']}, Title: {$notification['title']}, Read: " . ($notification['is_read'] ? 'Yes' : 'No'));
        }
        
        $this->info('✅ تم اختبار المسار بنجاح!');
    }
}
