<?php

namespace App\Imports;

use App\Models\InfoProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Events\BeforeImport;
use App\Events\NewImportFileStatus;
use Maatwebsite\Excel\Concerns\WithEvents;

class InfoProductsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithEvents
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $rows = 0;
    private $totalRows = 0;
    private $percentageStart = 0;
    private $percentageFinish = 0;

    function __construct($percentageStart, $percentageFinish)
    {
        $this->percentageStart = $percentageStart;
        $this->percentageFinish = $percentageFinish;
    }

    public function model(array $row)
    {
        ++$this->rows;
        
        if ( $this->rows % ceil($this->totalRows * 10 / 100) === 0 ) {
            $progressPercentage = round($this->getProgress() * ($this->percentageFinish - $this->percentageStart) / 100);
            broadcast( new NewImportFileStatus('executing', $this->percentageStart + $progressPercentage) );
        }
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

    // Hàm tính số hàng đã được nhập
    public function getRowCount(): int
    {
        return $this->rows;
    }

    // Hàm tính tiến độ hoàn thành nhập
    public function getProgress()
    {
        return round($this->rows / $this->totalRows * 100);
    }

    public function registerEvents(): array
    {
        return [
            // Trước khi nhập tính tổng số hàng dữ liệu của file
            BeforeImport::class => function (BeforeImport $event) {
                $rowsInWorksheet = $event->getReader()->getTotalRows();
                $this->totalRows = $rowsInWorksheet['Worksheet'] - 1;
            }
        ];
    }
}
