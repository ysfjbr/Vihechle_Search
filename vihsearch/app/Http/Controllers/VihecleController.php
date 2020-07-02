<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Vihecle;
use App\Country;
use App\Categ;
use App\Subcateg;
use App\Sales;
use App\Series;
use App\Producer;
use App\Part;
use App\Vihecle_Part;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \PhpOffice\PhpSpreadsheet\IOFactory;

class VihecleController extends Controller
{
    public function list()
    {
        $vihecles = Vihecle::all();
        return view("vihecle.search");
    }

    function only_number($str) {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public function importPage()
    {
        return view("vihecle.import");
    }

    public function getItemData(Request $req)
    {
        $v = Vihecle::find($req->vid);
        $vs= $v->series;
        $vs->producer;
        $v->country;
        $sc = $v->subcateg;
        $sc->categ;
        $v->sales;
        $v->parts;
        return $v;
    }

    public function getSearchData(Request $req)
    {
        $search_res =  DB::table("vihecles")
                        ->join('series', 'series.id', '=', 'vihecles.series_id')
                        ->join('producers', 'producers.id', '=', 'series.producer_id')
                        ->leftJoin('countries', 'countries.id', '=', 'vihecles.country_id')
                        ->leftJoin('sales', 'sales.id', '=', 'vihecles.sales_id')
                        ->join('subcategs', 'subcategs.id', '=', 'vihecles.subcateg_id')
                        ->join('categs', 'categs.id', '=', 'subcategs.categ_id')

                        ->select(   'vihecles.*', 
                                    'series.name as series', 
                                    'series.producer_id', 
                                    'producers.name as producer',
                                    'countries.name as country',
                                    'sales.name as sales',
                                    'subcategs.name as subcateg',
                                    'categs.name as categ'
                                )
                        ->where("subcategs.categ_id","=",$req->categ)
                        ->where($req->producer ? "series.producer_id":"" ,$req->producer ? "=" : "",$req->producer)
                        ->where($req->yearFrom ? "vihecles.year":"" ,$req->yearFrom ? ">=" : "",$req->yearFrom)
                        ->where($req->yearTo ? "vihecles.year":"" ,$req->yearTo ? "<=" : "",$req->yearTo)
                        ->where(DB::raw("cast(vihecles.size as Int)") , ">=" ,$req->sizeFrom )
                        ->where(DB::raw("cast(vihecles.size as Int)") , "<=" ,$req->sizeTo)
                        ->where(DB::raw("series.name || ' '|| vihecles.size || ' '|| vihecles.config") , "like" ,"%".$req->model."%")
                        ->paginate($req->page_size);
        return $search_res;
    }
    
    function getModels($prod)
    {
        return $prod->model;
    }

    public function getFilterData(Request $req)
    {
        $categs =  DB::table("categs")->orderBy('name', 'asc')
                    ->select('name as text', 'id as value')
                    ->get();

        $vihecles =  DB::table("vihecles")
                    ->join('series', 'series.id', '=', 'vihecles.series_id')
                    ->join('producers', 'producers.id', '=', 'series.producer_id')
                    //->leftJoin('countries', 'countries.id', '=', 'vihecles.country_id')
                    //->leftJoin('sales', 'sales.id', '=', 'vihecles.sales_id')
                    ->join('subcategs', 'subcategs.id', '=', 'vihecles.subcateg_id')
                    ->join('categs', 'categs.id', '=', 'subcategs.categ_id')

                    ->select(   'vihecles.*', 
                                'series.name as series', 
                                'series.producer_id', 
                                'producers.name as producer',
                                //'countries.name as country',
                                //'sales.name as sales',
                                'subcategs.name as subcateg',
                                'categs.name as categ'
                            )
                    ->selectRaw("series.name || ' '|| vihecles.size || ' '|| vihecles.config AS model")
                    ->where("subcategs.categ_id","=",$req->categ)
                    ->where($req->producer ? "series.producer_id":"" ,$req->producer ? "=" : "",$req->producer)
                    ->where($req->yearFrom ? "vihecles.year":"" ,$req->yearFrom ? ">=" : "",$req->yearFrom)
                    ->where($req->yearTo ? "vihecles.year":"" ,$req->yearTo ? "<=" : "",$req->yearTo)
                    ->where(DB::raw("cast(vihecles.size as Int)") , ">=" ,$req->sizeFrom )
                    ->where(DB::raw("cast(vihecles.size as Int)") , "<=" ,$req->sizeTo)
                    //->where(DB::raw("series.name || ' '|| vihecles.size || ' '|| vihecles.config") , "like" ,"%".$req->model."%")
                    ->get();

        $producers  =  DB::table("producers")
                        ->join('series', 'series.producer_id', '=', 'producers.id')
                        ->join('vihecles', 'vihecles.series_id', '=', 'series.id')
                        ->join('subcategs', 'vihecles.subcateg_id', '=', 'subcategs.id')
                        ->where("subcategs.categ_id","=",$req->categ)
                        ->select("producers.*")
                        ->groupBy('producers.name')
                        ->orderBy('producers.name', 'asc')
                        ->get(); 

        $models = [];

        foreach ($vihecles as $vihecle)
        {
            if($req->producer) array_push($models,$vihecle->model);        
        }

        //$producers = array_unique($producers, SORT_REGULAR);
        $models = array_unique($models);
        
        /* $models =  DB::table("vihecles")
            ->join('series', 'series.id', '=', 'vihecles.series_id')
            ->selectRaw("series.name || ' '|| vihecles.size || ' '|| vihecles.config AS model")
            ->where("series.producer_id" ,"=" ,$req->producer)
            ->orderBy('series.name', 'asc')
            ->orderBy('vihecles.size', 'asc')
            ->orderBy('vihecles.config', 'asc')
            ->distinct()->get(); */
            
        $years_size= DB::table("vihecles")
                    ->selectRaw("min(year) as minYear")->selectRaw("max(year) as maxYear")
                    ->selectRaw("min(CAST(size AS INT )) as minSize")->selectRaw("max(CAST(size AS INT )) as maxSize")
                    ->join('series', 'series.id', '=', 'vihecles.series_id')
                    ->join('subcategs', 'subcategs.id', '=', 'vihecles.subcateg_id')
                    ->where("subcategs.categ_id","=",$req->categ)
                    ->where($req->producer ? "series.producer_id":"" ,$req->producer ? "=" : "",$req->producer)
                    ->where(DB::raw("series.name || ' '|| vihecles.size || ' '|| vihecles.config") , "like" ,"%".$req->model."%")
                    ->first();

        return response()->json([
                    'models'     => $models,
                    'producers'  => $producers,
                    'categs'     => $categs,
                    'years_size' => $years_size,
                ]);

    }

    public function import(Request $req)
    {//(false)//
        
        if($req->file("fn1"))
        {
            ini_set('max_execution_time', 1800);  /// Timeout = 30 min
            $path = $req->file("fn1");
            $inputFileType = 'Xlsx';
            $inputFileName = $path;
            $reader = IOFactory::createReader($inputFileType);
            $reader->setLoadAllSheets();
            $spreadsheet    = $reader->load($inputFileName);
        
            $worksheet      = $spreadsheet->getActiveSheet();
            $highestRow     = $worksheet->getHighestRow();

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
                    $vih->size = $size;
                    $vih->config = $config;
                    $vih->year = $year;
                    $vih->cylinder = $cylinder;
                    $vih->eng_output = $engine;
                    $vih->drivetype = $drivetype;
                    $vih->save();
                    echo "Added To Vihecles: ".$model."<br>";
                }
            }
        }

        if($req->file("fn2"))
        {
            ini_set('max_execution_time', 1800);  /// Timeout = 30 min
            $path = $req->file("fn2");
            $inputFileType = 'Xlsx';
            $inputFileName = $path;
            $reader = IOFactory::createReader($inputFileType);
            $reader->setLoadAllSheets();
            $spreadsheet    = $reader->load($inputFileName);
        
            $worksheet      = $spreadsheet->getActiveSheet();
            $highestRow     = $worksheet->getHighestRow();

            for($row = 2; $row <= $highestRow; $row++)
            {
                $id         = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $name       = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $active     = $worksheet->getCellByColumnAndRow(3, $row)->getValue() == "1";
                $vihecles   = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                
                $id = $this->only_number($id);
                //print_r(explode("*",$vihecles));
                //echo "Added To Vihecles: ".$name.($active ? " actiiiive ": "")."<br>";

                $part_obj = Part::where("name","=",$name)->first();
                if($part_obj) {
                    echo "Part ID: ".$part_obj->id." ".$part_obj->name."<br>";
                }
                else if(trim($name)!= ""){
                    $part_obj=new Part();
                    $part_obj->id = $id;
                    $part_obj->name = $name;
                    $part_obj->active = $active;
                    $part_obj->save();
                    echo "Added To Part: ".$name."<br>";
                }

                foreach(explode("*",$vihecles) as $v_id)
                {
                    $vp_obj=new Vihecle_Part();
                    $vp_obj->part_id = $id;
                    $vp_obj->vihecle_id = $v_id;
                    $vp_obj->save();
                }

            }
        }
    }
}
