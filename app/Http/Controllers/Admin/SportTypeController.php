<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SportType;
use Illuminate\Support\Facades\File;

class SportTypeController extends Controller
{

    public function all_sport_type(){
            $get = SportType::orderby('id', 'desc')->get();


            return view('admin.Sport.all', compact('get'));
    }

    public function create_sport_type_page(){
        return view('admin.Sport.create');
    }

    public function create_sport(Request $request){
        if (isset($request->photo)){
            $uploadedFile = $request->file('photo');
            $fileName = time() . '.' . $uploadedFile->extension();
            $uploadedFile->move(public_path('uploads'), $fileName);
        }

        SportType::create([
           'name' => $request->name,
           'photo' => $fileName
        ]);
        return redirect()->back()->with('created','created');
    }



    public function single_page_sport($id){
        $get = SportType::whereid($id)->first();
        if($get == null){
            return redirect()->back();
        }
        return view('admin.Sport.single', compact('get'));
    }


    public function update_sport(Request $request){
        $get = SportType::where('id', $request->sport_id)->first();

        if ($get == null){
            return redirect()->back();
        }
        if (isset($request->photo)){
            $uploadedFile = $request->file('photo');
            $fileName = time() . '.' . $uploadedFile->extension();
            $uploadedFile->move(public_path('uploads'), $fileName);
            $oldFilePath = 'uploads/'.$get->photo;
            if (File::exists(public_path($oldFilePath))) {
                unlink(public_path($oldFilePath));
            }
        }else{
            $fileName = $get->photo;
        }

        SportType::whereid($request->sport_id)->update([
            'name' => $request->name,
            'photo' => $fileName
        ]);

        return redirect()->back()->with('created','created');
    }



    public function delete_sport($id){

        $get = SportType::where('id', $id)->first();

        if ($get == null){
            return redirect()->back();
        }
        $oldFilePath = 'uploads/'.$get->photo;
        if (File::exists(public_path($oldFilePath))) {
            unlink(public_path($oldFilePath));
        }

        $get->delete();


        return redirect()->route('all_sport_type')->with('created','created');
    }

}
