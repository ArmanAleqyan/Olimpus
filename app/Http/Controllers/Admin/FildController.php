<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FildSportType;
use App\Models\Fild;
use App\Models\SportType;
use App\Models\City;
use App\Models\FildPhoto;
use App\Models\FildGrafik;
use Illuminate\Support\Facades\Storage;

class FildController extends Controller
{
        public function filds($id){
        $get_filds_table = FildSportType::where('sport_id', $id)->get('fild_id')->pluck('fild_id')->toarray();

        $get = Fild::wherein('id', $get_filds_table)->get();


        return view('admin.Filds.all', compact('get'));
        }

        public function create_fild_page(){
            $sports = SportType::orderby('id', 'desc')->get();
            $city = City::orderby('name', 'desc')->get();
            return view('admin.Filds.create' , compact('sports', 'city'));
        }


        public function create_fild(Request $request){



          $create =   Fild::create([
               'city_id' => $request->city_id,
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'users_count' => $request->users_count,
                'peculiarities' => $request->peculiarities,
                'description' => $request->description,
                'size' => $request->size,
                'covering' => $request->covering
            ]);


            if (isset($request->photo)){
                $time = time();
                foreach ($request->photo as $photo){
                    $uploadedFile = $photo;
                    $fileName = $time++ . '.' . $uploadedFile->extension();
                    $uploadedFile->move(public_path('uploads'), $fileName);
                    FildPhoto::create([
                        'fild_id' => $create->id,
                        'photo' => $fileName
                    ]);
                }
            }

            if (isset($request->data)){
                foreach ($request->data as $data){
                    FildGrafik::create([
                       'fild_id' => $create->id,
                        'start' => $data['start_time'],
                        'end' => $data['end_time'],
                        'price' => $data['price'],
                    ]);
                }
            }
            
            if (isset($request->sport_type) ){
                foreach ($request->sport_type as $item) {
                    FildSportType::create([
                       'fild_id' => $create->id,
                       'sport_id' => $item,
                    ]);
                }
            }
            return response()->json([
               'status' => true,
               'message' => 'Created',
                'url' => route('create_fild_page')
            ],200);
        }

        public function single_page_fild($id){
            $get = Fild::where('id', $id)->first();
            $sports = SportType::orderby('id', 'desc')->get();
            $city = City::orderby('name', 'desc')->get();
            if ($get == null){
                return redirect()->back();
            }
            return view('admin.Filds.single', compact('get', 'sports', 'city'));
        }


        public function delete_fild_grafik($id){

            FildGrafik::where('id', $id)->delete();


            return redirect()->back()->with('created','created');
        }


        public function update_fild(Request $request){

            $get = Fild::where('id', $request->fild_id)->first();

            if ($get == null){
                return response()->json([
                   'status' => false
                ],422);
            }

            $get->update([
                'city_id' => $request->city_id,
                'name' => $request->name,
                'address' => $request->address,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'users_count' => $request->users_count,
                'peculiarities' => $request->peculiarities,
                'description' => $request->description,
                'size' => $request->size,
                'covering' => $request->covering
            ]);


            if (isset($request->photo)){
                $time = time();
                foreach ($request->photo as $photo){
                    $uploadedFile = $photo;
                    $fileName = $time++ . '.' . $uploadedFile->extension();
                    $uploadedFile->move(public_path('uploads'), $fileName);
                    FildPhoto::create([
                        'fild_id' => $request->fild_id,
                        'photo' => $fileName
                    ]);
                }
            }

           if (isset($request->sport_type)){
               FildSportType::where('fild_id', $request->fild_id)->delete();
               foreach ($request->sport_type as $item) {
                   FildSportType::create([
                       'fild_id' => $request->fild_id,
                       'sport_id' => $item,
                   ]);
               }
           }

            if (isset($request->data)){
                foreach ($request->data as $data){
                    FildGrafik::create([
                        'fild_id' =>  $request->fild_id,
                        'start' => $data['start_time'],
                        'end' => $data['end_time'],
                        'price' => $data['price'],
                    ]);
                }
            }

            if (isset($request->old_data )){
                foreach ($request->old_data as $key => $value){
                    FildGrafik::where('id', $key)->update([
                        'start' => $value['start_time'],
                        'end' => $value['end_time'],
                        'price' => $value['price'],
                    ]);
                }
            }


            return response()->json([
                'status' => true,
                'url' => route('single_page_fild' , $request->fild_id)
            ]);

        }



        public function delete_photo($id){

            $get_photo = FildPhoto::where('id' ,$id)->first();

            if ($get_photo == null){
                return redirect()->back();
            }
            $directoryPath = public_path('uploads/'.$get_photo->photo);


                unlink($directoryPath);

            $get_photo->delete();
            return redirect()->back()->with('created','created');
        }


        public function delete_fild($id){
            $get_fild = Fild::where('id' ,$id)->first();

            if ($get_fild == null){
                return redirect()->back();
            }


            $get_category = FildSportType::where('fild_id', $id)->first();

            $get_photo = FildPhoto::where('fild_id', $id)->get();


            foreach ($get_photo as $item) {
                $directoryPath = public_path('uploads/'.$item->photo);
                unlink($directoryPath);
            }


            $get_fild->delete();



            return redirect()->route('filds', $get_category->sport_id);




        }
}
