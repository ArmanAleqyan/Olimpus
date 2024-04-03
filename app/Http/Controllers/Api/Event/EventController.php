<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use App\Models\FildGrafik;
use App\Models\Fild;
use Stevebauman\Location\Facades\Location;
use App\Models\Event;
use App\Models\BalanceHistory;
class EventController extends Controller
{
    /**
     * @OA\Post(
     *     path="/get_events",
     *     operationId="getEvents",
     *     tags={"Event Management"},
     *     summary="Get list of events",
     *     @OA\Parameter(
     *         name="order_by_date",
     *         in="query",
     *         description="Order events by date in descending order",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User Id for events",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="order_by_time",
     *         in="query",
     *         description="Order events by start time in descending order",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of events retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="Events")),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated"),
     *         ),
     *     ),
     * )
     */
    public function get_events(Request $request){
            $get = Event::query();


            if (isset($request->order_by_date)){
                $get->orderby('date', 'desc');
            }
            if (isset($request->order_by_time)){
                $get->orderby('start_time', 'desc');
            }

            if (isset($request->user_id)){
                $get->where('user_id', $request->user_id);
            }

            $gets = $get->with('grafik','fild.photo')->where('start', '>=', Carbon::now())->simplepaginate(10);


            return response()->json([
               'status' => true,
               'data' => $gets
            ],200);
    }

    /**
     * @OA\Post(
     *     path="api/create_new_event",
     *     operationId="createNewEvent",
     *     tags={"Event Management"},
     *     summary="Create a new event",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="fild_id", type="integer", example=1),
     *             @OA\Property(property="grafik_id", type="integer", example=1),
     *             @OA\Property(property="date", type="string", format="date", example="2023-08-15"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Event created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Event created"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={
     *                 "fild_id": {"The fild id field is required."},
     *                 "grafik_id": {"The grafik id field is required."},
     *                 "date": {"The date field must be a date."},
     *             }),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid input or insufficient balance",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="You Not have Money"),
     *         ),
     *     ),
     * )
     */
    public function create_new_event(Request $request){
        $rules=array(
            'fild_id' => 'required',
            'grafik_id' => 'required',
            'date' => 'date',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }

        $get_grafik = FildGrafik::where('id', $request->grafik_id)->first();
        $get_fild = Fild::where('id', $request->fild_id)->first();
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $ip = $request->ip();
        $location = Location::get($ip);
        $timezone = $location->timezone ?? 'UTC';
        if ($date < Carbon::now()->tz($timezone)->format('Y-m-d')){
            return response()->json([
               'status' => false,
               'message' => 'Wrong date'
            ],422);
        }
        if ($get_grafik == null || $get_fild == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong grafik_id or fild_id'
            ],422);
        }
        $get_event_validation = Event::where('fild_id' , $request->fild_id)->where('grafik_id', $request->grafik_id)->where('date', $date)->first();
        if ($get_event_validation != null){
            return response()->json([
               'status' => false,
               'message' => 'this  grafik saved with other  user'
            ],422);
        }
        if (auth()->user()->balance < $get_grafik->price){
            return response()->json([
               'status' => false,
               'message' => 'You Not have Money'
            ],422);
        }


     $create =   Event::create([
           'user_id' => auth()->user()->id,
           'grafik_id' => $request->grafik_id,
            'fild_id' =>  $request->fild_id,
            'price' => $get_grafik->price,
            'date' => $request->date,
            'start_time' => $get_grafik->start,
            'start' => Carbon::parse("$request->date $get_grafik->start"),
        ]);
        $new_balance = auth()->user()->balance - $get_grafik->price;
        auth()->user()->update([
           'balance' =>  $new_balance
        ]);

        BalanceHistory::create([
           'user_id' => auth()->user()->id,
           'message' => "Pago de evento № $create->id",
           'type' =>  'new_event',
           'price' =>  $get_grafik->price

        ]);
        return response()->json([
           'status' => true,
           'message' => 'Event created'
        ],200);
    }



}
