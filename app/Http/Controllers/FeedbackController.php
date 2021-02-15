<?php

namespace App\Http\Controllers;

use App\Events\NewSchedule;
use App\Feedback;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use JWTAuth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.jwt',['except' => ['ShowAllFeedbacksBarberShop','clicouBotao']]);
    }

    public function index()
    {

    }

    public function ShowAllFeedbacksBarberShop($id){
        $feedbacks = Feedback::where('barbershop_id',$id)->with('client')->orderBy('created_at', 'desc')->paginate('5');
        $user      = User::select(['users.score',
                                   'users.stars_1',
                                   'users.stars_2',
                                   'users.stars_3',
                                   'users.stars_4',
                                   'users.stars_5'])->find($id);


        foreach($feedbacks as $key=>$value)$res['feedbacks'][$key] = $value;

        $stars['stars_1']     = $user->stars_1;
        $stars['stars_2']     = $user->stars_2;
        $stars['stars_3']     = $user->stars_3;
        $stars['stars_4']     = $user->stars_4;
        $stars['stars_5']     = $user->stars_5;
        unset($feedbacks['0']);
        unset($feedbacks['1']);
        unset($feedbacks['2']);
        unset($feedbacks['3']);
        unset($feedbacks['4']);
        $feedbacks['feedbacks']   = $res['feedbacks'];
        $feedbacks['score'] = $user->score;
        $feedbacks['stars'] = $stars;
        return response()->json($feedbacks);
    }

    public function store(Request $request,$id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->type === 'client'){
            $barbershop = User::find($id);
            $feedbacks  = Feedback::where('barbershop_id',$id)->get();

            if( $request->score >= 0 && $request->score <= 1 )$barbershop->stars_1 = $barbershop->stars_1 + 1;
            else if( $request->score > 1 && $request->score <= 2 )$barbershop->stars_2 = $barbershop->stars_2 + 1;
            else if( $request->score > 2 && $request->score <= 3 )$barbershop->stars_3 = $barbershop->stars_3 + 1;
            else if( $request->score > 3 && $request->score <= 4 )$barbershop->stars_4 = $barbershop->stars_4 + 1;
            else if( $request->score > 4 && $request->score <= 5 )$barbershop->stars_5 = $barbershop->stars_5 + 1;

            $totalScoreAnterior = $barbershop->score * $feedbacks->count();
            $novoScore          = ($totalScoreAnterior + $request->score)/($feedbacks->count() + 1 );

            $barbershop->score = $novoScore;
            $barbershop->save();

            $novoFeedback = new Feedback();
            $inputData    = $request->all();
            $novoFeedback->fill($inputData);
            $novoFeedback->barbershop_id = $id;
            $novoFeedback->client_id     = $user->id;
            $novoFeedback->save();

            Event::dispatch(new NewSchedule($novoFeedback));

            return response()->json($novoFeedback);
        }
        return response()->json(['success'=>false,'message'=>'Apenas Clients podem marcar agendamentos!']);

    }

    public function clicouBotao(){
        return response()->json(['message'=>'funfou']);
    }


    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {

    }
}
