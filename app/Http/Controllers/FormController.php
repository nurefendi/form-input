<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Forminput;

class FormController extends Controller
{
    public function showForm()
    {
        return view('form');
    }

    public function submitForm(Request $request)
    {
        $validatedData = $request->validate([
            'berat_basah' => 'required|numeric|min:0',
            'drc' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        Forminput::create([
            'berat_basah' => $validatedData['berat_basah'],
            'drc' => $validatedData['drc'],
            'keterangan' => $validatedData['keterangan'],
        ]);

        return redirect()->back()->with('success', 'Form berhasil disubmit!');
    }
    public function findById($id)
    {
        $data = Forminput::select('id', 'berat_basah', 'drc', 'keterangan', 'created_at', 'updated_at')
            ->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $data['berat_kering'] = $this->calculateDryWeight($data['berat_basah'], $data['drc']);
        return response()->json($data);
    }

    public function getAll()
    {
        $data = Forminput::select('id', 'berat_basah', 'drc', 'keterangan', 'created_at', 'updated_at')
            ->orderBy('created_at', 'desc')
            ->get();

        $data->transform(function ($item) {
            $item['berat_kering'] = $this->calculateDryWeight($item['berat_basah'], $item['drc']);
            return $item;
        });
        return response()->json($data);
    }
    public function destroy($id)
    {
        $data = Forminput::find($id);
        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }
        $data->delete();
        return response()->json(['message' => 'Data deleted successfully']);
    }
    private function calculateDryWeight($beratBasah, $drc)
    {
        //Rumus Berat Kering = Berat Basah x DRC / 100
        $dryWeight = $beratBasah * ($drc / 100);
        return $dryWeight;
    }
}
