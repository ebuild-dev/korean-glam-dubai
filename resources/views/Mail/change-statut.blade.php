<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mise à Jour du Statut de Commande</title>
    <style>
        /* Styles CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            width: 150px;
            /* Ajuster la taille du logo selon vos besoins */
        }

        .content {
            background-color: #e96dad;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
        }

        .content h2 {
            margin-top: 0;
        }

        .button {
            display: inline-block;
            background-color: #ffffff;
            color: #e96dad;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ url('/icons/L8s5vItyfLF8pq2BEUEUfcYURb6NWBMDHL36J2r8.png') }}" alt="Your Logo" class="logo">
        <h2>Mise à Jour du Statut de Commande</h2>

        <center>
            <h1>
                {{ $commande->statut }}
            </h1>
        </center>
        <br><br>
        <p>Bonjour {{ $commande->client->nom }} ,</p>
        <p>Nous vous informons que le statut de votre commande (ID : {{ $commande->id }}) a été mis à jour.</p>
        <p>Vous pouvez télécharger le reçu de votre commande en utilisant le lien ci-dessous :</p>
        <a href="{{ route('print_commande', ['id' => $commande->id]) }}" class="button">
            Télécharger le Reçu
        </a>
        <p>Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter. Merci d'avoir fait vos
            achats chez nous !</p>
    </div>
</body>

</html>
