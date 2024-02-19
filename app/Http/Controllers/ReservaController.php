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
        
        DB::table('revervas')
            ->join('carteleras','carteleras.id','=','reservas.cartelera_id')
            ->select('reservas.id','')
            ->get(); 

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $id = $request->id;
        $cant = $request->cantTicket;
        
        $reserva = new Reserva();
        $reserva->cartelera_id = $request->id;
        $reserva->cant_asientos = $request->cantTicket;

        try {

        $total = DB::table('carteleras')
                     ->select('asientos')
                     ->where('id',$id)
                     ->first();
                    
        $total_asientos = $total->asientos;

        $actual = DB::table('carteleras')
                      ->select('asientos_reservados')
                      ->where('id',$id)
                      ->first();      
        
        if ($actual===null){
            $num=0;
        }else{      
            $num = $actual->asientos_reservados; //asientos reservados
        }

        $sum = $cant + $num; //suma cantidad reserva + asientos reservados
        $disponibles = $total_asientos - $num;
        
            
        if(  $total_asientos >= $sum ){
            $reserva->save();
            $sala_upd = DB::table('carteleras')->where('id',$id)->update(['asientos_reservados'=>$sum]);
        }else{    
            return response([
               
                'errors' => [
                  "No hay asientos disponibles para esta función",
                  "Asientos disponibles: ".$disponibles],
             ],422);
        }
            
        } catch (\Throwable $th) {
            return response([
                'errors' => [
                    'Ah ocurrido un error en el servidor! NO se proceso la solicitud',
                    $th
                ]
            ],422);
        }
        
        

        
        
        
        
        
       return $total_asientos;
       
    //    return[ 
    //         'message' => 'Correcto'
    //    ];
       

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
