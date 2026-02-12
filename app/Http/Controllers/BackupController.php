<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        // Consultar el último backup
        $lastBackup = Cache::get('last_backup_time', null);

        return view('admin.backup.backup', compact('lastBackup'));
    }

    public function backup(Request $request)
    {
        try {
            // Generar contenido del archivo SQL
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

            // Guardar archivo en storage/app/backup
            $fileName = 'PolleriaBD_Backup_' . date('Ymd_His') . '.sql';
            Storage::disk('local')->put("backup/$fileName", $backupSql);

            // Guardar la fecha del último backup
            Cache::put('last_backup_time', now(), 7200); // Cache por 2 horas

            // Regresar mensaje de éxito
            return redirect()->route('admin.backup.index')->with('success', 'La copia de seguridad se ha realizado con éxito.');
        } catch (\Exception $e) {
            // Regresar mensaje de error
            return redirect()->route('admin.backup.index')->with('error', 'Error al realizar la copia de seguridad: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sqlFile' => 'required|mimes:sql|max:20480000000000', // máximo 20GB
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $sqlFile = $request->file('sqlFile');
            $sqlContent = file_get_contents($sqlFile->getPathname());
            DB::unprepared($sqlContent);

            return redirect()->back()->with('success', 'La base de datos se restauró con éxito.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    public function automaticBackup()
    {
        try {
            // Comprobar si se realizó un backup automático en la última semana
            $lastWeeklyBackup = Cache::get('last_weekly_backup');
            if ($lastWeeklyBackup && Carbon::parse($lastWeeklyBackup)->diffInDays(now()) < 7) {
                return; // Ya se hizo el backup semanal
            }

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

            // Guardar el archivo de copia de seguridad en storage/app/backup
            $fileName = 'PolleriaBD_WeeklyBackup_' . date('Ymd_His') . '.sql';
            Storage::disk('backup')->put($fileName, $backupSql);

            // Guardar la hora del último backup semanal
            Cache::put('last_weekly_backup', now(), 604800); // 7 días en segundos

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error en el backup automático: ' . $e->getMessage());
        }
    }
}
