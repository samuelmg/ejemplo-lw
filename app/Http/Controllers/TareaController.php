<?php

namespace App\Http\Controllers;

use App\Mail\ReporteMd;
use App\Mail\TareasPendientes;
use App\Models\Etiqueta;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class TareaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    private $reglasValidacion = [
        'tarea' => 'required|min:5|max:255',
        'descripcion' => ['required', 'min:5'],
        'categoria' => 'required',
    ];


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tareas = Tarea::with('user:id,name,email', 'etiquetas')->withTrashed()->get();
        // $tareas = Auth::user()->tareas;//()->where('categoria', 'Trabajo')->get();
        //  dd($tareas);
        return view('tareas.indexTareas', compact('tareas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Tarea::class);
        

        $etiquetas = Etiqueta::all();
        return view('tareas.formTarea', compact('etiquetas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tarea' => 'required|min:5|max:255',
            'descripcion' => ['required', 'min:5'],
            'categoria' => 'required',
            'etiqueta_id' => 'required',
        ]);

        $request->merge([
            'user_id' => Auth::id(),
        ]);
        $tarea = Tarea::create($request->all());

        $tarea->etiquetas()->attach($request->etiqueta_id);

        // $tarea = new Tarea();
        // $tarea->tarea = $request->tarea;
        // $tarea->descripcion = $request->descripcion;
        // $tarea->categoria = $request->categoria;

        // $user = Auth::user();
        // $user->tareas()->save($tarea);

        

        return redirect('/tarea');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function show(Tarea $tarea)
    {
        $this->authorize('view', $tarea);

        return view('tareas.showTarea', compact('tarea'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarea $tarea)
    {
        Gate::authorize('administra', $tarea);

        $etiquetas = Etiqueta::all();
        return view('tareas.formTarea', compact('tarea', 'etiquetas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarea $tarea)
    {
        Gate::authorize('administra', $tarea);

        $request->validate($this->reglasValidacion);

        Tarea::where('id', $tarea->id)
            ->update($request->except(['_method', '_token', 'etiqueta_id']));

        $tarea->etiquetas()->sync($request->etiqueta_id);

        // $tarea->tarea = $request->tarea;
        // $tarea->descripcion = $request->descripcion;
        // $tarea->categoria = $request->categoria;
        // $tarea->save();

        return redirect('/tarea/' . $tarea->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarea  $tarea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarea $tarea)
    {
        $tarea->delete();
        return redirect('/tarea');
    }

    public function borradoDb($tarea)
    {
        $tarea = Tarea::where('id', $tarea)->withTrashed()->first();
        $tarea->forceDelete();
        return redirect('/tarea');
    }


    public function enviarReporte()
    {
        Mail::to(Auth::user()->email)->send(new ReporteMd());
        return redirect()->back();
    }





    public function enviarTareas()
    {
        Mail::to(Auth::user()->email)->send(new TareasPendientes());
        return redirect()->back();
    }
}
