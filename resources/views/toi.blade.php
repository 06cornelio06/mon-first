<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Historique des activités - Viroscope</title>
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
    .tab-active {
      border-bottom: 2px solid #16a34a;
      color: #16a34a;
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

  <!-- Contenu principal -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white rounded-lg shadow mt-4 text-gray-800">
    <h1 class="text-3xl font-bold mb-6">Historique des activités</h1>

    <!-- Formulaire de recherche -->
    <form method="GET" action="{{ route('recent') }}" class="mb-8 bg-white p-4 rounded-lg shadow-sm">
      <div class="flex flex-col sm:flex-row flex-wrap gap-4">
        <div class="flex flex-col w-full sm:w-48">
          <label for="plaque" class="mb-1 font-medium">Plaque</label>
          <input type="text" name="plaque" id="plaque" value="{{ request('plaque') }}"
            placeholder="Ex: ABC123"
            class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" />
        </div>

        <div class="flex flex-col w-full sm:w-48">
          <label for="type" class="mb-1 font-medium">Type de véhicule</label>
          <select name="type" id="type"
            class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Tous</option>
            <option value="motorcycle" {{ request('type') == 'motorcycle' ? 'selected' : '' }}>Moto</option>
            <option value="car" {{ request('type') == 'car' ? 'selected' : '' }}>Voiture</option>
            <option value="tricycle" {{ request('type') == 'tricycle' ? 'selected' : '' }}>Tricycle</option>
            <option value="nyonyovi" {{ request('type') == 'nyonyovi' ? 'selected' : '' }}>Nyonyovi</option>
            <option value="minibus" {{ request('type') == 'minibus' ? 'selected' : '' }}>Minibus</option>
            <option value="bus" {{ request('type') == 'bus' ? 'selected' : '' }}>Bus</option>
            <option value="truck" {{ request('type') == 'truck' ? 'selected' : '' }}>Camion</option>
          </select>
        </div>

        <div class="flex flex-col w-full sm:w-48">
          <label for="periode" class="mb-1 font-medium">Période</label>
          <select name="periode" id="periode"
            class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Toutes</option>
            <option value="jour" {{ request('periode') == 'jour' ? 'selected' : '' }}>Aujourd'hui</option>
            <option value="semaine" {{ request('periode') == 'semaine' ? 'selected' : '' }}>Cette semaine</option>
            <option value="mois" {{ request('periode') == 'mois' ? 'selected' : '' }}>Ce mois</option>
            <option value="année" {{ request('periode') == 'année' ? 'selected' : '' }}>Cette année</option>
          </select>
        </div>

        <div class="flex items-end">
          <button type="submit"
            class="px-6 py-2 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
            Rechercher
          </button>
        </div>
      </div>
    </form>

    <!-- Tableau des activités -->
    <div class="overflow-x-auto rounded-lg shadow ring-1 ring-black ring-opacity-5">
      <table class="min-w-full divide-y divide-gray-300">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Plaque</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Événement</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nom</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Type</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Entrée</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Sortie</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Durée</th>
            <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Statut</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          @forelse ($activites as $act)
            <tr>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['date'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-900 font-semibold">{{ $act['plaque'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['evenement'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['name'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['type'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['entree'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['sortie'] }}</td>
              <td class="px-3 py-4 text-sm text-gray-700">{{ $act['duree'] }}</td>
              <td class="px-3 py-4 text-sm">
                @if ($act['statut'] === 'Complété')
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    {{ $act['statut'] }}
                  </span>
                @else
                  <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    {{ $act['statut'] }}
                  </span>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="9" class="text-center py-6 text-sm text-gray-500">Aucune activité trouvée.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
      {{ $activites->links() }}
    </div>
  </div>
</body>
</html>
