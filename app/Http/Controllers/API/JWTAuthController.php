<?php

namespace App\Http\Controllers\API;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
class JWTAuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use ApiResponsetrait;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|between:2,100',
            'email' => 'required|email|unique:users|max:50',
            'password' => 'required|string|min:6',//confirmed
        ]);

        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));

        // return response()->json([
        //     'message' => 'Successfully registered',
        //     'user' => $user
        // ], 201);
        $response = $this->apiRespone($user, 'Successfully registered', 201);
        return $response;
    }
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }


    public function profile()
    {
        return response()->json(auth()->user());
    }

    
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }


    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }
}



/************ */

    // namespace App\Http\Controllers;

    // use App\User;
    // use Illuminate\Http\Request;
    // use Illuminate\Support\Facades\Hash;
    // use Illuminate\Support\Facades\Validator;
    // use JWTAuth;
    // use Tymon\JWTAuth\Exceptions\JWTException;

    // class UserController extends Controller
    // {
    //     public function authenticate(Request $request)
    //     {
    //         $credentials = $request->only('email', 'password');

    //         try {
    //             if (! $token = JWTAuth::attempt($credentials)) {
    //                 return response()->json(['error' => 'invalid_credentials'], 400);
    //             }
    //         } catch (JWTException $e) {
    //             return response()->json(['error' => 'could_not_create_token'], 500);
    //         }

    //         return response()->json(compact('token'));
    //     }

    //     public function register(Request $request)
    //     {
    //             $validator = Validator::make($request->all(), [
    //             'name' => 'required|string|max:255',
    //             'email' => 'required|string|email|max:255|unique:users',
    //             'password' => 'required|string|min:6|confirmed',
    //         ]);

    //         if($validator->fails()){
    //                 return response()->json($validator->errors()->toJson(), 400);
    //         }

    //         $user = User::create([
    //             'name' => $request->get('name'),
    //             'email' => $request->get('email'),
    //             'password' => Hash::make($request->get('password')),
    //         ]);

    //         $token = JWTAuth::fromUser($user);

    //         return response()->json(compact('user','token'),201);
    //     }

    //     public function getAuthenticatedUser()
    //         {
    //                 try {

    //                         if (! $user = JWTAuth::parseToken()->authenticate()) {
    //                                 return response()->json(['user_not_found'], 404);
    //                         }

    //                 } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

    //                         return response()->json(['token_expired'], $e->getStatusCode());

    //                 } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

    //                         return response()->json(['token_invalid'], $e->getStatusCode());

    //                 } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

    //                         return response()->json(['token_absent'], $e->getStatusCode());

    //                 }

    //                 return response()->json(compact('user'));
    //         }
    // }




    // namespace App\Http\Controllers;

    // use Illuminate\Http\Request;

    // class DataController extends Controller
    // {
    //         public function open() 
    //         {
    //             $data = "This data is open and can be accessed without the client being authenticated";
    //             return response()->json(compact('data'),200);

    //         }

    //         public function closed() 
    //         {
    //             $data = "Only authorized users can see this";
    //             return response()->json(compact('data'),200);
    //         }
    // }

