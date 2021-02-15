<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use JWTAuth;

class ScheduleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.jwt', ['except' => ['pesquisarAgendamentoBarber','pesquisarAgendamentoBarberShop','Isvago']]);
    }

    public function Isvago($array){
        $flag = false;
        foreach($array['horarios'] as $tem){
            if($tem == 0)$flag = true;
        }
        return $flag;
    }

    public function pesquisarAgendamentoBarber(Request $request,$id){
        date_default_timezone_set('America/Sao_Paulo');
        $anoAtual    = date("Y");

        $mesAtual    = date("m");

        $diaAtual    = date("d");

        $horaAtual   = date("H");

        $minutoAtual = date("i");

        $ultimoDia       = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);

        $contadorDias    = 0;

        $primeiroDiaPosX = date("w",mktime(0,0,0,$request->month,1,$request->year));

        for( $i = 0 ; $i < 6 ; $i++ ){
            for( $cont = 0 ; $cont < 7 ; $cont++){
                $array[$i][$cont]['dia'] = 0;
            }
            for( $j = 0 ; $j < 7 ; $j++){
                $contadorDias++;
                if($contadorDias <= $ultimoDia){
                    if($i == 0){
                        if($j >= $primeiroDiaPosX){
                            $array[$i][$j]['dia'] = $contadorDias;
                            $contHorarios = 0;

                            if(($request->year > $anoAtual) || ($request->year == $anoAtual && $request->month > $mesAtual) || ($request->year == $anoAtual && $request->month == $mesAtual && $contadorDias >= $diaAtual) ){
                                for($hora = 8 ; $hora < 18 ; $hora++){
                                    for($minuto = 0; $minuto < 2 ; $minuto++){
                                        if($minuto == 0){
                                                if($hora < 10)$horario = "0$hora:00";
                                                else $horario = "$hora:00";
                                        }
                                        else{
                                                if($hora < 10)$horario = "0$hora:30";
                                                else $horario = "$hora:30";
                                        }

                                        $pode = true;

                                        if($contadorDias == $diaAtual && $request->year == $anoAtual && $request->month == $mesAtual){
                                            $valoresHorario = explode(':',$horario);
                                            foreach($valoresHorario as $key=>$valor){
                                                if($key == 0)$horaValor = $valor;
                                                else $minutoValor = $valor;
                                            }
                                            $tempo = ( $horaValor * 60 ) + $minutoValor;
                                            $tempoAtual =  ( $horaAtual * 60 ) + $minutoAtual;
                                            if($tempo >= $tempoAtual)$pode = true;
                                            else $pode = false;
                                        }
                                        if($pode){
                                            $data        = "$request->year-$request->month-$contadorDias $horario";
                                            $agendamento = Schedule::where('barber_id',$id)->where('date','=',$data)->where('status','confirmed')->get();
                                            if($agendamento->count() > 0)$array[$i][$j]['horarios'][$contHorarios] = 1;
                                            else $array[$i][$j]['horarios'][$contHorarios] = 0;
                                        }
                                        else $array[$i][$j]['horarios'][$contHorarios] = 1;
                                        $contHorarios++;
                                    }
                                }
                                $array[$i][$j]['isVago'] = $this->Isvago($array[$i][$j]);
                            }
                            else $array[$i][$j]['isVago'] = false;
                        }
                        else {
                            $contadorDias--;
                            $array[$i][$j]['dia'] = 0;
                        }
                    }
                    else{
                        $array[$i][$j]['dia'] = $contadorDias;
                        $contHorarios = 0;

                        if(($request->year > $anoAtual) || ($request->year == $anoAtual && $request->month > $mesAtual) || ($request->year == $anoAtual && $request->month == $mesAtual && $contadorDias >= $diaAtual) )
                        {

                            for($hora = 8 ; $hora < 18 ; $hora++){
                                for($minuto = 0; $minuto < 2 ; $minuto++){
                                    if($minuto == 0){
                                            if($hora < 10)$horario = "0$hora:00";
                                            else $horario = "$hora:00";
                                    }
                                    else{
                                            if($hora < 10)$horario = "0$hora:30";
                                            else $horario = "$hora:30";
                                    }
                                    $pode = true;

                                    if($contadorDias == $diaAtual && $request->year == $anoAtual && $request->month == $mesAtual){
                                        $valoresHorario = explode(':',$horario);
                                        foreach($valoresHorario as $key=>$valor){
                                            if($key == 0)$horaValor = $valor;
                                            else $minutoValor = $valor;
                                        }
                                        $tempo      = ( $horaValor * 60 ) + $minutoValor;
                                        $tempoAtual =  ( $horaAtual * 60 ) + $minutoAtual;
                                        if($tempo >= $tempoAtual){

                                            $pode = true;

                                        }
                                        else $pode = false;

                                    }
                                    if($pode){
                                        $data        = "$request->year-$request->month-$contadorDias $horario";
                                        $agendamento = Schedule::where('barber_id',$id)->where('date','=',$data)->get();
                                        if($agendamento->count() > 0)$array[$i][$j]['horarios'][$contHorarios] = 1;
                                        else $array[$i][$j]['horarios'][$contHorarios] = 0;
                                    }
                                    else $array[$i][$j]['horarios'][$contHorarios] = 1;

                                    $contHorarios++;
                                }
                            }
                            $array[$i][$j]['isVago'] = $this->Isvago($array[$i][$j]);
                        }
                        else $array[$i][$j]['isVago'] = false;
                    }
                }
            }
        }

        return response()->json($array);


    }

    public function pesquisarAgendamentoBarberShop(Request $request,$id){
        $proxMes      = $request->mes + 1;
        $dataFinal    = "$request->year-$proxMes-01";
        $dataInicial  = "$request->year-$request->mes-01";

        $agendamentos = Schedule::where('barbershop_id',$id)->where('date','>=',$dataInicial)->where('date','<',$dataFinal)->get();

        return response()->json($agendamentos);
    }


    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->type == 'barberShop')$schedules = Schedule::where('barbershop_id',$user->id)->with('barber.barbershop','client')->get();
        if($user->type == 'client')$schedules = Schedule::where('client_id',$user->id)->with('barber.barbershop','client')->get();
        return response()->json($schedules);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->type == 'client'){
            $schedule = new Schedule();
            $scheduleData = $request->all();
            $schedule->fill($scheduleData);
            $schedule->status = 'waiting';
            $schedule->client_id = $user->id;
            $schedule->save();

            return response()->json($schedule);
        }
        return response()->json(['success'=>false,'message'=>'Apenas Clients podem marcar agendamentos!']);
    }

    public function show($id)
    {

    }

    public function update(Request $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->type == 'client'){
            $schedule = Schedule::find($id)->where('client_id',$id)->get();
            if($schedule){
                $schedule->status = "confirmed";
                $schedule->save();
                return response()->json($schedule);
            }
            else return response()->json(['success'=>false,'message'=>"Agendamento não existe"]);
        }
        return response()->json(['success'=>false,'message'=>'Apenas Clients podem marcar agendamentos!']);
    }

    public function destroy($id)
    {
        //
    }
}
