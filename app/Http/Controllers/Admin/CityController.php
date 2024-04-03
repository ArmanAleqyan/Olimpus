<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
class CityController extends Controller
{
    public function get_all_city(Request $request){
        $get = City::orderby('name', 'Asc')->get();

        return  view('admin.City.All', compact('get'));
    }
    public function create_city_page(){
        return view('admin.City.create');
    }

    public function create_city(Request  $request){

        City::create([
           'name' => $request->name
        ]);


        return redirect()->back()->with('created', 'created');
    }

    public function single_page_city($id){
        $get = City::where('id', $id)->first();

        if ($get == null){
            return redirect()->back();
        }

        return  view('admin.City.single', compact('get'));
    }

    public function update_city(Request $request){
        City::where('id', $request->city_id)->update([
           'name' => $request->name
        ]);


        if ($request->all() == []){
            return redirect()->back();
        }else{
            return redirect()->back()->with('created','created');
        }

    }

    public function delete_city($id){
        City::where('id', $id)->delete();
        return redirect()->route('get_all_city')->with('created','created');
    }
}
