<?php

namespace App\Http\Controllers;

use App\Models\Entres;
use App\Models\Sorties;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\StatistiquesService;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;



class SortiesController extends Controller
{
    private function getTypes(): array
    {
        return [
            'motorcycle' => ['label' => 'Moto',     'color' => '#3b82f6', 'tarif' => 100],
            'car'        => ['label' => 'Voiture',  'color' => '#22c55e', 'tarif' => 500],
            'tricycle'   => ['label' => 'Tricycle', 'color' => '#ec4899', 'tarif' => 250],
            'nyonyovi'   => ['label' => 'Nyonyovi', 'color' => '#0ea5e9', 'tarif' => 250],
            'minibus'    => ['label' => 'Minibus',  'color' => '#eab308', 'tarif' => 300],
            'bus'        => ['label' => 'Bus',      'color' => '#f97316', 'tarif' => 700],
            'truck'      => ['label' => 'Camion',   'color' => '#8b5cf6', 'tarif' => 700],
        ];
    }

    private function getTarifs(): array
    {
        return collect($this->getTypes())->mapWithKeys(fn($item, $key) => [$key => $item['tarif']])->toArray();
    }

    public function testDb()
{
    $users = DB::table('users')->get();
    
}
public function ticket($id)
{
    $sortie = Sorties::findOrFail($id);
    $entree = Entres::where('plaque', $sortie->plaque)->latest()->first();

    if (!$entree) {
        abort(404, 'Entrée non trouvée');
    }

    // Tarifs par type de véhicule
    $tarifs = [
        'motorcycle' => 100,
        'car'        => 200,
        'tricycle'   => 150,
        'nyonyovi'   => 150,
        'minibus'    => 300,
        'bus'        => 400,
        'truck'      => 500,
    ];

    // Calcul du nombre de jours passés
    $joursPasses = $entree->created_at->diffInDays($sortie->created_at) + 1;
    
    $type = $entree->type;
    $montant = ($tarifs[$type] ?? 0) * $joursPasses;

    $pdf = Pdf::loadView('ticket-sortie', compact('entree', 'sortie', 'joursPasses', 'montant'))
        ->setPaper('A6', 'portrait');

    return $pdf->download('ticket-sortie.pdf');
}





    private function genererStatistiques($periode, $date = null)
    {
        $now = $date ? Carbon::parse($date) : now();

        if ($periode === 'semaine' && preg_match('/^\d{4}-W\d{2}$/', $date)) {
            [$year, $weekNumber] = explode('-W', $date);
            $now = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
        }

        switch ($periode) {
            case 'semaine':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            case 'mois':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'année':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            default:
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
        }

        $types = $this->getTypes();
        $circumference = 2 * pi() * 80;
        $offset = 0;

        $tableData = [];
        $pieSegments = [];
        $barSegments = [];

        $totalEntrees = Entres::whereBetween('created_at', [$start, $end])->count();
        $totalSorties = 0;
        $revenusTotaux = 0;
        $revenuMax = 0;

        foreach ($types as $type => $info) {
            $entrees = Entres::where('type', $type)->whereBetween('created_at', [$start, $end])->count();
            $sorties = Sorties::where('type', $type)->whereBetween('created_at', [$start, $end])->count();
            $revenu = Sorties::where('type', $type)->whereBetween('created_at', [$start, $end])->sum('montant');
            $tarifMoyen = $sorties > 0 ? round($revenu / $sorties, 2) : 0;

            $tableData[] = [
                'label'   => $info['label'],
                'entrees' => $entrees,
                'sorties' => $sorties,
                'tarif'   => $tarifMoyen,
                'revenu'  => $revenu,
                'color'   => $info['color'],
            ];

            $percent = $totalEntrees > 0 ? ($entrees / $totalEntrees) : 0;
            $length = $percent * $circumference;

            $pieSegments[] = [
                'label'      => $info['label'],
                'color'      => $info['color'],
                'dasharray'  => round($length, 2) . ' ' . round($circumference - $length, 2),
                'dashoffset' => -round($offset, 2),
                'percent'    => round($percent * 100, 1),
            ];

            $barSegments[] = [
                'label'  => $info['label'],
                'revenu' => $revenu,
                'color'  => $info['color'],
            ];

            $offset += $length;
            $totalSorties += $sorties;
            $revenusTotaux += $revenu;
            $revenuMax = max($revenuMax, $revenu);
        }

        $tarifMoyenTotal = $totalSorties > 0 ? round($revenusTotaux / $totalSorties, 2) : 0;

        return [
            'tableData'        => $tableData,
            'segments'         => $pieSegments,
            'revenuSegments'   => $barSegments,
            'revenuMax'        => $revenuMax,
            'totalEntrees'     => $totalEntrees,
            'totalSorties'     => $totalSorties,
            'revenusTotaux'    => $revenusTotaux,
            'tarifMoyenTotal'  => $tarifMoyenTotal,
        ];
    }



public function statistiques(Request $request)
{
    $date = $request->input('date', now()->format('Y-m-d'));
    

        $daily= $this->genererStatistiques('jour', $date);
        $weekly  = $this->genererStatistiques('semaine', $date);
        $monthly = $this->genererStatistiques('mois', $date);
        $yearly  = $this->genererStatistiques('année', $date);
    // Présents actuels et comparaison à 1h
    $now = now();
    $oneHourAgo = $now->copy()->subHour();

    $currentEntrants = Entres::count();
    $currentSorties = Sorties::count();
    $currentPresent = $currentEntrants - $currentSorties;

    $entrantsBefore = Entres::where('created_at', '<=', $oneHourAgo)->count();
    $sortiesBefore = Sorties::where('created_at', '<=', $oneHourAgo)->count();
    $presentOneHourAgo = $entrantsBefore - $sortiesBefore;

    $diff = $currentPresent - $presentOneHourAgo;
    $diffFormatted = $diff > 0 ? "+{$diff}" : (string) $diff;

    // Capacité et occupation
    $capaciteTotale = 150;
    $placesOccupées =  $currentPresent;
    $tauxOccupation = $capaciteTotale > 0 ? round(($placesOccupées / $capaciteTotale) * 100, 1) : 0;

    // Autres stats générales
    $totalEngins = $currentEntrants;
    $revenuJour = Sorties::whereDate('created_at', today())->sum('montant');
    
    $topDailyEntry = Entres::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderByDesc('total')
        ->first();

    return view('dashboard', [
        // Stats période
        'dailyTableData'         => $daily['tableData'],
        'dailyPieSegments'       => $daily['segments'],
        'dailyRevenusSegments'   => $daily['revenuSegments'],
        'dailyRevenuMax'         => $daily['revenuMax'],
        'dailyTotalEntrees'      => $daily['totalEntrees'],
        'dailyTotalSorties'      => $daily['totalSorties'],
        'dailyRevenusTotaux'     => $daily['revenusTotaux'],
        'dailyTarifMoyenTotal'   => $daily['tarifMoyenTotal'],

        'weeklyTableData'        => $weekly['tableData'],
        'weeklyPieSegments'      => $weekly['segments'],
        'weeklyRevenusSegments'  => $weekly['revenuSegments'],
        'weeklyRevenuMax'        => $weekly['revenuMax'],
        'weeklyTotalEntrees'     => $weekly['totalEntrees'],
        'weeklyTotalSorties'     => $weekly['totalSorties'],
        'weeklyRevenusTotaux'    => $weekly['revenusTotaux'],
        'weeklyTarifMoyenTotal'  => $weekly['tarifMoyenTotal'],

        'monthlyTableData'       => $monthly['tableData'],
        'monthlyPieSegments'     => $monthly['segments'],
        'monthlyRevenusSegments' => $monthly['revenuSegments'],
        'monthlyRevenuMax'       => $monthly['revenuMax'],
        'monthlyTotalEntrees'    => $monthly['totalEntrees'],
        'monthlyTotalSorties'    => $monthly['totalSorties'],
        'monthlyRevenusTotaux'   => $monthly['revenusTotaux'],
        'monthlyTarifMoyenTotal' => $monthly['tarifMoyenTotal'],

        'yearlyTableData'        => $yearly['tableData'],
        'yearlyPieSegments'      => $yearly['segments'],
        'yearlyRevenusSegments'  => $yearly['revenuSegments'],
        'yearlyRevenuMax'        => $yearly['revenuMax'],
        'yearlyTotalEntrees'     => $yearly['totalEntrees'],
        'yearlyTotalSorties'     => $yearly['totalSorties'],
        'yearlyRevenusTotaux'    => $yearly['revenusTotaux'],
        'yearlyTarifMoyenTotal'  => $yearly['tarifMoyenTotal'],

        // Dashboard global
        'topDailyEntry'          => $topDailyEntry,
        'places_disponibles'     => $capaciteTotale - $placesOccupées,
        'places_occupees'        => $placesOccupées,
        'evolution_1h'           => $diffFormatted,
        'evolution_occupees_1h'  => $diff,
        'taux_occupation'        => $tauxOccupation,
        'totalEngins'            => $totalEngins,
        'revenuJour'             => $revenuJour,
    ]);
}


    public function create(Request $request)
    {
        $types = $this->getTypes();
        $tarifs = $this->getTarifs();

        $plaque = $request->input('plaque');
        $entree = null;
        $dernierType = null;
        $montant = null;
        $joursPasses = null;

        if ($plaque) {
            $entree = Entres::where('plaque', $plaque)->latest()->first();

            if ($entree) {
                $dernierType = $entree->type;
                $joursPasses = Carbon::parse($entree->created_at)->diffInDays(Carbon::now()) + 1;
                $montant = $tarifs[$dernierType] * $joursPasses;
            }
        }

        return view('sorties', compact('types', 'entree', 'dernierType', 'montant', 'plaque', 'joursPasses'));
    }
public function store(Request $request)
{
    $types = $this->getTypes();

    $validated = $request->validate([
        'plaque'      => 'required|string|exists:entres,plaque',
        'type'        => 'required|in:' . implode(',', array_keys($types)),
        'paiement'    => 'required|in:cash,card,app',
        'paiement_ok' => ['nullable', 'accepted'],
    ]);

    $entree = Entres::where('plaque', $validated['plaque'])->latest()->first();

    if (!$entree) {
        return back()->withErrors(['plaque' => 'Aucune entrée trouvée pour cette plaque.']);
    }

    $sortieExistante = Sorties::where('plaque', $validated['plaque'])
        ->where('created_at', '>=', $entree->created_at)
        ->exists();

    if ($sortieExistante) {
        return back()->withErrors(['plaque' => 'Ce véhicule est déjà sorti après sa dernière entrée.']);
    }

    $joursPasses = Carbon::parse($entree->created_at)->diffInDays(Carbon::now()) + 1;
    $tarifJournalier = $types[$validated['type']]['tarif'];
    $montantTotal = $joursPasses * $tarifJournalier;

    $sortie = Sorties::create([
        'owner_name'  => $entree->name,
        'owner_phone' => $entree->phone,
        'plaque'      => $validated['plaque'],
        'type'        => $validated['type'],
        'montant'     => $montantTotal,
        'paiement'    => $validated['paiement'],
        'paiement_ok' => $request->has('paiement_ok'),
    ]);

    // QR Code dynamique
    $qrData = "Véhicule : {$validated['plaque']}\nType : {$validated['type']}\nMontant : {$montantTotal} FCFA\nDate sortie : " . now()->format('d/m/Y H:i');
   $qrCode = QrCode::format('svg')->size(150)->generate($qrData);


    // Redirection vers une page ticket PDF avec données
    return redirect()->route('sorti-ticket', [
        'id'     => $sortie->id,
        'qrCode' => $qrCode,
    ]);
}




public function exportJour(Request $request)
{
    $date = $request->input('date', now()->toDateString());
    $daily = $this->genererStatistiques('jour', $date);

    $pdf = Pdf::loadView('exports.jour', [
        'date'                 => $date,
        'dailyTableData'       => $daily['tableData'],
        'dailyPieSegments'     => $daily['segments'],
        'dailyRevenusSegments' => $daily['revenuSegments'],
        'dailyRevenuMax'       => $daily['revenuMax'],
        'dailyTotalEntrees'    => $daily['totalEntrees'],
        'dailyTotalSorties'    => $daily['totalSorties'],
        'dailyRevenusTotaux'   => $daily['revenusTotaux'],
        'dailyTarifMoyenTotal' => $daily['tarifMoyenTotal'],
    ]);

    return $pdf->download("statistiques_jour_{$date}.pdf");
}



public function exportSemaine(Request $request)
{
    $weekInput = $request->input('week', now()->format('o-\WW')); // format "2025-W30"
    [$year, $weekNumber] = explode('-W', $weekInput);

    $week = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();

    $weekly = $this->genererStatistiques('semaine', $week);

    // Prépare les données pour la vue PDF
    $pdf = Pdf::loadView('exports.semaine', [
        'week'                   => $week,
        'weeklyTableData'        => $weekly['tableData'],
        'weeklyPieSegments'      => $weekly['segments'],
        'weeklyRevenusSegments'  => $weekly['revenuSegments'],
        'weeklyRevenuMax'        => $weekly['revenuMax'],
        'weeklyTotalEntrees'     => $weekly['totalEntrees'],
        'weeklyTotalSorties'     => $weekly['totalSorties'],
        'weeklyRevenusTotaux'    => $weekly['revenusTotaux'],
        'weeklyTarifMoyenTotal'  => $weekly['tarifMoyenTotal'],
    ]);
    

    // Télécharge le fichier PDF avec nom : statistiques_semaine_2025-W30.pdf
    $filename = 'statistiques_semaine_' . $week->format('o-\WW') . '.pdf';

    return $pdf->download($filename);
}


public function exportMois(Request $request)
{
    $month = $request->input('month', now()->toDateString());
    $monthly = $this->genererStatistiques('mois', $month);

    $pdf = Pdf::loadView('exports.mois', [
        'month'                   => $month,
        'monthlyTableData'       => $monthly['tableData'],
        'monthlyPieSegments'     => $monthly['segments'],
        'monthlyRevenusSegments' => $monthly['revenuSegments'],
        'monthlyRevenuMax'       => $monthly['revenuMax'],
        'monthlyTotalEntrees'    => $monthly['totalEntrees'],
        'monthlyTotalSorties'    => $monthly['totalSorties'],
        'monthlyRevenusTotaux'   => $monthly['revenusTotaux'],
        'monthlyTarifMoyenTotal' => $monthly['tarifMoyenTotal'],
    ]);

    return $pdf->download("statistiques_mois_{$month}.pdf");
}

public function exportAnnee(Request $request)
{
    $year = $request->input('year', now()->toDateString());
    $yearly = $this->genererStatistiques('année', $year);

    $pdf = Pdf::loadView('exports.année', [
        'year'                  => $year,
        'yearlyTableData'       => $yearly['tableData'],
        'yearlyPieSegments'     => $yearly['segments'],
        'yearlyRevenusSegments' => $yearly['revenuSegments'],
        'yearlyRevenuMax'       => $yearly['revenuMax'],
        'yearlyTotalEntrees'    => $yearly['totalEntrees'],
        'yearlyTotalSorties'    => $yearly['totalSorties'],
        'yearlyRevenusTotaux'   => $yearly['revenusTotaux'],
        'yearlyTarifMoyenTotal' => $yearly['tarifMoyenTotal'],
    ]);
    



    return $pdf->download("statistiques_année_{$year}.pdf");
}


public function downloadTicket($id)
{
    $sortie = Sorties::findOrFail($id);
    $pdf = Pdf::loadView('ticket-sortie', compact('sortie'));
    return $pdf->download('ticket.pdf');
}


}