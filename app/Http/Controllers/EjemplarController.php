<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//use App\Http\Requests\EjemplarRequest;

use App\Ejemplar;
use App\Libro;
use App\Autor;
use App\Genero;
use App\Estado;
use App\Usuario;

class EjemplarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ejemplares = Ejemplar::all();
        $datos = array();
        $libroDatos = array();
        $cont = 0;

        foreach ($ejemplares as $ejemplar) {
            $usuario = Usuario::find($ejemplar->usuario_id);
            $estado = Estado::find($ejemplar->estado_id);
            $libro = Libro::find($ejemplar->libro_id);
            $genero = Genero::find($libro->genero_id);
            $autor = Autor::find($libro->autor_id);


            $libroDatos['id'] = $libro->id;
            $libroDatos['titulo'] = $libro->titulo;
            $libroDatos['autor'] = $autor;
            $libroDatos['genero'] = $genero;

            $datos[$cont]['id'] = $ejemplar->id;
            $datos[$cont]['fecha_prestamo'] = $ejemplar->fecha_prestamo;
            $datos[$cont]['fecha_devolucion'] = $ejemplar->fecha_devolucion;
            $datos[$cont]['libro'] = $libroDatos;
            $datos[$cont]['estado'] = $estado;
            $datos[$cont]['usuario'] = $usuario;

            $cont++;
        }

        return $datos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $ejemplar = Ejemplar::create($request->all());
        if(!isset($ejemplar)){
            $datos = [
            'errors'=>true,
            'msg'=>'No se creo un ejemplar'
            ];
            $ejemplar = \Response::json($datos, 404);
        }

        return $ejemplar;
        

        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ejemplar = Ejemplar::find($id);
        $datos = array();
        $libroDatos = array();

        $usuario = Usuario::find($ejemplar->usuario_id);
        $estado = Estado::find($ejemplar->estado_id);
        $libro = Libro::find($ejemplar->libro_id);
        $genero = Genero::find($libro->genero_id);
        $autor = Autor::find($libro->autor_id);


        $libroDatos['id'] = $libro->id;
        $libroDatos['titulo'] = $libro->titulo;
        $libroDatos['autor'] = $autor;
        $libroDatos['genero'] = $genero;

        $datos['id'] = $ejemplar->id;
        $datos['fecha_prestamo'] = $ejemplar->fecha_prestamo;
        $datos['fecha_devolucion'] = $ejemplar->fecha_devolucion;
        $datos['libro'] = $libroDatos;
        $datos['estado'] = $estado;
        $datos['usuario'] = $usuario;

        return $datos;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $ejemplar = Ejemplar::find($id); 
        $ejemplar->fill($request->all());
        $ejemplarRetorno = $ejemplar->save();
        
        if (isset($ejemplar)) {
            $ejemplar = \Response::json($ejemplarRetorno, 200);
        } else {
           $ejemplar= \Response::json(['error' => 'No se ha podido actualizar la pelicula'], 404);
       }
       return $ejemplar;
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ejemplar = Ejemplar::find($id); 
        if ($ejemplar->delete()) {  
            $ejemplar = \Response::json(['delete' => true, 'id' => $id], 200);
        } else {
           $ejemplar = \Response::json(['error' => 'No se ha podido eliminar la pelicula'], 403);
        }
        
        return $ejemplar;
    }
}
