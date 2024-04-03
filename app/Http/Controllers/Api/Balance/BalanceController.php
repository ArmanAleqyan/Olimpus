<?php

namespace App\Http\Controllers\Api\Balance;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\BalanceHistory;
use App\Models\Respecto;
use Illuminate\Validation\Rule;
use Validator;
class BalanceController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/get_balance_history",
     *     summary="Get balance history for the authenticated user",
     *     tags={"Balance"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with balance history data",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function get_balance_history(){
        $get = BalanceHistory::where('user_id', auth()->user()->id)->with('sender', 'receiver')->orwhere('sender_id', auth()->user()->id)->orwhere('receiver_id', auth()->user()->id)->orderby('id', 'desc')->simplepaginate(10);
        return response()->json([
           'status' =>true,
           'data' => $get
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/send_bonus_for_other_user",
     *     summary="Send bonus to another user",
     *     tags={"Balance"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="bonus", type="decimal", format="float", example=50.00),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response after sending bonus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Bonus Sent"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     * )
     */

    public function send_bonus_for_other_user(Request $request){
        $rules=array(
            'user_id' => 'required',
            'bonus' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }
        if (auth()->user()->balance < $request->bonus){
            return response()->json([
               'status' => false,
               'message' => 'You Not have Many in balance'
            ],422);
        }
        $get_user = User::where('id', $request->user_id)->first();
        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message'  => 'Wrong User ID'
            ],422);
        }
        auth()->user()->update([
           'balance' => auth()->user()->balance - $request->bonus
        ]);
        $get_user->update([
           'balance' => $get_user->balance + $request->bonus
        ]);
        BalanceHistory::create([
            'user_id' => auth()->user()->id,
            'sender_id' => auth()->user()->id,
            'receiver_id' => $get_user->id,
            'message' => "Regalo de",
            'type' =>  'P2P_tranzaction',
            'price' =>  $request->bonus

        ]);
        return response()->json([
           'status' => true,
           'message' => 'Bonus Send ed'
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/get_users_for_send_bonus",
     *     summary="Get users for sending a bonus",
     *     tags={"Balance"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="search", type="string", example="John Doe"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with users list for sending bonus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */

    public function get_users_for_send_bonus(Request $request){
        $get_respest = Respecto::where('sender_id',auth()->user()->id)->get('receiver_id')->pluck('receiver_id')->toarray();
        $get_respests = Respecto::where('receiver_id',auth()->user()->id)->get('sender_id')->pluck('sender_id')->toarray();


        $get_users = User::query();    


        $string = $request->search;
        if(isset($string)){
            $keyword =$string;
            $name_parts = explode(" ", $keyword);
            foreach ($name_parts as $part) {
                $get_users->orWhere(function ($query) use ($part) {
                    $query->where('name', 'like', "%{$part}%")
                        ->orwhere('surname', 'like', "%{$part}%")
                    ;
                });
            }
        }



       $arr =   array_merge($get_respest, $get_respests);

        $get  = $get_users->wherein('id', $arr)->simplepaginate(10);


        return response()->json([
           'status' => true,
           'data' => $get
        ],200);
    }


}
