<?php
  
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
  
class SampleRequestExports implements FromArray,WithHeadings
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
        return ["Name","Email","Phone","City","State","Zip","Country","Manufacturer","Style","Color","Description","Spoken Before","Date","Customer IP","Sales Representative Email","Status"];
    }
}