<?php

namespace App\Http\Controllers\Api\Search;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Fild;
use App\Models\FildGrafik;
use App\Models\FildSportType;
use Illuminate\Validation\Rule;
use Validator;
use Stevebauman\Location\Facades\Location;

class FilterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/get_filds",
     *     operationId="getFilds",
     *     tags={"Field Management"},
     *     summary="Get a list of fields",
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="city_id", type="integer", example=1),
     *             @OA\Property(property="sport_id", type="integer", example=2),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of fields retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object", ref="Fild Data")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object"),
     *         ),
     *     ),
     * )
     */
    public function get_filds(Request $request)
    {
        $fild = Fild::query();

        if (isset($request->city_id)) {
            $fild->where('city_id', $request->city_id);
        }

        if (isset($request->sport_id)){
            $get_filds = FildSportType::where('sport_id', $request->sport_id)->get('fild_id')->pluck('fild_id')->toarray();
            $fild->wherein('id' , $get_filds);
        }


        $get = $fild->with('sport_type', 'photo')->get();


        return response()->json([
            'status' => true,
            'data' => $get
        ], 200);
    }


    /**
     * @OA\Post(
     *     path="/api/get_fild_grafik",
     *     operationId="getFildGrafik",
     *     tags={"Grafik Management"},
     *     summary="Get available field schedules (grafiks) for a specific field and date",
     *     @OA\Parameter(
     *         name="fild_id",
     *         in="query",
     *         description="ID of the field",
     *         required=true,
     *         @OA\Schema(type="integer", example=1),
     *     ),
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date in Y-m-d format",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="2023-08-15"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of available grafiks for the specified field and date",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="Grafik data")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={
     *                 "fild_id": {"The fild id field is required."},
     *                 "date": {"The date field is required."},
     *             }),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong fild_id or Wrong date"),
     *         ),
     *     ),
     * )
     */
    public function get_fild_grafik(Request $request){
        $rules=array(
            'fild_id' => 'required',
            'date' => 'required|date',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }

        $get_fild = Fild::where('id' , $request->fild_id)->first();


        if ($get_fild == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong fild_id'
            ],422);
        }

        $date = Carbon::parse($request->date)->format('Y-m-d');

        if ($date < Carbon::now()->format('Y-m-d')){
            return response()->json([
               'status' => false,
               'message' => 'Wrong date'
            ],422);
        }
        $ip = $request->ip();
        $location = Location::get($ip);
        $timezone = $location->timezone ?? 'UTC';

        $get_events = \App\Models\Event::where('fild_id', $request->fild_id)->where('date', $date)->get('grafik_id')->pluck('grafik_id')->toarray();
        $get_grafik = FildGrafik::where('fild_id', $request->fild_id)
                                ->where('start', '>=', Carbon::now()->tz($timezone)->format('H:i:s'))
                                ->where('end', '>=', Carbon::now()->tz($timezone)->format('H:i:s') )
                                ->wherenotin('id',$get_events)
                                ->get();


        return response()->json([
           'status' => true,
           'data' => $get_grafik
        ],200);
    }








}