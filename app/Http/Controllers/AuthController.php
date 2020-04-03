<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Support\Facades\Validator;
use DB, Mail;
use DateTime;
use JWTAuth;
use Exception;
use Illuminate\Mail\Message;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['signup', 'login']]);
    }

    public function signup(RegistrationFormRequest $request)
    {
      try {
      $user = User::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'username' => $request->username,
        'email' => $request->email,
        'phoneNumber' => $request->phoneNumber,
        'idNumber' => $request->idNumber,
        'gender' => $request->gender,
        'password' => bcrypt($request->password),
        'role' => $request->role,
      ]);

      $token = auth()->login($user);
      $verification_code = substr($token,0,50);
      DB::table('user_verifications')->insert([
        'user_id' => $user->id,
        'token' => $verification_code
      ]);
      
      $firstname = $request->firstName;
      $lastname = $request->lastname;
      $email = $request->email;

      Mail::send('email.verify', ['firstName'=> $firstname, 
      'lastName'=> $lastname, 'verification_code'=>$verification_code], function($mail) use ($email) {
        $mail->from('markjoker73@gmail.com', 'verify email');
        $mail->to($email);
        $mail->subject("Please verify your email");
      });
      return response()->json([
        'message' => 'user successfully registed',
        'token' => $this->respondWithToken($token),
        'data' => $user
      ], Response::HTTP_CREATED);
    } catch (Exception $ex) {
      return response()->json([
        'error' => $ex->getMessage()
      ], Response::HTTP_BAD_REQUEST);
    }
    }

    public function verifyUser($verification_code){
      $check=DB::table('user_verifications')->where('token',$verification_code)->first();
      if(!is_null($check)){
        $user=User::find($check->user_id);

        if($user->is_verified === 1){
          return response()->json([
            'success'=>true,
            'message'=>'Account already verified'
            ]);
        }

        $user->update(['is_verified'=>1]);
        $dt = new DateTime();
        $user->update(['email_verified_at'=> $dt->format('Y-m-d H:i:s')]);
        DB::table('user_verifications')->where('token',$verification_code)->delete();
        return response()->json([
          'success'=>true,
          'message'=>'you have successfully verified your email address'
        ], Response::HTTP_OK);
      }

      return response()->json([
        'status'=>false,
        'error'=>'verification code is invalid!!'
      ], Response::HTTP_BAD_REQUEST);
    }


    public function login(LoginFormRequest $request)
    {
      $username = $request->username;
      $field = filter_var($username, FILTER_VALIDATE_EMAIL)? 'email': 'username';
      $credentials = [
        $field => $username,
        'password' => $request->password
      ];
      if (!$token = auth()->attempt($credentials)) {
        return response()->json([
          'error' => 'Please check if your username or email and password are true'
        ], Response::HTTP_UNAUTHORIZED);
      }
      return response()->json([
        'message' => 'user successfully logged in',
        'token' => $this->respondWithToken($token)
      ], Response::HTTP_OK);
    }
    public function getAuthUser(Request $request)
    {
        return auth()->user();
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {

      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer'
      ]);
    }
}
