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




   <!-- Hero Welcome Section -->
<section class="hero-pattern bg-green-700 text-white py-20">
    <div class="max-w-7xl mx-auto px-6 md:px-10 grid md:grid-cols-2 gap-12 items-center">
        
        <!-- Left: Welcome Content -->
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-lg">
                Bienvenue sur <span class="text-green-200">Viroscope</span>
            </h1>
            <p class="mt-5 text-lg md:text-xl text-green-100 leading-relaxed max-w-xl">
           “Gérez votre parking comme un pro.”
Une solution simple, rapide et efficace pour surveiller, optimiser et rentabiliser vos places de stationnement.</p>

            <!-- Social Proof -->
            <div class="mt-10 flex items-center mb-20">
                <div class="flex -space-x-3 mr-4">
                    <img class="h-10 w-10 rounded-full border-2 border-white" src="https://api.dicebear.com/7.x/personas/svg?seed=Alpha" alt="User 1">
                    <img class="h-10 w-10 rounded-full border-2 border-white" src="https://api.dicebear.com/7.x/personas/svg?seed=Beta" alt="User 2">
                    <img class="h-10 w-10 rounded-full border-2 border-white" src="https://api.dicebear.com/7.x/personas/svg?seed=Gamma" alt="User 3">
                </div>
                <p class="text-green-100 text-sm sm:text-base">
                    Déjà adopté par <span class="font-bold text-white">Plus de 500 clients nous font confiance</span>
                </p>
            </div>
        </div>

        <!-- Right: SVG Illustration -->
        <div class="hidden md:block mb-10">
            <svg class="w-full max-w-md mx-auto" viewBox="0 0 400 300" xmlns="http://www.w3.org/2000/svg">
                <rect x="50" y="50" width="300" height="200" fill="#e5e7eb" rx="6" />
                <g fill="#d1d5db">
                    <rect x="70" y="70" width="80" height="40" rx="3" />
                    <rect x="160" y="70" width="80" height="40" rx="3" />
                    <rect x="250" y="70" width="80" height="40" rx="3" />
                    <rect x="70" y="130" width="80" height="40" rx="3" />
                    <rect x="250" y="130" width="80" height="40" rx="3" />
                    <rect x="70" y="190" width="80" height="40" rx="3" />
                    <rect x="160" y="190" width="80" height="40" rx="3" />
                </g>
                <rect x="160" y="130" width="80" height="40" fill="#22c55e" rx="3" />
                <rect x="250" y="190" width="80" height="40" fill="#22c55e" rx="3" />
                <rect x="85" y="75" width="50" height="30" fill="#60a5fa" rx="5" />
                <rect x="175" y="75" width="50" height="30" fill="#f87171" rx="5" />
                <rect x="85" y="195" width="50" height="30" fill="#34d399" rx="5" />
                <circle cx="30" cy="30" r="20" fill="#22c55e" />
                <text x="30" y="35" font-size="20" text-anchor="middle" fill="white" font-weight="bold">P</text>
            </svg>
        </div>
    </div>
</section>

</body>
</html> 
 