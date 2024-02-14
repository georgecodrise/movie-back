<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $reserva = new Reserva();
        $id = $request->id;
        $cant = $request->cantTicket;

        $reserva->cartelera_id = $request->id;
        $reserva->cant_asientos = $request->cantTicket;
        
        $total = DB::table('salas')->select('asientos_total')->where('id',$id)->first();
        $total_asientos = $total->asientos_total;

        $actual = DB::table('salas')->select('asientos_reservados')->where('id',$id)->first();
        $num = $actual->asientos_reservados; //asientos reservados
        $sum = $cant + $num; //suma cantidad reserva + asientos reservados
        $disponibles = $total_asientos - $num;

        if(  $total_asientos >= $sum ){
            $reserva->save();
            $sala_upd = DB::table('salas')->where('id',$id)->update(['asientos_reservados'=>$sum]);
        }else{

            return response([
               
                'errors' => [
                  "No hay asientos disponibles para esta función",
                  "Asientos disponibles: ".$disponibles],

                
             ],422);
        }

       return[ 
            'reserva'=>$reserva,
            'cant_upd'=>$sala_upd,
       ];


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
