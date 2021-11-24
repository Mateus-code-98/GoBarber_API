<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JWTAuth;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['store', 'showBarberShops', 'showOnlyBarberShop']]);
    }

    public function ConfereID($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->id === $id) return $user;
        return false;
    }

    public function showBarberShops()
    {
        $users = User::where('type', 'barbershop')->with('barbers')->get();
        return response()->json($users);
    }

    public function showOnlyBarberShop($id)
    {
        $users = User::where('type', 'barbershop')->where('id', $id)->with('barbers')->first();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $user     = new User();
        $userData = $request->all();
        $user->fill($userData);
        $user->password = bcrypt($user->password);
        $user->save();
        return response()->json($user);
    }

    public function uploadAvatarUsers(Request $request, $id)
    {
            $user = $this->ConfereID($id);
            if ($user) {
                $usuario = User::find($id);
                if ($usuario->path_avatar !== null) {
                    Storage::disk('public')->delete($usuario->path_avatar);
                }
                $file_name = "avatares/$usuario->type/" . 'image' . time() . '.png';
                $usuario->path_avatar = $file_name;

                Storage::disk("public")->put($file_name, $request->path_avatar);
                $usuario->save();
                return response()->json($usuario);
            }
            return response()->json(['success' => false, 'message' => 'Usuário não autorizado'], 401);
    }

    public function show($id)
    {
        $user = $this->ConfereID($id);
        if ($user) {
            $usuario = User::find($id);
            return response()->json($usuario);
        }
        return response()->json(['success' => false, 'message' => 'Usuário não autorizado'], 401);
    }

    public function update(Request $request, $id)
    {
        $user = $this->ConfereID($id);
        if ($user) {
            $userData = $request->all();
            $usuario  = User::find($id);
            $usuario->fill($userData);
            $usuario->save();
            return response()->json($usuario);
        }
        return response()->json(['success' => false, 'message' => 'Usuário não autorizado'], 401);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = $this->ConfereID($id);
        if ($user) {
            $usuario = User::find($id);
            $usuario->password = bcrypt($request->password);
            $usuario->save();
            return response()->json($usuario);
        }
        return response()->json(['success' => false, 'message' => 'Usuário não autorizado'], 401);
    }

    public function destroy($id)
    {
        $user = $this->ConfereID($id);
        if ($user) {
            $usuario = User::find($id);
            $usuario->delete();
            return response()->json($usuario);
        }
        return response()->json(['success' => false, 'message' => 'Usuário não autorizado'], 401);
    }
}
