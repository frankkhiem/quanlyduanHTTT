<?php

namespace App\Jobs;

use App\Events\NewImportFileStatus;
use App\Events\ResultCategoriesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoriesImport as ImportCategories;
use Exception;
use Illuminate\Support\Facades\Storage;
use Throwable;

class CategoriesImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(1);
        $import = new ImportCategories;
        Excel::import($import, $this->filePath);

        // sleep(1);
        Storage::deleteDirectory('temp');
        // throw new Exception("Thực hiện nhập danh mục sản phẩm thất bại. Vui lòng thử lại!");
        broadcast( new ResultCategoriesImport('finished', 'success', $import->getRowCount(), $import->getRowsFail()) );
    }

    public function failed(Throwable $exception)
    {
        broadcast( new ResultCategoriesImport('failed', $exception->getMessage()) );
    }
}
