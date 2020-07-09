<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $user = User::where('email', $data['email'])->first();
        if($user && Hash::check($data['password'], $user->password)){
            $user->expired_at = (new \DateTime())->format('Y-m-d H:i:s');
            $user->save();
                return response()->json(['token' =>  $user->token], 200);
        }
        return response()->json(['message' => 'Failed Email or Password'], 400);
    }

    public function getUser(Request $request){
            return response(User::with('roles')->where('token', $request->bearerToken())->first()->toJson(), 200);
    }

    public function logout(Request $request){
        $user = User::where('token', $request->bearerToken())->first();
        $user->expired_at = null;
        $user->save();
        response()->json(['token' => null], 200);
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
}
