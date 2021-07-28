<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport as ImportProducts;
use App\Imports\InfoProductsImport;
use ZipArchive;
use App\Events\NewImportFileStatus;
use Exception;

class ProductsImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $tries = 1;

    protected $pathZipFile;


    public function __construct($pathZip)
    {
        $this->pathZipFile = $pathZip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(1);
        $zip = new ZipArchive();
        if ( $zip->open( storage_path('app\\'.$this->pathZipFile) ) ) {
            $zip->extractTo( storage_path('app\\temp\\'. File::name($this->pathZipFile)) );
            $zip->close();
            
            // Đường dẫn tương đối tới thư mục giải nén
            $pathDirExtract = 'temp\\'.File::name($this->pathZipFile);
        } else {
            echo('Extract fail!!!!!!');
        }
        broadcast( new NewImportFileStatus('executing', 10) );

        $productsData = ""; 
        $infoProductsData = "";
        $filesInDir = Storage::files( $pathDirExtract );
        foreach ( $filesInDir as $file ) {
            if ( File::name($file) === "products_data" ) $productsData = $file;
            if ( File::name($file) === "information_products_data" ) $infoProductsData = $file;
        }
        broadcast( new NewImportFileStatus('executing', 20) );

        // Nếu không tồn tại 2 tệp thỏa mãn thì fail job
        if ( !$productsData || !$infoProductsData ) {
            Storage::deleteDirectory('temp');
            $this->fail(20);
        }

        Excel::import(new ImportProducts( $pathDirExtract, 20, 65 ), $productsData);
        Excel::import(new InfoProductsImport(65, 95), $infoProductsData);
        Storage::deleteDirectory('temp');
        sleep(1);
        broadcast( new NewImportFileStatus('finished', 100) );
    }

    public function failed($percentage)
    {
        broadcast( new NewImportFileStatus('failed', $percentage) );
    }
}
