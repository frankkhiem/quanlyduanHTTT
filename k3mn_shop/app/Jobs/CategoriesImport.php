<?php

namespace App\Jobs;

use App\Events\NewImportFileStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CategoriesImport as ImportCategories;
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
        $import = new ImportCategories;
        Excel::import($import, $this->filePath);

        // for ($i = 0; $i < 10; $i ++) {
        //     sleep(1);
        //     if ( $i === 2 ) {
        //         $this->fail($i * 10);
        //     } else {
        //         broadcast( new NewImportFileStatus('executing', $i * 10) );
        //     }
        // }
        Storage::deleteDirectory('temp');
        broadcast( new NewImportFileStatus('finished', 100) );
    }

    public function failed($percentage)
    {
        broadcast( new NewImportFileStatus('failed', $percentage) );
    }
}
