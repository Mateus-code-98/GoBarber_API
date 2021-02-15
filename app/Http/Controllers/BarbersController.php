<?php

namespace App\Http\Controllers;

use App\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use JWTAuth;

class BarbersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.jwt');
    }

    public function uploadAvatarBarber(Request $request,$id){
        $barber   = Barber::find($id);
        $heCanSee = $this->ConfereID($barber->barbershop_id);
        if($heCanSee){
            if($barber->path_avatar !== null){
                Storage::disk('public')->delete($barber->path_avatar);
            }
            $barber->path_avatar = $request->file('path_avatar')->store("avatares/barber");
            $barber->save();
            return response()->json($barber);
        }
        else return response()->json(['success'=>false,'message'=> 'Usuário não autorizado'],401);
    }

    public function ConfereID($id){
        $user = JWTAuth::parseToken()->authenticate();
        if($user->id === $id)return $user;
        return false;
    }

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();

        $barbers = Barber::where('barbershop_id',$user->id)->with('barbershop')->get();

        return response()->json($barbers);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $barberData = $request->all();
        if($user->type === 'barberShop')
        {
            $barber = new Barber();
            $barber->fill($barberData);
            $barber->barbershop_id = $user->id;
            $barber->save();

            return response()->json($barber);
        }
        else return response()->json(['success'=>false,'message'=>'Apenas Barbershops podem adicionar Barbers!']);

    }

    public function show($id)
    {
        $barber   = Barber::find($id);

        $heCanSee = $this->ConfereID($barber->barbershop_id);

        if($heCanSee)return response()->json($barber);

        return response()->json(['success'=>false,'message'=> 'Usuário não autorizado'],401);
    }

    public function update(Request $request, $id)
    {
        $barber   = Barber::find($id);

        $heCanSee = $this->ConfereID($barber->barbershop_id);

        if($heCanSee)
        {
            $barberData = $request->all();
            $barber->fill($barberData);
            $barber->save();
            return response()->json($barber);
        }

        return response()->json(['success'=>false,'message'=> 'Usuário não autorizado'],401);
    }

    public function destroy($id)
    {
        $barber   = Barber::find($id);

        $heCanSee = $this->ConfereID($barber->barbershop_id);

        if($heCanSee)
        {
            $barber->delete();
            return response()->json($barber);
        }

        return response()->json(['success'=>false,'message'=> 'Usuário não autorizado'],401);
    }
}
