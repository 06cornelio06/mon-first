<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ticket d'entrée</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }
        .ticket {
            width: 300px;
            margin: 20px auto;
            padding: 10px;
            border: 1px dashed #333;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body onload="window.print();">
    <div class="ticket">
        <h3>🎫 Ticket d'Entrée</h3>
        <p><strong>Plaque :</strong> {{ $entree->plaque }}</p>
        <p><strong>Type :</strong> {{ $entree->type }}</p>
        <p><strong>Nom :</strong> {{ $entree->name }}</p>
        <p><strong>Téléphone :</strong> {{ $entree->phone }}</p>
        <p><strong>Date :</strong> {{ $entree->created_at->format('d/m/Y H:i') }}</p>
        <p style="text-align: center;">Merci pour votre visite</p>
    </div>
</body>
</html>
