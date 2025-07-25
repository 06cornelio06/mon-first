<?php

namespace App\Http\Controllers;

use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str; 

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EntresController extends Controller
{
    public function create()
    {
        return view('entres'); // Vue du formulaire
    }

 public function store(Request $request)
{
    // Validation
    $validated = $request->validate([
        'plaque' => 'required|string|max:255',
        'type' => 'required|string',
        'name' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:20',
    ]);

    // Vérifier la dernière entrée de ce véhicule
    $lastEntry = Entres::where('plaque', $validated['plaque'])
                        ->where('type', $validated['type'])
                        ->latest()
                        ->first();

    if ($lastEntry) {
        $hasExit = Sorties::where('plaque', $validated['plaque'])
                          ->where('created_at', '>=', $lastEntry->created_at)
                          ->exists();

        if (!$hasExit) {
            return back()->withErrors(['plaque' => 'Ce véhicule est déjà présent dans le parking.'])->withInput();
        }

        if (empty($validated['name'])) {
            $validated['name'] = $lastEntry->name;
        }
        if (empty($validated['phone'])) {
            $validated['phone'] = $lastEntry->phone;
        }
    }

    // Créer une nouvelle entrée
    $entree = Entres::create([
        'plaque' => $validated['plaque'],
        'type'   => $validated['type'],
        'name'   => $validated['name'],
        'phone'  => $validated['phone'],
    ]);

    // ✅ GÉNÉRER LE PDF AVANT DE L’UTILISER
    $pdf = Pdf::loadView('ticket-entree', compact('entree')) ->setPaper('A6', 'portrait');

    // ✅ RENVOYER LE PDF À TÉLÉCHARGER
 return $pdf->stream('ticket.pdf'); // 
}


}