<?php

namespace App\Http\Controllers;

use App\Vihecle;
use App\Country;
use App\Categ;
use App\Subcateg;
use App\Sales;
use App\Series;
use App\Producer;

use Illuminate\Http\Request;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class VihecleController extends Controller
{
    public function list()
    {
        $vihecles = Vihecle::all();
        return view("vihecle.list", ["vihecles" => $vihecles]);
    }

    function only_number($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public function importPage()
    {
        return view("vihecle.import");
    }
    
    function import(Request $req)
    {
        if(false)//($req->file("fn"))
        {
            $path = $req->file("fn");
            $inputFileType = 'Xlsx';
            $inputFileName = $path;
            $reader = IOFactory::createReader($inputFileType);
            $reader->setLoadAllSheets();
            $spreadsheet    = $reader->load($inputFileName);
            //$worksheetNames = $reader->listWorksheetNames($inputFileName);
            //$worksheetData  = $reader->listWorksheetInfo($inputFileName);
            
            $worksheet      = $spreadsheet->getActiveSheet();
            $highestRow     = $worksheet->getHighestRow(); // e.g. 10
            //$highestColumn  = $worksheet->getHighestColumn(); // e.g 'F'
            
            
            // foreach($object->getWorksheetIterator() as $spreadsheet)
            //{
                //$highestRow    = $worksheet->getHighestRow();
                //$highestColumn = $worksheet->getHighestColumn();
    
                for($row = 2; $row <= $highestRow; $row++)
                {
                    $id         = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                    $producer   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                    $series     = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                    $size       = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                    $config     = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                    $model      = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                    $sales      = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                    $year       = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                    $cylinder   = $worksheet->getCellByColumnAndRow(9, $row)->getValue();
                    $drivetype  = $worksheet->getCellByColumnAndRow(10, $row)->getValue();
                    $engine     = $worksheet->getCellByColumnAndRow(11, $row)->getValue();
                    $country    = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
                    $categ      = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
                    $subcateg   = $worksheet->getCellByColumnAndRow(14, $row)->getValue();
    
                    $id = $this->only_number($id);
                    $year = $this->only_number($year);
                    $cylinder = $this->only_number($cylinder);
                    $id = str_pad($id, 11 , '0' , STR_PAD_LEFT); 

                    //echo $producer." ".$model." | ".$year."<br>";
                    $saleObj = Sales::where("name","=",$sales)->first();
                    if($saleObj) {
                        echo "Sales ID: ".$saleObj->id." ".$saleObj->name."<br>";
                    }
                    else if(trim($sales)!= ""){
                        $saleObj=new Sales();
                        $saleObj->name = $sales;
                        $saleObj->save();
                        echo "Added To Sales: ".$sales."<br>";
                    }

                    $country_obj = Country::where("name","=",$country)->first();
                    if($country_obj) {
                        echo "Country ID: ".$country_obj->id." ".$country_obj->name."<br>";
                    }
                    else if(trim($country)!= ""){
                        $country_obj=new Country();
                        $country_obj->name = $country;
                        $country_obj->save();
                        echo "Added To Country: ".$country."<br>";
                    }

                    $pro_obj = Producer::where("name","=",$producer)->first();
                    if($pro_obj) {
                        echo "Producer ID: ".$pro_obj->id." ".$pro_obj->name."<br>";
                    }
                    else if(trim($producer)!= ""){
                        $pro_obj=new Producer();
                        $pro_obj->name = $producer;
                        $pro_obj->save();
                        echo "Added To Producer: ".$producer."<br>";
                    }

                    $series_obj = Series::where("name","=",$series)->where('producer_id', $pro_obj->id)->first();
                    if($series_obj) {
                        echo "Series ID: ".$series_obj->id." ".$series_obj->name."<br>";
                    }
                    else if(trim($series)!= ""){
                        $series_obj=new Series();
                        $series_obj->name = $series;
                        $series_obj->producer_id = $pro_obj->id;
                        $series_obj->save();
                        echo "Added To Series: ".$series."<br>";
                    }

                    $categ_obj = Categ::where("name","=",$categ)->first();
                    if($categ_obj) {
                        echo "Categ ID: ".$categ_obj->id." ".$categ_obj->name."<br>";
                    }
                    else if(trim($categ)!= ""){
                        $categ_obj=new Categ();
                        $categ_obj->name = $categ;
                        $categ_obj->save();
                        echo "Added To Categ: ".$categ."<br>";
                    }

                    $subcateg_obj = Subcateg::where("name","=",$subcateg)->where('categ_id', $categ_obj->id)->first();
                    if($subcateg_obj) {
                        echo "Subcateg ID: ".$subcateg_obj->id." ".$subcateg_obj->name."<br>";
                    }
                    else if(trim($subcateg)!= ""){
                        $subcateg_obj=new Subcateg();
                        $subcateg_obj->name = $subcateg;
                        $subcateg_obj->categ_id = $categ_obj->id;
                        $subcateg_obj->save();
                        echo "Added To Subcateg: ".$subcateg."<br>";
                    }

                    $subcateg_obj = Subcateg::where("name","=",$subcateg)->where('categ_id', $categ_obj->id)->first();
                    if($subcateg_obj) {
                        echo "Subcateg ID: ".$subcateg_obj->id." ".$subcateg_obj->name."<br>";
                    }
                    else if(trim($subcateg)!= ""){
                        $subcateg_obj=new Subcateg();
                        $subcateg_obj->name = $subcateg;
                        $subcateg_obj->categ_id = $categ_obj->id;
                        $subcateg_obj->save();
                        echo "Added To Subcateg: ".$subcateg."<br>";
                    }

                    $vih = Vihecle::where("id","=",$id)->first();
                    if($vih) {
                        echo "Vih ID: ".$vih->id."<br>";
                    }
                    else{
                        $vih=new Vihecle();
                        $vih->id = $id;
                        $vih->series_id = $series_obj->id;
                        $vih->subcateg_id = $subcateg_obj->id;
                        $vih->country_id = $country_obj->id;
                        $vih->sales_id = $saleObj->id ?? null;
                        $vih->size = $id;
                        $vih->config = $id;
                        $vih->year = $id;
                        $vih->cylinder = $id;
                        $vih->eng_output = $id;
                        $vih->drivetype = $id;
                        $vih->save();
                        echo "Added To Vihecles: ".$model."<br>";
                    }
                } 
        } 
    }
}
