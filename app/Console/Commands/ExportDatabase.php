<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportDatabase extends Command
{
    protected $signature = 'db:export';
    protected $description = 'Export PostgreSQL database to a SQL file';

    public function handle()
    {
        $dbHost = config('database.connections.pgsql.host', 'localhost');
        $dbPort = config('database.connections.pgsql.port', '5432');
        $dbUser = config('database.connections.pgsql.username', 'postgres');
        $dbPass = config('database.connections.pgsql.password', '123456');
        $dbName = config('database.connections.pgsql.database', 'laravel_db');

        $backupPath = storage_path('app/backup.sql');

        $command = sprintf(
            'PGPASSWORD=\'%s\' pg_dump -U %s -h %s -p %s -d %s -F p > %s',
            $dbPass,
            $dbUser,
            $dbHost,
            $dbPort,
            $dbName,
            $backupPath
        );

        $output = null;
        $resultCode = null;
        exec($command, $output, $resultCode);

        if ($resultCode === 0) {
            $this->info("Database exported successfully to: $backupPath");
        } else {
            $this->error("Failed to export database. Command output: " . implode("\n", $output));
        }
    }
}