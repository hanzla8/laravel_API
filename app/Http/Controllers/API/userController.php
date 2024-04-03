<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class userController extends Controller
{

    // Create Function USER
    public function createUser(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name'      => "required|string",
            'email'     => "required|string|unique:users",
            'phone'     => "required|numeric:digits:11",
            'password'  => "required|min:6"
        ]);
        if($validator->fails()) {
            $result = array('status' => false, 'message' => "Validation error Occured", 'error_message' => $validator->errors());
            return response()->json($result, 400); //bed request
        } 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt( $request->password ),
        ]);
        if($user->id) {
            $result = array('status' => true, 'message' => "USER CREATED", "data" => $user);
            $responceCode = 200; //Success
        } else {
            $result = array('status' => false, 'message' => "Something want Wrong");
            $responceCode = 400; //Bad Request
        }
        return response()->json($result, $responceCode);
    }



    public function getUsers()
    {
        // ye try or is main baqi sara controller ka code,  or pir catch main extenshion or phir os ke bad curly bracket main $result ko bhej kr os main error ko pass krenge, ye hamare Error handling ka kaam ko snbhalti hai. {{ yahni ke try + catch block ham error handling ke liye use krte hai}}
        try{    
            $users = User::all();

            $result = array('status' => true, 'message' => count($users). "user's fetched", "data" => $users);
            $responceCode = 200; //Success

            return response()->json($result, $responceCode);
        } catch(Exception $e) {
            $result = array('status' => false, 'message' => "API field due to an error", 
             "error"=> $e->getMessage());
            return response()->json($result, 500);

        }

    }
    

    // Get User Detail + ID
    public function getUsersDetails($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['status' => false, 'message' => "User Not Found"], 404 );
        }

        $result = array('status' => true, 'message' =>  "Your indiviual user Found", "data" => $user);
        $responceCode = 200; //Success

        return response()->json($result, $responceCode);

    }


    // Update USer Here
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['status' => false, 'message' => "User Not Found"], 404 );
        }
        // Validation
        $validator = Validator::make($request->all(), [
            'name'      => "required|string",
            'email'     => "required|string|unique:users,email,".$id,
            'phone'     => "required|numeric|digits:11",
        ]);
        if($validator->fails()) 
        {
            $result = array('status' => false, 'message' => "Validation error Occured", 'error_message' => $validator->errors());
            return response()->json($result, 400); //bed request
        } 
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->save();

        $result = array('status' => true, 'message' =>  "User has been updated successfuly", "data" => $user);
        return response()->json($result, 200); //good request
    }


     // Update USer Here
    public function deleteUser($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(['status' => false, 'message' => "User Not Found"], 404 );
        }

        $user->delete();

        $result = array('status' => true, 'message' =>  "User has been deleted successfuly");
        return response()->json($result, 200); //good request
            
    }

    // LOgin Function
    public function login(Request $request) {
         
        $validator = Validator::make($request->all(), [
            'email'     => "required",
            'password'     => "required"
        ]);
        if($validator->fails()) 
        {
            return response()->json(['status' => false, 'message' => "Validation Error occured", 'errors' => $validator->error()], 400 );
        } 

        $credentials = $request->only("emails", "password");

        if(Auth::attempt($credentials)) {
            $user = Auth::user();
            return response()->json(['status' => true, 'message' => "Login Successfully", "data" => $user], 200 );
        }
        else{

        }
        return response()->json(['status' => false, 'message' => "invalid Login credential"], 401 );



    }
}
