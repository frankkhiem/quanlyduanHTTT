<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Storage;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    // đường dẫn tới thư mục giải nén từ file zip chứa thư mục ảnh của sản phẩm
    public $pathDirImages;

    function __construct( $path )
    {
        $this->pathDirImages = $path;
    }

    public function collection(Collection $rows)
    {
        //
        foreach ( $rows as $row ) {
            $directories = Storage::allDirectories($this->pathDirImages);
            $regex = "/\/{$row['id']}$/";
            $dirImagesProduct = '';
            foreach ( $directories as $dir ) {
                if ( preg_match($regex, $dir) ) {
                    $dirImagesProduct = $dir;
                    break;
                }
            }
            
            // định dạng lại thumnail và imagesPath sau mỗi lần lặp row
            $imagesPath = [];
            $thumbnail = "";

            if ( $dirImagesProduct ) {
                $images = File::files( storage_path('app\\'.$dirImagesProduct) );

                if ( sizeof($images) > 0 ) {
                    foreach ( $images as  $image ) {
                        // di chuyển các ảnh sản phẩm vào folder root public
                        File::move( $image->getRealPath(), public_path( 'uploads/imagesProduct/'.$image->getFilename() ) );
                        $imagesPath[] = $image->getFilename();
                    }
                    $thumbnail = $images[0]->getFilename();
                }
            }

            Product::updateOrCreate(
                [ 
                    'id' => $row['id'] 
                ],
                [
                    'category_id' => $row['category_id'],
                    'name' => $row['name'],
                    'thumbnail' => $thumbnail,
                    'image' => json_encode($imagesPath),
                    'short_desc' => $row['short_desc'],
                    'full_desc' => $row['full_desc'],
                    'price' => $row['price'],
                    'status_product_id' => $row['status_product_id'],
                    'star' => $row['star'],
                ]
            );
        }
    }
}
