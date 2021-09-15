<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainCategory;

class CollectTut extends Controller
{
    public function index(){
       //$numbers = [1,2,3,4];
       //$col = collect($numbers);
       //return $col->avg();

       //$names = collect(['name','age']);
       //$res = $names->combine(['ahmed','28']);
       //return $res;
       
       //$ages = collect([2,3,5,6,7,9]);
       //return $ages->count();

       //$ages = collect([2,3,3,6,6,9]);
       //return $ages->countBy();

       //$ages = collect([2,5,5,6,7,9]);
       //return $ages->duplicates();
       
    }

    public function complex(){
/*
        $cats = MainCategory::get();
        $cats->each(function($cat){
          unset($cat->translation_lang);
          unset($cat->translation_of);
          return $cat;
        });

        return $cats;

*/
        $cats = MainCategory::get();
        $cats->each(function($cat){
          if($cat->active == 0){
            unset($cat->translation_lang);
            unset($cat->translation_of);
          }
          $cat->name = 'Marcos';
          return $cat;
        });

        return $cats;

    }

    public function complexFilter(){

        $cats = MainCategory::get();
        $cats = collect($cats);
        $resOfFilter = $cats->filter(function($value,$key){
           return $value['translation_lang'] == 'ar';
        });
        return array_values($resOfFilter->all());
    }

    public function complexTransform(){

        $cats = MainCategory::get();
        $cats = collect($cats);
        return $resOfFilter = $cats->transform(function($value,$key){
           $data = [];
           $data['name'] = $value['name'];
           $data['age'] = 28;
           return $data;
        });
    }
}
