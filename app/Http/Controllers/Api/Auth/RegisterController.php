<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Validator;
use  App\Mail\RegisterMail;


class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="surname", type="string", example="Doe"),
     *             @OA\Property(property="phone", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="secretpassword"),
     *             @OA\Property(property="password_confirmation", type="string", example="secretpassword"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Code sent to your email"),
     *             @OA\Property(property="code", type="integer", example=123456),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"name": {"The name field is required."}, "email": {"The email field is required."}}),
     *         ),
     *     ),
     * )
     */
    public function register(Request $request){
        $rules=array(
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'phone' => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('email_verify_code', 1);
                }),
            ],
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


        $random = random_int(100000, 999999);


        $details = [
            'email' => $request->email ,
            'name' => $request->name,
            'code' => $random
        ];

        \Mail::to($request->email)->send(new RegisterMail($details));
        User::updateOrCreate(['email' => $request->email],[
            'email' => $request->email,
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'email_verify_code' => $random
        ]);
        return response()->json([
           'status' => true,
           'message' => 'Code Send your email',
            'code' => $random
        ],200);

    }

    /**
     * @OA\Post(
     *     path="api/resend_code_for_register",
     *     operationId="resendVerificationCode",
     *     tags={"Authentication"},
     *     summary="Resend verification code for registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Verification code resent successfully",
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
     *         description="Wrong email provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong Email"),
     *         ),
     *     ),
     * )
     */

    public function resend_code_for_register(Request $request){
        $rules=array(
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('email_verify_code', 1);
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

        $get_user = User::where('email', $request->email)->first();

        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Email'
            ],422);
        }
        $random = random_int(100000, 999999);
        $details = [
            'email' => $request->email ,
            'name' => $get_user->name,
            'code' => $random
        ];
        try{
            \Mail::to($request->email)->send(new RegisterMail($details));
        }catch (\Exception $e){
            return response()->json([
               'status' => false,
               'message' => 'Sorry I have Email error'
            ],422);
        }
        $get_user->update([
           'email_verify_code' => $random
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Code Send your email',
            'code' => $random
        ],200);
    }

    /**
     * @OA\Post(
     *     path="api/validation_register_code",
     *     operationId="validateRegistrationCode",
     *     tags={"Authentication"},
     *     summary="Validate registration code and log in the user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="string", example="123456"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User validated and logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in"),
     *             @OA\Property(property="user", type="object", ref="User Data"),
     *             @OA\Property(property="token", type="string", example="access_token"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"code": {"The code field is required."}}),
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
    public function validation_register_code(Request $request){
        $rules=array(
            'code' => 'required',
            'email' => [
                'required',
                'email',
                'max:254',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('email_verify_code', 1);
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
        $get_user = User::where('email', $request->email) -> where('email_verify_code', $request->code)->first();
        if ($get_user == null){
            return response()->json([
               'status' => false,
               'message' => 'Wrong Code'
            ],422);
        }
        $get_user->update([
           'email_verify_code' => 1
        ]);
        Auth::login($get_user);
        $token = $get_user->createToken('Laravel Password Grant Client')->accessToken;
        return response()->json([
           'status' => true,
           'message' => 'User Login ed',
            'user' => $get_user,
            'token' => $token,
        ],200);
    }

    /**
     * @OA\Post(
     *     path="api/login",
     *     operationId="userLogin",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="secretpassword"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User logged in"),
     *             @OA\Property(property="user", type="object", ref="User Data"),
     *             @OA\Property(property="token", type="string", example="access_token"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation errors or missing fields",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="object", example={"email": {"The email field is required."}, "password": {"The password field is required."}}),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized: Wrong email, password, or email not verified",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Wrong Email Or Password"),
     *         ),
     *     ),
     * )
     */

    public function login(Request $request){
        $rules=array(
            'password' => 'required',
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
        $get_user  = User::where('email', $request->email)->first();
        $data = [
          'email' => $request->email,
          'password' => $request->password
        ];
         $login = Auth::attempt($data);
         if ($login == false || $get_user == null || $get_user->email_verify_code != 1){
             return response()->json([
                'status' => false,
                'message' => 'Wrong Email Or Password'
             ],401);
         }
        $token = $get_user->createToken('Laravel Password Grant Client')->accessToken;
         return response()->json([
            'status' => true,
            'message' => 'User Login ed',
            'user' => $get_user,
            'token' => $token,
         ],200);

    }
}
