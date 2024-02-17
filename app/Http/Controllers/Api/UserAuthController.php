<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserAuthController extends Controller
{
    
    /**
     * The register function in PHP validates user input, creates a new user with the provided
     * information, and returns a response with a token if successful.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data and information about the incoming request,
     * such as the request method, headers, query parameters, form data, and more. In this code
     * snippet, the  parameter is used to retrieve
     * 
     * @return a JSON response. If the validation fails, it returns a JSON response with status false,
     * message 'validation error', and the validation errors. If the user is created successfully, it
     * returns a JSON response with status true, message 'User Created Successfully', and the user's
     * API token. If an exception occurs, it returns a JSON response with status false and the
     * exception message.
     */
    public function register(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'role' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    
    /**
     * The above function is a PHP code snippet for handling user login, including validation,
     * authentication, and generating an API token.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data and information about the incoming request,
     * such as the request method, headers, query parameters, form data, and more. In this code
     * snippet, the  parameter is used to retrieve
     * 
     * @return a JSON response. If the validation fails, it returns a JSON response with status false,
     * a message of 'validation error', and the validation errors. If the authentication attempt fails,
     * it returns a JSON response with status false and a message of 'Email & Password does not match
     * with our record.'. If the authentication is successful, it returns a JSON response with status
     * true, a message
     */
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'role' => $user->role
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    /**
     * The above function logs out the user by deleting all their tokens and returns a JSON response
     * with a "logged out" message.
     * 
     * @return a JSON response with a message "logged out".
     */
    public function logout(){
        auth()->user()->tokens()->delete();
    
        return response()->json([
          "message"=>"logged out"
        ]);
    }


}