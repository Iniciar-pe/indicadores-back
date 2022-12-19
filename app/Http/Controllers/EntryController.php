<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Entry;

class EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function get(Request $request)
    {
        return response()->json([
            'status' => '200',
            'entry' => Entry::orderBy('orden', 'asc')->get(),
        ], 200);
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'mnemonic' => 'required|string|max:255',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $rubro = $this->incrementingEntry();

        $entry = Entry::create([
            'id_rubro' => $rubro ? $rubro->id_rubro + 1 : '1',
            'descripcion' => $request->get('description'),
            'nemonico' => $request->get('mnemonic'),
            'estado' => $request->get('status'),
            'edita_pp' => $request->get('edita_pp'),
            'edita_pa' => $request->get('edita_pa'),
            'notas' => $request->get('note'),
            'orden' => $request->get('orden'),

        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Registration registered correctly',
        ], 200);

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|max:255',
            'mnemonic' => 'required|string|max:255',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $entry = Entry::find($request->get('id'));
        $entry->descripcion = $request->get('description');
        $entry->nemonico = $request->get('mnemonic');
        $entry->estado = $request->get('status');
        $entry->edita_pp = $request->get('edita_pp');
        $entry->edita_pa = $request->get('edita_pa');
        $entry->notas = $request->get('notas');
        $entry->orden = $request->get('orden');
        $entry->save();

        return response()->json([
            'status' => '200',
            'message' => 'Updated record correctly',
        ], 200);

    }

    public function delete(Request $request)
    {
        $entry = Entry::where('id_rubro', $request->get('id'))->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Record deleted correctly',
        ], 200);
    }

    private function incrementingEntry()
    {
        return Entry::orderBy('id_rubro', 'desc')->first();
    }
}
