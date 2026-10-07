<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Commande - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* Fond global blanc */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff; /* Fond blanc */
            border: 2px solid #ffc0cb; /* Bordure rose */
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #ffc0cb; /* Rose clair */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            padding: 20px;
            background-color: #ffffff; /* Contenu blanc */
            color: #333;
        }

        .footer {
            background-color: #ffc0cb; /* Rose clair */
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }

        /* Optionnel : pour ajouter une touche douce aux boutons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffc0cb;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
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

    </div>
    <div class="content">

        <h2>Hello {{ $appointment->full_name }},</h2>
        <p>This is a reminder for your appointment scheduled at:</p>
        <p><b>{{ $appointment->date_appointment }}</b></p>

        <p>You can join the online meeting here:</p>

        <p>
            <a href="https://meet.jit.si/{{ $appointment->meeting_code }}">
                https://meet.jit.si/{{ $appointment->meeting_code }}
            </a>
        </p>
        <p>
            Thank you for reaching out.<br>
            <strong>The {{ config('app.name') }} Team</strong>
        </p>

    </div>
    <div class="footer">
        <p>&copy; 2025 {{ config('app.name') }}. Tous droits réservés.</p>
    </div>
</div>
</body>

</html>
