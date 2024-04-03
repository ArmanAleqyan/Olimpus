<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

use App\Mail\NewEmail;
class ProfileController extends Controller
{

    /**
     * @OA\Post(
     *     path="api/update_user_data",
     *     operationId="updateUserData",
     *     tags={"Profile"},
     *     summary="Update user data",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="birth_day", type="string", example="1990-01-01"),
     *             @OA\Property(property="growth", type="integer", example=175),
     *             @OA\Property(property="weight", type="integer", example=70),
     *             @OA\Property(property="gender", type="string", enum={"male", "female"}),
     *             @OA\Property(property="city_id", type="integer", example=1),
     *             @OA\Property(property="sport_modality", type="string", example="Running"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User data updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Empty data or validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Empty data or validation error"),
     *         ),
     *     ),
     * )
     */
    public function update_user_data(Request $request){
        $user = auth()->user();
        if (isset($request->birth_day)){
            $user->update([
               'birth_day' => Carbon::parse($request->birth_day) ->format('Y-m-d')
            ]);
        }


        if (isset($request->growth)){
            $user->update([
               'growth' => $request->growth
            ]);
        }

        if (isset($request->weight)){
            $user->update([
               'weight' => $request->weight
            ]);
        }


        if (isset($request->gender)){
            $user->update([
               'gender' => $request->gender
            ]);
        }


        if (isset($request->city)){
            $user->update([
               'city_id' => $request->city_id
            ]);
        }
         if (isset($request->sport_modality)){
             $user->update([
                'sport_modality' => $request->sport_modality
             ]);
         }
         if ($request->all() == []){
             return response()->json([
                'status'  => false,
                 'message' => 'empty data'
             ],422);
         }else{
             return response()->json([
                 'status' => true,
                 'message' => 'Updated'
             ],200);
         }
    }

    /**
     * @OA\Post(
     *     path="api/user_add_new_email",
     *     operationId="userAddNewEmail",
     *     tags={"Profile"},
     *     summary="Add a new email address for the user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="newemail@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verification code sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Code sent to your new email"),
     *             @OA\Property(property="code", type="integer", example=123456),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing email",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"email": {"The email field is required."}}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Email already taken or email verification error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email already taken or email verification error"),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function user_add_new_email(Request $request){
        $rules=array(
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('email_verify_code', 1)->where('id', '!=', auth()->user()->id);
                }),
            ]
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }
        $random = random_int(100000, 999999);
        $details = [
            'email' => $request->email ,
            'name' => auth()->user()->name,
            'code' => $random
        ];

        try{
            \Mail::to($request->email)->send(new NewEmail($details));
        }catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Sorry i have Email error'
            ],422);
        }

        auth()->user()->update([
            'email_candidate' => $request->email,
            'email_candidate_code' => $random
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Code Send ed in your new email',
            'code' => $random
        ],200);
    }


    /**
     * @OA\Post(
     *     path="api/validation_new_email_code",
     *     operationId="validationNewEmailCode",
     *     tags={"Profile"},
     *     summary="Validate verification code for new email address",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="123456"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email address validated and updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Updated"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing code",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"code": {"The code field is required."}}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Wrong code provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong Code"),
     *         ),
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function validation_new_email_code(Request $request){
        $rules=array(
            'code' => 'required',

        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }
        if (auth()->user()->email_candidate_code != $request->code){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Code'
            ],422);
        }
        auth()->user()->update([
           'email' => auth()->user()->email_candidate,
            'email_candidate' => null,
            'email_candidate_code' => null
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Updated'
        ],200);
    }

}
