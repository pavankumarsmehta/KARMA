<?php
  
namespace App\Exports;
  
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class QuoteExports implements FromArray,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;

    private $myArray;

    public function __construct($myArray){
        $this->myArray = $myArray;
    }

    public function array(): array{
        return $this->myArray;
    }

    public function headings(): array
    {
        return ["Name","Email","Phone","City","State","Zip","Country","Manufacturer","Style","Product Name","Color","Pending Underlaymnt","Description","Spoken Before","News Letter","Date","Customer IP","Sales Representative Email","Status"];
    }
}