<?php

namespace App\Http\Controllers\Api\Respecto;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use App\Models\Respecto;
class RespectoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/respect",
     *     operationId="respectAction",
     *     tags={"Respect Management"},
     *     summary="Add or remove respect for a user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="receiver_id", type="integer", example=1, description="ID of the user to show respect to"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respecto action performed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Respecto Added or Respecto Deleted"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"receiver_id": {"The receiver_id field is required."}}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong User Id"),
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

    public function respect(Request $request){
        $rules=array(
            'receiver_id' => 'required',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }

        $get_user  = User::whereid($request->receiver_id)->first();

        if ($get_user == null){
            return response()->json([
               'status' => false,
                'message' => 'Wrong User Id'
            ],422);
        }

        $get_respect = Respecto::where('sender_id', auth()->user()->id)->where('receiver_id', $request->receiver_id)->first();

        if ($get_respect == null){
            Respecto::create([
               'sender_id' => auth()->user()->id,
               'receiver_id' => $request->receiver_id
            ]);


            return response()->json([
               'status' => true,
               'message' => 'Respecto Added'
            ],200);
        }else{
            $get_respect->delete();


            return response()->json([
               'status' => true,
               'message' => 'Respecto Deleted'
            ],200);
        }


    }
}
