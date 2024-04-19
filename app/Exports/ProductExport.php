<?php
  
namespace App\Exports;
  
use App\Models\ExportProducts;
use Maatwebsite\Excel\Concerns\FromCollection;  

  
class ProductExport implements FromCollection
{
    protected $data;
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function collection()
    {
        return collect($this->data);
    }
}