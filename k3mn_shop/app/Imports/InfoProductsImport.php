<?php

namespace App\Imports;

use App\Models\InfoProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class InfoProductsImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new InfoProduct([
            'product_id' => $row['product_id'],
            'attribute' => $row['attribute'],
            'information' => $row['information'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
