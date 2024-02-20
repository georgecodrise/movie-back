<?php

namespace App\Http\Controllers;

use App\Models\Cartelera;
use App\Rules\TakedMovie;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarteleraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $start= now()->format('Y-m-d');
        $end = now()->format('Y-m-d');

        $cartelera = DB::table('carteleras')
                         ->join('movies','movies.id','=','carteleras.movie_id')
                         ->join('salas','salas.id','=','carteleras.sala_id')
                         ->select('carteleras.id','movies.name as pelicula','salas.name as sala',
                                  'carteleras.inicio','carteleras.estado')
                         ->selectRaw('(carteleras.asientos - carteleras.asientos_reservados ) as disponibles ')
                         ->whereBetween('inicio',[$start.' 07:00:00' ,$end.' 23:59:59'])
                         ->get();

        return $cartelera;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $inicio = $request->inicio;
        $fin = $request->fin;
        $sala = $request->sala;

        $db_asientos = DB::table('salas')->select('asientos_total')->where('id','=',$sala)->first();
        $asientos= $db_asientos->asientos_total;

        $cartelera = new Cartelera();

        $cartelera->movie_id = $request-> movie;
        $cartelera->sala_id = $request-> sala;
        $cartelera->asientos = $asientos;
        $cartelera->inicio = $request-> inicio;
        $cartelera->fin = $request-> fin;
        
        $count=DB::table('carteleras')
        // ->whereBetween('inicio',[$inicio,$fin])
        // ->orWhereBetween('fin',[$inicio,$fin])
        ->whereRaw("(inicio BETWEEN '$inicio' AND '$fin' OR fin BETWEEN '$inicio' AND '$fin')")
        ->where('sala_id','=',$sala)
        ->count();
         
        try {

            if($count == 0){

                $cartelera->save();  
                return [ 'message'=>'Se registró el evento' ];

             }else{

                 return response([
                     'errors' => ['Sala ocupada en ese horario']
                 ],422);
             }

        } catch (\Throwable $th) {
             return response([

                'errors' => [
                    'Ah ocurrido un error en el servidor!',
                    $th
                ]
            ],422);
        }

        
        //return $asientos;
        
        //return ['cartelera'=>$cartelera];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cartelera = DB::table('carteleras')
                         ->join('movies','movies.id','=','carteleras.movie_id')
                         ->join('salas','salas.id','=','carteleras.sala_id')
                         ->select('carteleras.id','carteleras.movie_id','movies.name as pelicula','carteleras.sala_id','salas.name as sala',
                                  'carteleras.inicio','carteleras.fin')
                         ->where('carteleras.id',$id)
                         ->get();

        return $cartelera;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cartelera $cartelera)
    {
        try {
            //code...
            $inicio = $request->inicio;
            $fin = $request->fin;
            $sala = $request->sala;

            $count=DB::table('carteleras')
            ->whereRaw("(inicio BETWEEN '$inicio' AND '$fin' OR fin BETWEEN '$inicio' AND '$fin')")
            ->where('sala_id','=',$sala)
            ->count();

            $cartelera->movie_id = $request->pelicula;
            $cartelera->sala_id = $request->sala;
            $cartelera->inicio = $request->inicio;
            $cartelera->fin = $request->fin;

            //return $request;

            try {
                //code...

                if($count == 0){

                    $cartelera->save();  
                    return [ 'message'=>'Se actualizó correctamente' ];
    
                 }else{
    
                     return response([
                         'errors' => ['Sala ocupada en ese horario']
                     ],422);
                 }

            } catch (\Throwable $th) {

                return response([
                    'errors' => [
                        $th
                    ]
                ],422);
            }
            

        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'errors' => [
                    $th
                ]
            ],422);
            
        }

    }

    public function estado(Request $request, Cartelera $cartelera, string $id){

        $db_estado = DB::table('carteleras')->select('estado')->where('id','=',$id)->first();
        $estado = $db_estado->estado;
        

        if($estado){
            DB::table('carteleras')->where('id','=',$id)->update(['estado'=>0]);
        }else{
            DB::table('carteleras')->where('id','=',$id)->update(['estado'=>1]);
        }

        return ['message'=>'Actualizado' ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            //code...

            DB::table('carteleras')->where('id',$id)->delete();
            
            return [
                'message'=>'Se eliminó la función correctamente.'
            ];

        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'errors' => [
                    $th
                ]
            ],422);
            
        }

    }
}
