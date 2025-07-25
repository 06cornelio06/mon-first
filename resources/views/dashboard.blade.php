<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Viroscope - Menu Responsive Fullscreen</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              500: '#22c55e',
              600: '#16a34a',
            }
          }
        }
      }
    }
  </script>
  <style>
    
    body {
      font-family: 'Poppins', sans-serif;
    }
    o-pattern {
            background-color: #16a34a;
            background-image: radial-gradient(circle at 25px 25px, rgba(255, 255, 255, 0.2) 2%, transparent 0%), 
                             radial-gradient(circle at 75px 75px, rgba(255, 255, 255, 0.2) 2%, transparent 0%);
            background-size: 100px 100px;
        }
        .parking-spot {
            transition: all 0.3s ease;
        }
        .parking-spot:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .dashboard-card {
            transition: all 0.2s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-3px);
        }
        .status-available {
            background-color: #22c55e;
        }
        .status-occupied {
            background-color: #ef4444;
        }
        .status-reserved {
            background-color: #f59e0b;
        }
        .status-maintenance {
            background-color: #6b7280;
        }
        .form-container {
            transition: all 0.3s ease;
        }
        .form-container:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .modal {
            transition: opacity 0.3s ease;
        }
        .modal-content {
            transition: transform 0.3s ease;
        }
     .tab-link {
  color: #16a34a; /* Texte vert */
  border-color: transparent;
  transition: 0.3s;
}
.tab-link:hover {
  border-color: #16a34a;
}

/* Contenu par défaut caché */
.tab-content {
  display: none;
}

/* Affiche l’onglet sélectionné */
.tab-content:target {
  display: block;
}

/* Onglet actif (bordure verte) */
:target ~ .tabs a[href="#tab-daily"],
:target ~ .tabs a[href="#tab-weekly"],
:target ~ .tabs a[href="#tab-monthly"],
:target ~ .tabs a[href="#tab-yearly"] {
  border-color: transparent;
}
:target ~ .tabs a[href="#tab-daily"]:target,
:target ~ .tabs a[href="#tab-weekly"]:target,
:target ~ .tabs a[href="#tab-monthly"]:target,
:target ~ .tabs a[href="#tab-yearly"]:target {
  border-color: #16a34a;
}

/* --- Onglet 1 actif par défaut --- */
body:not(:has(:target)) #tab-daily {
  display: block;
}
body:not(:has(:target)) .tabs a[href="#tab-daily"] {
  border-color: #16a34a;
}




  
  </style>
</head>
<body class="bg-green-600 text-white">

  <!-- Navigation -->
  <nav class="bg-white shadow-md">
    <div class="flex items-center justify-between px-3 py-3 max-w-7xl mx-auto">
      <div class="flex-shrink-0">
        <img class="h-20 w-auto" src="{{ asset('images/R.png') }}" alt="Viroscope">
      </div>

      <!-- Menu mobile -->
      <div class="md:hidden">
        <input type="checkbox" id="menu-toggle" class="hidden peer" />
        <label for="menu-toggle" class="cursor-pointer z-50 relative">
          <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </label>
        <div class="fixed inset-0 hidden peer-checked:flex flex-col items-center justify-center bg-white z-40 space-y-8 text-xl font-semibold text-gray-800">
          <a href="{{ url('/entres') }}" class="hover:text-green-600">Entrées</a>
          <a href="{{ url('/sorties') }}" class="hover:text-green-600">Sorties</a>
          <a href="{{ url('/dashboard') }}" class="hover:text-green-600">Rapports</a>
          <a href="{{ url('/recent') }}" class="text-green-600">Activités</a>

          @auth
          <div class="pt-6 border-t border-gray-300 w-full max-w-sm text-center">
            <p class="text-base font-medium mt-4">{{ Auth::user()->name }}</p>
            <a href="{{ route('profile.edit') }}" class="block mt-2 hover:text-green-900">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="mt-2 hover:text-red-900">Déconnexion</button>
            </form>
          </div>
          @endauth

          @guest
          <div class="pt-6 border-t border-gray-300 w-full max-w-sm text-center">
            <a href="{{ route('login') }}" class="block text-green-600">Connexion</a>
            <a href="{{ route('register') }}" class="block mt-2 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">S'inscrire</a>
          </div>
          @endguest
        </div>
      </div>

      <!-- Menu desktop -->
      <div class="hidden md:flex flex-1 justify-center space-x-6">
        <a href="{{ url('/entres') }}" class="text-gray-800 hover:text-green-600 font-semibold">Entrées</a>
        <a href="{{ url('/sorties') }}" class="text-gray-800 hover:text-green-600 font-semibold">Sorties</a>
        <a href="{{ url('/dashboard') }}" class="text-gray-800 hover:text-green-600 font-semibold">Rapports</a>
        <a href="{{ url('/recent') }}" class="text-gray-800 text-green-600 font-semibold">Activités</a>
      </div>

      <!-- Auth à droite -->
      <div class="hidden md:flex items-center space-x-4">
        @auth
        <div class="relative group">
          <button class="flex items-center space-x-2 text-gray-800 hover:text-green-600">
            <span>{{ Auth::user()->name }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>
          <ul class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-md invisible group-hover:visible group-hover:translate-y-1 transform transition-all z-50 flex flex-col items-center py-2">
            <li><a href="{{ route('profile.edit') }}" class="block w-full text-center px-4 py-2 hover:bg-gray-100 text-green-900">Profil</a></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-center px-4 py-2 hover:bg-gray-100 text-red-900">Déconnexion</button>
              </form>
            </li>
          </ul>
        </div>
        @endauth

        @guest
        <a href="{{ route('login') }}" class="bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-md">Connexion</a>
        <a href="{{ route('register') }}" class="bg-white text-primary-600 border border-primary-600 hover:bg-primary-50 px-4 py-2 rounded-md">S'inscrire</a>
        @endguest
      </div>
    </div>
  </nav>




<div class="mt-5">
    
  <!-- Dashboard Overview Section -->
<div id="dashboard" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-xl shadow-lg p-6 md:p-8 -mt-16 relative z-10">
        <!-- En-tête tableau de bord -->
        <div class="flex justify-between items-start flex-col md:flex-row mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 md:mb-0">Tableau de bord</h2>
            <div class="flex items-center space-x-2">
                <span class="text-gray-500">Dernière mise à jour:</span>
                <span class="font-medium" id="last-update">Aujourd'hui à 15:30</span>
             <form method="GET" action="{{ route('dashboard') }}">
    <button type="submit" class="p-1 rounded-full hover:bg-gray-100" title="Rafraîchir les données">
        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
    </button>
</form>

            </div>
        </div>

        <!-- Sous-navigation -->
        <div class="sub-nav flex justify-center w-full px-4 py-2 bg-gray-50 rounded-md mb-4 overflow-x-auto">
            <a href="#dash"
                class="subnav-link active px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md mr-2">
                Tableau de bord
            </a>
            <a href="#statistics"
                class="subnav-link px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md mr-2">
                Statistiques des engins
            </a>
         
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Card 1 -->
         <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dashboard-card">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-500">Record journalier</h3>
        <div class="h-8 w-8 bg-primary-100 rounded-full flex items-center justify-center">
            <svg class="h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 17v-2a4 4 0 00-4-4H4m0 0a4 4 0 004-4V5m5 14h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6m0 0V5m0 6h4" />
            </svg>
        </div>
    </div>
    @if($topDailyEntry)
        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $topDailyEntry->total }}</p>
        <div class="mt-1 flex items-center text-sm">
            <span class="text-gray-500">le {{ \Carbon\Carbon::parse($topDailyEntry->date)->format('d/m/Y') }}</span>
        </div>
    @else
        <p class="mt-2 text-3xl font-bold text-gray-400">–</p>
        <div class="mt-1 flex items-center text-sm">
            <span class="text-gray-500">Aucune donnée</span>
        </div>
    @endif
</div>


            <!-- Card 2 -->
  <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dashboard-card">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-500">Places disponibles</h3>
        <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7" />
            </svg>
        </div>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $places_disponibles }}</p>
    <div class="mt-1 flex items-center text-sm">
        <span class="{{ ($evolution_1h[0] === '+') ? 'text-green-600' : 'text-red-600' }}">
            {{ $evolution_1h }}
        </span>
        <span class="ml-1 text-gray-500">depuis 1h</span>
    </div>
</div>


            <!-- Card 3 -->
          <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dashboard-card">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-500">Places occupées</h3>
        <div class="h-8 w-8 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $places_occupees }}</p>
    <div class="mt-1 flex items-center text-sm">
        <span class="{{ ($evolution_occupees_1h < 0) ? 'text-red-600' : 'text-green-600' }}">
            {{ $evolution_occupees_1h >= 0 ? '+' : '' }}{{ $evolution_occupees_1h }}
        </span>
        <span class="ml-1 text-gray-500">depuis 1h</span>
    </div>
</div>


            <!-- Card 4 -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm dashboard-card">
    <div class="flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-500">Taux d'occupation</h3>
        <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
        </div>
    </div>

    <p class="mt-2 text-3xl font-bold text-gray-900">{{ $taux_occupation }}%</p>

    <div class="mt-1 flex items-center text-sm">
        <span class="{{ $evolution_occupees_1h < 0 ? 'text-red-600' : 'text-green-600' }}">
            {{ $evolution_occupees_1h > 0 ? '+' : '' }}{{ $evolution_occupees_1h }}
        </span>
        <span class="ml-1 text-gray-500">depuis 1h</span>
    </div>
</div>
</div>


        <!-- Tableau récapitulatif des engins -->
  <div class="mb-8">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Statistiques des engins</h3> 
   

  <!-- Onglets -->
     <div class="border-b border-gray-200 mb-4">
    <!-- Exemple de navigation -->
<ul class="flex flex-wrap -mb-px text-sm font-medium text-green-600">
  <li class="mr-2">
    <a href="#tab-daily" class="inline-block p-4 border-b-2 font-semibold hover:text-green-600 hover:border-green-600">Journalier</a>
  </li>
  <li class="mr-2">
    <a href="#tab-weekly" class="inline-block p-4 border-b-2 hover:text-green-600 hover:border-green-600">Hebdomadaire</a>
  </li>
  <li class="mr-2">
    <a href="#tab-monthly" class="inline-block p-4 border-b-2 hover:text-green-600 hover:border-green-600">Mensuel</a>
  </li>
  <li>
    <a href="#tab-yearly" class="inline-block p-4 border-b-2 hover:text-green-600 hover:border-green-600">Annuel</a>
  </li>
</ul>

     </div>

  <!-- Contenu de l'onglet "Statistiques du jour" -->
  <div id="tab-daily" class="tab-content">
    <!-- Filtre date -->
    <div class="flex justify-between items-center mb-4">
      <h4 class="text-md font-medium text-gray-700">Statistiques du jour</h4>
      <div class="flex items-center space-x-2">
  <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
    <!-- Sélecteur de date -->
    <input 
        type="date" 
        name="date" 
        value="{{ request('date', date('Y-m-d')) }}" 
        class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-900"
    >

    <!-- Bouton appliquer -->
    <button 
        type="submit" 
        class="bg-primary-100 text-primary-700 px-3 py-1 rounded-md text-sm font-medium hover:bg-primary-200 text-gray-700"
    >
        Appliquer
    </button>

    <!-- Bouton exporter -->
    <a 
        href="{{ route('export.jour', ['date' => request('date', date('Y-m-d'))]) }}" 
        class="bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-green-700"
    >
        Exporter PDF
    </a>

    <input type="hidden" name="period" value="jour">
</form>

      </div>
    </div>

    <!-- Tableau des données -->
 <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
      <thead class="bg-gray-50">
        <tr>
          <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Type d'engin</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Entrées</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sorties</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tarif</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Revenus</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @foreach ($dailyTableData as $stat)
          <tr>
            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $stat['label'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['entrees'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['sorties'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['tarif'], 0, ',', ' ') }} FCFA</td>
           <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['revenu'], 0, ',', ' ') }} FCFA</td>
          </tr>
        @endforeach
        <tr class="bg-gray-50 font-semibold text-gray-900">
          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">Total</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $dailyTotalEntrees }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $dailyTotalSorties }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">–</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ number_format($dailyRevenusTotaux, 0, ',', ' ') }} FCFA</td>
        </tr>
      </tbody>
    </table>
  </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
      <!-- Graphique Circulaire -->
      <div class="bg-white p-4 rounded-lg shadow-sm w-full">
        <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Répartition des entrées par type d'engin</h5>
        <div class="flex justify-center items-start">
          <svg viewBox="0 0 320 200" class="w-full h-64">
            <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="30" />
            @foreach ($dailyPieSegments as $s)
              <circle
                cx="100" cy="100" r="80"
                fill="none"
                stroke="{{ $s['color'] }}"
                stroke-width="30"
                stroke-dasharray="{{ $s['dasharray'] }}"
                stroke-dashoffset="{{ $s['dashoffset'] }}"
                stroke-linecap="butt"
              />
            @endforeach
            @php $y = 20; @endphp
            @foreach ($dailyPieSegments as $s)
              <rect x="210" y="{{ $y }}" width="12" height="12" fill="{{ $s['color'] }}" />
              <text x="228" y="{{ $y + 10 }}" font-size="11" fill="#374151">
                {{ $s['label'] }} ({{ $s['percent'] }}%)
              </text>
              @php $y += 20; @endphp
            @endforeach
          </svg>
        </div>
      </div>

      <!-- Graphique à barres -->
       <div class="bg-white p-4 rounded-lg shadow-sm">
      <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Revenus journalier</h5>
      <div class="relative h-64 flex items-end justify-between px-4">
    @foreach ($dailyRevenusSegments as $bar)
  @php
    $height = $dailyRevenuMax > 0 ? round(($bar['revenu'] / $dailyRevenuMax) * 100) : 0;
  @endphp
  <div class="flex flex-col items-center">
    <div
      class="w-6 rounded-t mb-1 text-white text-[10px] text-center"
      style="height: {{ $height }}px; background-color: {{ $bar['color'] }};"
    >
      {{ number_format($bar['revenu'], 0, ',', ' ') }} FCFA
    </div>
    <span class="text-[10px] text-gray-700">{{ $bar['label'] }}</span>
  </div>
@endforeach
      </div>
    </div>
    </div>
  </div>
</div>
<!-- Onglet Hebdomadaire -->
<div id="tab-weekly" class="tab-content">
  <!-- En-tête -->
  <div class="flex justify-between items-center mb-4">
    <h4 class="text-md font-medium text-gray-700">Statistiques hebdomadaires</h4>
    <div class="flex items-center space-x-2">
   <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
  <input
    type="week"
    name="date"
    id="weekly-week"
    class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-900"
    value="{{ request('date', now()->format('Y') . '-W' . now()->format('W')) }}"
  >
  <input type="hidden" name="period" value="semaine">
  <button
    type="submit"
    class="bg-primary-100 text-primary-700 px-3 py-1 rounded-md text-sm font-medium hover:bg-primary-200 text-gray-600"
  >
    Appliquer
  </button>
  <!-- Bouton exporter -->
    <a 
        href="{{ route('export.semaine', ['date' => request('date', date('Y-m-d'))]) }}" 
        class="bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-green-700"
    >
        Exporter PDF
    </a>
</form>


    </div>
  </div>

  <!-- Tableau -->
  <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
      <thead class="bg-gray-50">
        <tr>
          <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Type d'engin</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Entrées</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sorties</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tarif</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Revenus</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @foreach ($weeklyTableData as $stat)
          <tr>
            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $stat['label'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['entrees'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['sorties'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['tarif'], 0, ',', ' ') }} FCFA</td>
           <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['revenu'], 0, ',', ' ') }} FCFA</td>
          </tr>
        @endforeach
        <tr class="bg-gray-50 font-semibold text-gray-900">
          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">Total</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $weeklyTotalEntrees }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $weeklyTotalSorties }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">–</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ number_format($weeklyRevenusTotaux, 0, ',', ' ') }} FCFA</td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Graphiques -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <!-- Graphique Circulaire -->
      <div class="bg-white p-4 rounded-lg shadow-sm w-full">
        <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Répartition des entrées par type d'engin</h5>
        <div class="flex justify-center items-start">
          <svg viewBox="0 0 320 200" class="w-full h-64">
            <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="30" />
            @foreach ($weeklyPieSegments as $s)
              <circle
                cx="100" cy="100" r="80"
                fill="none"
                stroke="{{ $s['color'] }}"
                stroke-width="30"
                stroke-dasharray="{{ $s['dasharray'] }}"
                stroke-dashoffset="{{ $s['dashoffset'] }}"
                stroke-linecap="butt"
              />
            @endforeach
            @php $y = 20; @endphp
            @foreach ($weeklyPieSegments as $s)
              <rect x="210" y="{{ $y }}" width="12" height="12" fill="{{ $s['color'] }}" />
              <text x="228" y="{{ $y + 10 }}" font-size="11" fill="#374151">
                {{ $s['label'] }} ({{ $s['percent'] }}%)
              </text>
              @php $y += 20; @endphp
            @endforeach
          </svg>
        </div>
      </div>


    <!-- Graphique à barres -->
    <div class="bg-white p-4 rounded-lg shadow-sm">
      <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Revenus hebdomadaires</h5>
      <div class="relative h-64 flex items-end justify-between px-4">
        @foreach ($weeklyRevenusSegments as $bar)
          @php
            $height = $weeklyRevenuMax > 0 ? round(($bar['revenu'] / $weeklyRevenuMax) * 100) : 0;
          @endphp
          <div class="flex flex-col items-center">
           <div
           class="w-6 rounded-t mb-1 text-white text-[10px] text-center"
           style="height: {{ $height }}px; background-color: {{ $bar['color'] }}">
             {{ round($bar['revenu'] / 1000) }}k
           </div>
           <span class="text-[10px] text-gray-700">{{ $bar['label'] }}</span>
           </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
<div id="tab-monthly" class="tab-content">
  <div class="flex justify-between items-center mb-4">
    <h4 class="text-md font-medium text-gray-700">Statistiques mensuelles</h4>
    <div class="flex items-center space-x-2">
   <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
  <input
    type="month"
    name="date"
    id="monthly-month"
    class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-900"
    value="{{ request('date', now()->format('Y-m')) }}"
  >
  <input type="hidden" name="period" value="mois text-gray-600">
  <button
    type="submit"
    class="bg-primary-100 text-primary-700 px-3 py-1 rounded-md text-sm font-medium hover:bg-primary-200 text-gray-600"
  >
    Appliquer
  </button>
  <!-- Bouton exporter -->
    <a 
        href="{{ route('export.mois', ['date' => request('date', date('Y-m-d'))]) }}" 
        class="bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-green-700"
    >
        Exporter PDF
    </a>
</form>

    </div>
  </div>

  <!-- Tableau -->
  <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
      <thead class="bg-gray-50">
        <tr>
          <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Type d'engin</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Entrées</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sorties</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tarif</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Revenus</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @foreach ($monthlyTableData as $stat)
        <tr>
          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $stat['label'] }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['entrees'] }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['sorties'] }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['tarif'], 0, ',', ' ') }} FCFA</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['revenu'], 0, ',', ' ') }} FCFA</td>
        </tr>
        @endforeach

        <tr class="bg-gray-50 font-semibold text-gray-900">
          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">Total</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $monthlyTotalEntrees }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $monthlyTotalSorties }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">–</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ number_format($monthlyRevenusTotaux, 0, ',', ' ') }} FCFA</td>
        </tr>
      </tbody>
    </table>
  </div>

 <!-- Graphiques -->
   <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
   <!-- Graphique Circulaire -->
      <div class="bg-white p-4 rounded-lg shadow-sm w-full">
        <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Répartition des entrées par type d'engin</h5>
        <div class="flex justify-center items-start">
          <svg viewBox="0 0 320 200" class="w-full h-64">
            <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="30" />
            @foreach ($monthlyPieSegments as $s)
              <circle
                cx="100" cy="100" r="80"
                fill="none"
                stroke="{{ $s['color'] }}"
                stroke-width="30"
                stroke-dasharray="{{ $s['dasharray'] }}"
                stroke-dashoffset="{{ $s['dashoffset'] }}"
                stroke-linecap="butt"
              />
            @endforeach
            @php $y = 20; @endphp
            @foreach ($monthlyPieSegments as $s)
              <rect x="210" y="{{ $y }}" width="12" height="12" fill="{{ $s['color'] }}" />
              <text x="228" y="{{ $y + 10 }}" font-size="11" fill="#374151">
                {{ $s['label'] }} ({{ $s['percent'] }}%)
              </text>
              @php $y += 20; @endphp
            @endforeach
          </svg>
        </div>
      </div>

    <!-- Graphique à barres -->
       <div class="bg-white p-4 rounded-lg shadow-sm">
      <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Revenus mensuelles</h5>
      <div class="relative h-64 flex items-end justify-between px-4">
        @foreach ($monthlyRevenusSegments as $bar)
          @php
            $height = $monthlyRevenuMax > 0 ? round(($bar['revenu'] / $monthlyRevenuMax) * 100) : 0;
          @endphp
          <div class="flex flex-col items-center">
           <div
           class="w-6 rounded-t mb-1 text-white text-[10px] text-center"
           style="height: {{ $height }}px; background-color: {{ $bar['color'] }}">
             {{ round($bar['revenu'] / 1000) }}k
           </div>
           <span class="text-[10px] text-gray-700">{{ $bar['label'] }}</span>
           </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

<div id="tab-yearly" class="tab-content">
  <div class="flex justify-between items-center mb-4">
    <h4 class="text-md font-medium text-gray-700">Statistiques annuelles</h4>
    <div class="flex items-center space-x-2">
   <form method="GET" action="{{ route('dashboard') }}" class="flex items-center space-x-2">
  <input
    type="number"
    name="date"
    id="yearly-year"
    min="2025"
    max="2100"
    value="{{ request('date', date('Y')) }}"
    class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-900"
  >
  <input type="hidden" name="period" value="annee">
  <button
    type="submit"
    class="bg-primary-100 text-primary-700 px-3 py-1 rounded-md text-sm font-medium hover:bg-primary-200 text-gray-900"
  >
    Appliquer
  </button>
  <!-- Bouton exporter -->
    <a 
        href="{{ route('export.annee', ['date' => request('date', date('Y-m-d'))]) }}" 
        class="bg-green-600 text-white px-3 py-1 rounded-md text-sm font-medium hover:bg-green-700"
    >
        Exporter PDF
    </a>
</form>

    </div>
  </div>

  <!-- Tableau -->
  <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
    <table class="min-w-full divide-y divide-gray-300">
      <thead class="bg-gray-50">
        <tr>
          <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Type d'engin</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Entrées</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sorties</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tarif</th>
          <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Revenus</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white">
        @foreach ($yearlyTableData as $stat)
          <tr>
            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $stat['label'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['entrees'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $stat['sorties'] }}</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['tarif'], 0, ',', ' ') }} FCFA</td>
            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ number_format($stat['revenu'], 0, ',', ' ') }} FCFA</td>
          </tr>
        @endforeach
        <tr class="bg-gray-50 font-semibold text-gray-900">
          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">Total</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $yearlyTotalEntrees }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">{{ $yearlyTotalSorties }}</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm">–</td>
          <td class="whitespace-nowrap px-3 py-4 text-sm"> {{ number_format($yearlyRevenusTotaux, 0, ',', ' ') }} FCFA </td>
        </tr>
      </tbody>
    </table>
  </div>
<!-- Graphiques -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
      <!-- Graphique Circulaire -->
      <div class="bg-white p-4 rounded-lg shadow-sm w-full">
        <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Répartition des entrées par type d'engin</h5>
        <div class="flex justify-center items-start">
          <svg viewBox="0 0 320 200" class="w-full h-64">
            <circle cx="100" cy="100" r="80" fill="none" stroke="#e5e7eb" stroke-width="30" />
            @foreach ($yearlyPieSegments as $s)
              <circle
                cx="100" cy="100" r="80"
                fill="none"
                stroke="{{ $s['color'] }}"
                stroke-width="30"
                stroke-dasharray="{{ $s['dasharray'] }}"
                stroke-dashoffset="{{ $s['dashoffset'] }}"
                stroke-linecap="butt"
              />
            @endforeach
            @php $y = 20; @endphp
            @foreach ($yearlyPieSegments as $s)
              <rect x="210" y="{{ $y }}" width="12" height="12" fill="{{ $s['color'] }}" />
              <text x="228" y="{{ $y + 10 }}" font-size="11" fill="#374151">
                {{ $s['label'] }} ({{ $s['percent'] }}%)
              </text>
              @php $y += 20; @endphp
            @endforeach
          </svg>
        </div>
      </div>


    <!-- Graphique à barres -->
    <div class="bg-white p-4 rounded-lg shadow-sm">
      <h5 class="text-sm font-medium text-gray-700 mb-4 text-center">Revenus annuelles</h5>
      <div class="relative h-64 flex items-end justify-between px-4">
        @foreach ($yearlyRevenusSegments as $bar)
          @php
            $height = $yearlyRevenuMax > 0 ? round(($bar['revenu'] / $yearlyRevenuMax) * 100) : 0;
          @endphp
          <div class="flex flex-col items-center">
           <div
           class="w-6 rounded-t mb-1 text-white text-[10px] text-center"
           style="height: {{ $height }}px; background-color: {{ $bar['color'] }}">
             {{ round($bar['revenu'] / 1000) }}k
           </div>
           <span class="text-[10px] text-gray-700">{{ $bar['label'] }}</span>
           </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
                         
</body>
</html>
