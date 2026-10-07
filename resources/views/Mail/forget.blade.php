<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de Mot de Passe - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* Fond blanc */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* Fond du mail */
            border: 1px solid #ffc0cb; /* Bordure rose */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #ffc0cb; /* Rose clair */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 25px;
            background-color: #ffffff;
            color: #333;
            line-height: 1.6;
        }

        .footer {
            background-color: #ffc0cb; /* Rose clair */
            color: #ffffff;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }

        /* Bouton stylé optionnel */
        .btn {
            display: inline-block;
            background-color: #ffc0cb;
            color: #ffffff !important;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #ffb6c1;
        }
    </style>

</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ url('/icons/L8s5vItyfLF8pq2BEUEUfcYURb6NWBMDHL36J2r8.png') }}" alt="{{ config('app.name') }} Logo">
            <h1>Réinitialisation de votre mot de passe</h1>
        </div>
        <div class="content">
            <p>Bonjour {{ $user->nom  }},</p>
            <p>Nous avons reçu une demande de réinitialisation de votre mot de passe. Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci-dessous :</p>
            <p><a href="{{ $url }}">Réinitialiser mon mot de passe</a></p>
            <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, veuillez ignorer cet e-mail.</p>
            <p>Merci et à bientôt sur {{ config('app.name') }}.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
