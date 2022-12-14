<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\forgetPasswordMail;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;


class UserController extends Controller
{

    public function user(Request $request)
    {
        return $request->user();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function register(Request $request)
    {
        // validation

        $validator =  Validator::make($request->all(), [
            'email' => 'required|unique:users',
            'name' => 'required',
            'password' => ['required', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return Response::json([
                'code' => 400,
                'message' => 'failed',
                'data' => $validator->messages(),
            ]);
        } else {
            $name = $request->name;
            $email = $request->email;
            $password = $request->password;

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password)
            ]);

            return Response::json([
                'code' => 200,
                'message' => 'success',
                'data' => $user,
            ]);
        }
    }

    public function login(Request $request)
    {

        $user = User::where('email', $request->email)->first();
        // return $user['email_verified_at'];
        if (!$user || !Hash::check($request->password, $user->password)) {
            return Response::json([
                'code' => 400,
                'messgae' => 'The provided credentials are incorrect.',
                'data' => [],
            ]);
        }

        return Response::json([
            'code' => 200,
            'messgae' => 'success',
            'data' => [
                'token' => $user->createToken('defualt')->plainTextToken,
                'name' => $user->name,
                'email' => $user->email,

            ],
        ]);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        return Response::json([
            'code' => 200,
            'message' => 'success',
            'data' => $user,
        ]);
    }

    public function changePassword(Request $request)
    {

        $newPassword = $request->newPassword;
        // $oldPassword = $request->oldPassword;
        $user = $request->user();

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->update([
                'password' => Hash::make($newPassword),
            ]);
            return Response::json($user);
        } else {
            return Response::json([
                'message' => 'old Password dosent correct'
            ]);
        }

        // $user_id =  $request->user()->id;
        // $user=User::findOrFail($user_id);

    }
    public function changeName(Request $request)
    {
        // $newName = $request->newName;
        // $Password = $request->Password;

        $user = $request->user();

        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'name' =>  $request->newName,
            ]);
            return Response::json($user);
        }
        return Response::json([
            'message' => 'Password dosent correct'
        ]);
    }

    public function forgetPassword(Request $request)
    {

        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        // return Password::RESET_LINK_SENT ;
        //     ? back()->with(['status' => __($status)])
        //     : back()->withErrors(['email' => __($status)]);


        // Mail::to($request->email)->send(new forgetPasswordMail);

        return Response::json([
            'code' => 200,
            // 'reset password' => url(route('resetPassword')),
            'msg' => "succ",
            'data' => [],
        ]);
    }



    public function resetPassword()
    {
        return Response::json([
            'code'=>200,
            'message'=>'success',
            'data'=>[
                'token'=>request()->token,
            ],
            

        ]);

    //     $status = Password::reset(


    //         $request->only('password',),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password)
    //             ]);
     
    //             $user->save();
    //  return 'fghjkl';
    //             return event(new PasswordReset($user));
    //         }
    //     );
    //    return 'ah';
    //     return $status === Password::PASSWORD_RESET
    //                 ? redirect()->route('login')->with('status', __($status))
    //                 : back()->withErrors(['email' => [__($status)]]);


//---------------------------------------------------------------------------------------------------------------------
        // $newPassword = $request->newPassword;

        // $user = User::where('email', $request->email)->first();

        // $user->update([
        //     'password' => Hash::make($newPassword),
        // ]);
        // return Response::json([
        //     'code' => 200,
        //     'message' => 'Password Updated',
        //     'data' => [],
        // ]);
    }

    public function newPassword(Request $request)
    {
        $newPassword=$request->newPassword;
        // $token = Hash::make($request->token);

        $token = Crypt::decryptString($request->token);
        return $token;
        

        $result = DB::Table('password_resets')->select('email')->where('token',$token)->get();  
        // return $result;
        
        return DB::table('password_resets')->get();
        // ->where([
        //   'token' => $request->token
        // ])
        // ->first();
;
    }

    public function sendVerificationEmail(Request $request)
    {

        if ($request->user()->hasVerifiedEmail()) {
            return Response::json([
                'code' => 201,
                'message' => 'Already Verified',
                'data' => [],
            ]);
        }
        $request->user()->sendEmailVerificationNotification();

        return Response::json([
            'code' => 200,
            'message' => 'verification-link-sent',
            'data' => [],
        ]);
    }
    public function verifyEmail(Request $request)
    {
        if (!auth()->check()) {
            auth()->loginUsingId($request->route('id'));
        }

        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return 'success';
    }

    public function sendForgetPasswordEmail()
    {
        # code...
    }
}
