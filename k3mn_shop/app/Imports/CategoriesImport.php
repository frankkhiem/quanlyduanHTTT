<?php

namespace App\Imports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\BeforeImport;
use App\Events\NewImportFileStatus;

class CategoriesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithEvents
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $rows = 0;
    private $totalRows = 0;

    public function model(array $row)
    {
        sleep(3);
        ++$this->rows;
        
        if ( $this->rows % ceil($this->totalRows * 10 / 100) === 0 ) {
            $progressPercentage = $this->getProgress();
            broadcast( new NewImportFileStatus('executing', $progressPercentage) );
        }
        
        return new Category([
            //
            'name' => $row['name'],
            'short_desc' => $row['short_desc'],
            'full_desc' => $row['full_desc']
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
