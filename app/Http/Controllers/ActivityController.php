<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entres;
use App\Models\Sorties;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityController extends Controller
{
    public function activites(Request $request)
    {
        $plaqueRecherchee = $request->input('plaque');
        $typeRecherche    = $request->input('type');
        $periode          = $request->input('periode');

        $entrees = Entres::query();
        $sorties = Sorties::query();

        // Filtres : plaque
        if ($plaqueRecherchee) {
            $entrees->where('plaque', $plaqueRecherchee);
            $sorties->where('plaque', $plaqueRecherchee);
        }

        // Filtres : type
        if ($typeRecherche) {
            $entrees->where('type', $typeRecherche);
        }

        // Filtres : période
        if ($periode === 'jour') {
            $entrees->whereDate('created_at', Carbon::today());
            $sorties->whereDate('created_at', Carbon::today());
        } elseif ($periode === 'semaine') {
            $entrees->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $sorties->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($periode === 'mois') {
            $entrees->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            $sorties->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
        } elseif ($periode === 'année') {
            $entrees->whereYear('created_at', Carbon::now()->year);
            $sorties->whereYear('created_at', Carbon::now()->year);
        }

        // Récupération des données
        $entrees = $entrees->get();
        $sortiesGrouped = $sorties->get()->groupBy('plaque');

        $activites = collect();

        foreach ($entrees as $entree) {
            $heureEntree = Carbon::parse($entree->created_at);
            $sortie = optional($sortiesGrouped[$entree->plaque] ?? collect())
                ->firstWhere('created_at', '>', $entree->created_at);
            $heureSortie = $sortie ? Carbon::parse($sortie->created_at) : null;

            $activites->push([
                'plaque'    => $entree->plaque,
                'evenement' => $sortie ? 'Sortie' : 'Entrée',
                'name'      => $entree->name ?? '-',
                'type'      => ucfirst($entree->type),
                'date'      => $heureEntree->format('d/m/Y'),
                'entree'    => $heureEntree->format('H:i'),
                'sortie'    => $heureSortie ? $heureSortie->format('H:i') : '-',
                'duree'     => $heureSortie ? $heureEntree->diff($heureSortie)->format('%hh %imin') : '-',
                'statut'    => $sortie ? 'Complété' : 'En attente',
            ]);
        }

        // Tri décroissant par date+heure
        $activites = $activites->sortByDesc(fn ($a) => $a['date'] . $a['entree'])->values();

        // Pagination manuelle (10 par page)
        $page = $request->input('page', 1);
        $perPage = 10;
        $paginated = new LengthAwarePaginator(
            $activites->forPage($page, $perPage),
            $activites->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('recent', [
            'activites'          => $paginated,
            'plaqueRecherchee'   => $plaqueRecherchee,
            'typeRecherche'      => $typeRecherche,
            'periode'            => $periode,
        ]);
    }
}
