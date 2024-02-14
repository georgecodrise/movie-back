<?php

namespace App\Http\Controllers;

use App\Models\Cartelera;
use App\Rules\TakedMovie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarteleraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartelera = DB::table('carteleras')
                         ->join('movies','movies.id','=','carteleras.movie_id')
                         ->join('salas','salas.id','=','carteleras.sala_id')
                         ->select('carteleras.id','movies.name as pelicula','salas.name as sala',
                                  'carteleras.fecha','carteleras.hora')
                         ->get();

        return $cartelera;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cartelera = new Cartelera();

        $cartelera->movie_id = $request-> movie;
        $cartelera->sala_id = $request-> sala;
        $cartelera->fecha = $request-> date;
        $cartelera->hora = $request-> time;
        $cartelera->save();

        return ['cartelera'=>$cartelera];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cartelera = DB::table('carteleras')
                         ->join('movies','movies.id','=','carteleras.movie_id')
                         ->join('salas','salas.id','=','carteleras.sala_id')
                         ->select('carteleras.id','movies.name as pelicula','salas.name as sala',
                                  'carteleras.fecha','carteleras.hora')
                         ->where('carteleras.id',$id)
                         ->get();

        return $cartelera;
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
