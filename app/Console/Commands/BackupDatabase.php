<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

// نسخة احتياطية مضغوطة من قاعدة البيانات في storage/app/backups، مع حذف الأقدم من المدة المحددة
class BackupDatabase extends Command
{
    protected $signature = 'backup:database {--keep=14 : عدد الأيام التي تُحفظ نسخها}';

    protected $description = 'نسخ احتياطي لقاعدة بيانات MySQL (mysqldump + gzip)';

    public function handle(): int
    {
        $db = config('database.connections.' . config('database.default'));
        if (($db['driver'] ?? null) !== 'mysql') {
            $this->error('النسخ الاحتياطي يدعم MySQL فقط');
            return self::FAILURE;
        }

        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);
        $file = $dir . '/db-' . now()->format('Y-m-d_His') . '.sql.gz';

        // كلمة المرور عبر متغير بيئة حتى لا تظهر في قائمة العمليات
        $process = Process::fromShellCommandline(
            'mysqldump --single-transaction --quick --routines --no-tablespaces -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USER" "$DB_NAME" | gzip > "$OUT"',
            null,
            [
                'DB_HOST' => $db['host'], 'DB_PORT' => (string) $db['port'], 'DB_USER' => $db['username'],
                'DB_NAME' => $db['database'], 'MYSQL_PWD' => (string) $db['password'], 'OUT' => $file,
            ],
            null,
            600
        );
        $process->run();

        if (!$process->isSuccessful() || filesize($file) < 100) {
            File::delete($file);
            $this->error('فشل النسخ الاحتياطي: ' . trim($process->getErrorOutput()));
            return self::FAILURE;
        }

        // حذف النسخ الأقدم من المدة المحددة
        $cutoff = now()->subDays((int) $this->option('keep'))->getTimestamp();
        foreach (File::glob($dir . '/db-*.sql.gz') as $old) {
            if (File::lastModified($old) < $cutoff) {
                File::delete($old);
            }
        }

        $this->info('تم: ' . basename($file) . ' (' . round(filesize($file) / 1024) . ' KB)');
        return self::SUCCESS;
    }
}
