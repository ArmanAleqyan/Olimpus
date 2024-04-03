<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Respecto;
use App\Models\Event;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * @OA\Get(
     *     path="/single_page_user/{id}",
     *     operationId="singlePageUserAction",
     *     tags={"User Management"},
     *     summary="Get detailed information about a single user",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the user",
     *         @OA\Schema(type="integer"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object", ref="data"),
     *             @OA\Property(property="receiver_respecto_count", type="integer", example=10, description="Number of received respects"),
     *             @OA\Property(property="sender_respect_count", type="integer", example=5, description="Number of sent respects"),
     *             @OA\Property(property="events_count", type="integer", example=3, description="Number of events participated"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong user Id"),
     *         ),
     *     ),
     * )
     */
    public function single_page_user($id){

        $get_user = User::where('id', $id)->with('city')->first();


        if ($get_user == null || $id == null){
            return response()->json([
               'status' => false,
               'message' => 'wrong user Id'
            ],422);
        }

        $get_receiver_respect_count = Respecto::where('receiver_id',  $id)->count();
        $get_sender_respect_count = Respecto::where('sender_id',  $id)->count();
        $events = Event::where('user_id',  $id)->count();

        return response()->json([
           'status' => true,
           'data' => $get_user,
            'receiver_respecto_count' =>$get_receiver_respect_count,
            'sender_respect_count' =>$get_sender_respect_count,
            'events_count' => $events
        ]);
    }
}
