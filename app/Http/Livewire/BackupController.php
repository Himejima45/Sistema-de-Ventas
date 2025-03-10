<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use ZipArchive;

class BackupController extends Component
{
    use WithPagination;
    public $pageTitle, $componentName;

    protected $listeners = [
        'Destroy' => 'delete'
    ];

    public function get_size($size)
    {
        $units = [
            'GB' => 1 << 30,
            'MB' => 1 << 20,
            'KB' => 1 << 10,
        ];

        foreach ($units as $unit => $value) {
            if ($size >= $value) {
                return number_format($size / $value, 2) . $unit;
            }
        }

        return number_format($size, 2) . " B";
    }

    private $pagination = 20;

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName = 'Respaldos';
    }

    public function save()
    {
        Artisan::call('backup:run --only-db');
        $this->emit('record-created', 'Respaldo creado con éxito');
    }

    public function delete($record)
    {
        $disk = Storage::disk('backups');

        if ($disk->exists($record)) {
            $disk->delete($record);
        }
    }

    public function download($record)
    {
        $file_name = $record;
        $disk = Storage::disk('backups');

        if ($disk->exists($file_name)) {
            $stream = $disk->readStream($file_name);
            $download_file_name = explode('/', $file_name)[1];

            return Response::stream(function () use ($stream) {
                fpassthru($stream);
                fclose($stream);
            }, 200, [
                'Content-Type' => $disk->mimeType($file_name),
                'Content-Length' => $disk->size($file_name),
                'Content-disposition' => "attachment; filename={$download_file_name}",
            ]);
        } else {
            return 'El respaldo que intenta descargar no existe, pruebe creando una copia de seguridad u otro respaldo';
        }
    }

    public function upload($record)
    {
        $file_name = $record;
        $disk = Storage::disk('backups');

        if (!$disk->exists($file_name)) {
            $this->emit('upload-error');
        }

        $this->emit('upload-action');

        $zip = new ZipArchive;
        $sqlContent = '';

        if ($zip->open($disk->path($file_name)) === TRUE) {
            $zip->extractTo(storage_path('app'));

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);

                if (pathinfo($filename, PATHINFO_EXTENSION) === 'sql') {
                    $sqlContent = $zip->getFromIndex($i);
                    break;
                }
            }

            $zip->close();
        } else {
            $this->emit('upload-error');
        }

        if (!empty($sqlContent)) {
            try {
                DB::unprepared($sqlContent);
                $this->emit('upload-success');

                return 'Base de datos actualizada correctamente.';
            } catch (\Exception $e) {
                \Log::error('Error al ejecutar el SQL: ' . $e->getMessage());
                $this->emit('upload-error');
            }
        } else {
            $this->emit('upload-error');
        }
    }

    public function render()
    {
        $disk = Storage::disk('backups');
        $files = $disk->files(config('app.name'));

        $backups = collect($files)->map(function ($file) use ($disk) {
            $data = explode('/', $file);
            $date = explode('.', $data[1])[0];

            return [
                'key' => $file,
                'created_at' => Carbon::createFromFormat('Y-m-d-H-i-s', $date)->format('d/m/Y - h:i a'),
                'size' => $this->get_size($disk->size($file))
            ];
        })->reverse();

        $perPage = 10;
        $currentPage = Paginator::resolveCurrentPage();
        $currentItems = $backups->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedBackups = new LengthAwarePaginator($currentItems, $backups->count(), $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);

        return view('livewire.backups', ['backups' => $paginatedBackups])
            ->extends('layouts.theme.app')
            ->section('content');
    }
}
