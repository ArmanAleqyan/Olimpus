<?php

namespace App\Http\Controllers\Api\Forgot;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Mail\ForgotEmail;

class ForgotController extends Controller
{

    /**
     * @OA\Post(
     *     path="api/send_code_for_forgot_password",
     *     operationId="sendForgotPasswordCode",
     *     tags={"Forgot"},
     *     summary="Send verification code for password reset",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verification code sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Code sent to your email"),
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
     *         description="Wrong email or email not verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong Email or Email not verified"),
     *         ),
     *     ),
     * )
     */
    public function send_code_for_forgot_password(Request $request){
        $rules=array(
            'email' => [
                'required',
                'email',
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
        $get_user = User::where('email', $request->email)->first();
        if ($get_user == null || $get_user->email_verify_code != 1){
            return response()->json([
               'status' => false,
               'message' => 'wrong email'
            ],422);
        }

        $random = random_int(100000, 999999);
        $details = [
            'email' => $request->email ,
            'name' => $get_user->name,
            'code' => $random
        ];
        try{
            \Mail::to($request->email)->send(new ForgotEmail($details));
        }catch (\Exception $e){
            return response()->json([
               'status' => false,
               'message' => 'Sorry i have Email error'
            ],422);
        }
        $get_user->update([
            'email_forgot_code' => $random
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Code Send ed your email'  ,
            'code' => $random
        ],200);

    }
    /**
     * @OA\Post(
     *     path="api/validation_forgot_password_code",
     *     operationId="validateForgotPasswordCode",
     *     tags={"Forgot"},
     *     summary="Validate verification code for password reset",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="code", type="string", example="123456"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Code validated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Code Valid"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"email": {"The email field is required."}, "code": {"The code field is required."}}),
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
     * )
     */
    public function validation_forgot_password_code(Request $request){
        $rules=array(
            'email' => [
                'required',
                'email',
            ],
            'code' => 'required'
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }
        $get_user = User::where('email', $request->email)->where('email_forgot_code', $request->code)->first();
        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Code'
            ],422);
        } else{
          return response()->json([
              'status' => true,
              'message' => 'Code Valid'
          ],200);
        }
    }

    /**
     * @OA\Post(
     *     path="api/update_password_for_forgot_password",
     *     operationId="updateForgotPassword",
     *     tags={"Forgot"},
     *     summary="Update password after verifying code",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="code", type="string", example="123456"),
     *             @OA\Property(property="password", type="string", example="newsecretpassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="newsecretpassword"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Password Updated"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"email": {"The email field is required."}, "code": {"The code field is required."}, "password": {"The password field is required."}}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Wrong email or code provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong Code"),
     *         ),
     *     ),
     * )
     */

    public function update_password_for_forgot_password(Request $request){
        $rules=array(
            'email' => [
                'required',
                'email',
            ],
            'code' => 'required',
            'password' => 'max:254|required',
            'password_confirmation' => 'required|same:password|max:254',
        );
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'message' =>$validator->errors()
            ],400);
        }
        $get_user = User::where('email', $request->email)->where('email_forgot_code', $request->code)->first();
        if ($get_user == null){
            return  response()->json([
               'status' => false,
               'message' => 'Wrong Code'
            ],422);
        }
        $get_user->update([
          'password' => Hash::make($request->password),
            'email_forgot_code' => null
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Password Updated'
        ],200);
    }
}
