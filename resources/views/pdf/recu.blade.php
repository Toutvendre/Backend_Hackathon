<!DOCTYPE html>
<html>

<head>
    <title>Reçu de Commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .details {
            border-collapse: collapse;
            width: 100%;
        }

        .details th,
        .details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .details th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Reçu de Commande</h1>
            <p>
                Commande N° {{ $commande->id }} -
                {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}
            </p>
            <p>Numéro de Reçu : {{ $commande->numero_recu ?? 'En cours de génération' }}</p>
        </div>

        <h2>Client</h2>
        <p>Nom : {{ $commande->client_nom }}</p>
        <p>Téléphone : {{ $commande->client_telephone }}</p>

        @if ($commande->livraison)
            <p>Adresse de livraison : {{ $commande->adresse_livraison }}</p>
        @endif

        @if ($commande->notes)
            <p>Notes : {{ $commande->notes }}</p>
        @endif

        <p>Compagnie : {{ optional($commande->compagnie)->nom ?? 'N/A' }}</p>
        <p>Transaction : {{ optional($commande->transaction)->code_transaction ?? 'N/A' }}</p>
        <p>Méthode de paiement : Orange Money</p>

        <h2>Produit</h2>
        <table class="details">
            <tr>
                <th>Nom</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ optional($commande->produit)->nom ?? 'N/A' }}</td>
                <td>{{ $commande->quantite }}</td>
                <td>{{ number_format(optional($commande->produit)->prix ?? 0, 2, ',', ' ') }} €</td>
                <td>{{ number_format($commande->prix_total, 2, ',', ' ') }} €</td>
            </tr>
        </table>

        <h2>Total : {{ number_format($commande->prix_total, 2, ',', ' ') }} €</h2>
        <p>Merci pour votre achat !</p>
    </div>
</body>

</html>
