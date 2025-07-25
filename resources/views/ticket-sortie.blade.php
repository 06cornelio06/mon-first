<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket de Sortie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 250px; /* largeur ticket */
            margin: 0 auto;
            padding: 10px;
            color: #000;
        }

        .header, .footer {
            text-align: center;
        }

        .logo img {
            height: 50px;
            margin-bottom: 5px;
        }

        h2 {
            margin: 5px 0;
            font-size: 16px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }

        .section {
            margin: 10px 0;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        p {
            margin: 3px 0;
        }

        .footer {
            font-size: 10px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">
            <img src="{{ public_path('images/R.png') }}" alt="Viroscope">
        </div>
        <h2>Ticket de Sortie</h2>
    </div>

    <div class="section">
        <div class="section-title">Informations Véhicule</div>
        <p><strong>Plaque:</strong> {{ $entree->plaque }}</p>
        <p><strong>Type:</strong> {{ ucfirst($entree->type) }}</p>
        <p><strong>Propriétaire:</strong> {{ $entree->name }}</p>
        <p><strong>Téléphone:</strong> {{ $entree->phone }}</p>
    </div>

    <div class="section">
        <div class="section-title">Détails de la Sortie</div>
        <p><strong>Entrée:</strong> {{ $entree->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Sortie:</strong> {{ $sortie->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Durée:</strong> {{ $joursPasses }} jour(s)</p>
        <p><strong>Montant:</strong> {{ number_format($montant, 0, ',', ' ') }} FCFA</p>
        <p><strong>Paiement:</strong> {{ ucfirst($sortie->mode_paiement ?? 'Non précisé') }}</p>
    </div>

    <div class="footer">
        Merci de votre visite chez Viroscope<br>
        www.viroscope.com
    </div>

</body>
</html>
