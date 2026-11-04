<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;

class CheckRoles extends Command
{
    protected $signature = 'roles:check';
    protected $description = 'التحقق من الأدوار الموجودة في قاعدة البيانات';

    public function handle()
    {
        $this->info('🔍 التحقق من الأدوار الموجودة...');
        
        $roles = Role::all();
        
        if ($roles->count() == 0) {
            $this->error('❌ لا توجد أدوار في قاعدة البيانات');
            return 1;
        }
        
        $this->info('📋 الأدوار الموجودة:');
        foreach ($roles as $role) {
            $this->line("   - {$role->name} ({$role->display_name})");
        }
        
        return 0;
    }
} 