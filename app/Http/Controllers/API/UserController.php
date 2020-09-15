<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use JWTAuth;
use App\Transformers\UserTransformer;

class UserController extends Controller
{

    public $successStatus = 200;

    public function register(Request $request)
    {        
        $input = $request->all();

        $this->validator();

        $content = [
            'email' => $input['email'],
            'password' => $input['password']
        ];

        $input['password'] = Hash::make($input['password']);

        \DB::transaction(function() use ($input, $content) {
            User::create($input);
        });

        return response()->json(['result'=>true]);
    }

    public function login(Request $request)
    {
        $input = $request->all();

        if (!$token = JWTAuth::attempt($input)) 
        {
            abort(422);
        }

        return response()->json(['result' => $token]);
    }

    /**
     * auth user details api
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate()->toArray();

        return \JsonSerializer::item('user', $user, new UserTransformer);
    }

    public function update(Request $request)
    {
        $user = User::find(JWTAuth::parseToken()->authenticate()->toArray()['id']);
        
        $input = $request->all();

        if (isset($input['password']))
            $input['password'] = bcrypt($input['password']);
        
        $user->update($input);

        return $this->me();
    }

    protected function validator()
    {
        $rules = [
              'name'                  => 'required|max:255',
              'email'                 => 'required|email|max:255|unique:users',
              'password'              => 'required|confirmed|min:6',
              'password_confirmation' => 'min:6'
        ];

        $payload = app('request')->only('name', 'email', 'password', 'password_confirmation');

        $validator = app('validator')->make($payload, $rules);

        if ($validator->fails()) {
            abort(403, $validator->errors());
        }
    } 
}