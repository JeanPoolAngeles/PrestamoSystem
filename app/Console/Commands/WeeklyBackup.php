<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class WeeklyBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:weekly-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar una copia de seguridad semanal de la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Obtener todas las tablas de la base de datos
            $tables = DB::select('SHOW TABLES');
            $backupSql = '';

            foreach ($tables as $table) {
                foreach ($table as $tableName) {
                    $backupSql .= "DROP TABLE IF EXISTS $tableName;";
                    $createTable = DB::selectOne("SHOW CREATE TABLE $tableName");
                    $backupSql .= "\n\n" . $createTable->{'Create Table'} . ";\n\n";
                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $row = (array) $row;
                        $row = array_map('addslashes', $row);
                        $row = array_map('htmlspecialchars', $row);
                        $backupSql .= "INSERT INTO $tableName VALUES ('" . implode("', '", $row) . "');\n";
                    }
                    $backupSql .= "\n\n\n";
                }
            }

            // Guardar el archivo en storage/app/backup
            $fileName = 'PolleriaBD_WeeklyBackup_' . date('Ymd_His') . '.sql';
            Storage::disk('backup')->put($fileName, $backupSql);

            $this->info('Copia de seguridad semanal realizada con éxito.');
        } catch (\Exception $e) {
            $this->error('Error al realizar la copia de seguridad semanal.');
        }
    }
}
