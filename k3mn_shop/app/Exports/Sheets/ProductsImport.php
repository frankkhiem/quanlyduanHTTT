<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class ProductsImport implements FromArray, WithHeadings, WithTitle, WithStrictNullComparison
{
    protected $logRowsImport;

    public function __construct($logRowsImport)
    {
        $this->logRowsImport = $logRowsImport;
    }

    public function array(): array
    {
        return $this->logRowsImport;
    }

    public function headings(): array
    {
        return [
            '# Row Order', 'id', 'category_id', 'name', 'short_desc', 'full_desc', 'price', 'status_product_id', 'star', 'Status Import'
        ];
    }

    public function title(): string
    {
        return 'Log Products Import Fail';
    }
}
