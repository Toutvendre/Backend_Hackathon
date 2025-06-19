<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre compte Assistant Digitale</title>
    <style>
        body {
            background-color: #f4f4f4 !important;
            color: #1a1a1a !important;
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.5;
        }

        .container {
            background-color: #ffffff !important;
            border: 1px solid #e0e0e0 !important;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .content {
            color: #1a1a1a !important;
            padding: 40px 30px;
        }

        .title {
            color: #1a1a1a !important;
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 900;
            margin: 0 0 8px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .subtitle,
        .field-label,
        .footer-text {
            color: #666666 !important;
        }

        .field-value {
            color: #1a1a1a !important;
        }

        .button {
            background: linear-gradient(90deg, #ff6200, #ff8c00) !important;
            /* Orange gradient for Assistant Digitale */
            color: #ffffff !important;
            border: 2px solid #ff6200 !important;
            display: inline-block;
            padding: 14px 40px;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        }

        .footer {
            background-color: #f4f4f4 !important;
            border-top: 1px solid #e0e0e0 !important;
            padding: 20px;
            text-align: center;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }

        a {
            color: #ff6200 !important;
            /* Match brand color */
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 10px !important;
            }

            .content {
                padding: 30px 20px !important;
            }

            .title {
                font-size: 24px !important;
            }

            .subtitle {
                font-size: 12px !important;
                margin-bottom: 20px !important;
            }

            .field-label {
                font-size: 9px !important;
            }

            .field-value {
                font-size: 16px !important;
            }

            .button {
                padding: 12px 30px !important;
                font-size: 15px !important;
            }

            .footer {
                padding: 15px !important;
            }
        }
    </style>
</head>

<body>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" class="container">
                    <tr>
                        <td class="content">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="margin-bottom: 20px;">
                                <tr>
                                    <td align="center">
                                        <div
                                            style="width: 60px; height: 5px; background: linear-gradient(90deg, #ff6200, #ff8c00); border-radius: 2px;">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <h1 class="title">Bienvenue, {{ $compagnie->nom }} !</h1>
                            <p class="subtitle"
                                style="font-size: 13px; font-weight: 400; margin: 0 0 30px; text-align: center; letter-spacing: 0.3px;">
                                Votre compte compagnie a été créé avec succès.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <span class="field-label"
                                            style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;">CMPID</span><br>
                                        <span class="field-value"
                                            style="font-size: 18px; font-weight: 600;">{{ $compagnie->CMPID }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <span class="field-label"
                                            style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;">Email</span><br>
                                        <span class="field-value"
                                            style="font-size: 18px; font-weight: 600;">{{ $compagnie->email }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <span class="field-label"
                                            style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;">Catégorie</span><br>
                                        <span class="field-value"
                                            style="font-size: 18px; font-weight: 600;">{{ $compagnie->typeCategorie->nom }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <span class="field-label"
                                            style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;">Mot
                                            de passe temporaire</span><br>
                                        <span class="field-value"
                                            style="font-size: 18px; font-weight: 600;">{{ $motDePasse }}</span>
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size: 16px; font-weight: 500; color: #1a1a1a; margin: 20px 0;">
                                Connectez-vous à Assistant Digitale avec votre CMPID et ce mot de passe temporaire, puis
                                modifiez votre mot de passe pour plus de sécurité.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                style="margin-top: 40px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ env('FRONTEND_URL', 'http://localhost:5174') }}/login"
                                            class="button">Se connecter</a>
                                    </td>
                                </tr>
                            </table>
                            <p style="font-size: 16px; font-weight: 500; color: #1a1a1a; margin: 20px 0 0;">
                                Merci de rejoindre Assistant Digitale !
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer">
                            <p style="font-size: 15px; font-weight: 600; color: #1a1a1a; margin: 0 0 8px;">Assistant
                                Digitale</p>
                            <p class="footer-text" style="font-size: 12px; font-weight: 400; margin: 0 0 15px;">Votre
                                assistant pour une gestion connectée</p>
                            <p class="footer-text" style="font-size: 11px; font-weight: 400; margin: 15px 0 0;">
                                © {{ now()->year }} Assistant Digitale. Tous droits réservés. |
                                <a href="{{ env('FRONTEND_URL', 'http://localhost:5174') }}"
                                    style="text-decoration: none; font-weight: 600;">Visitez notre site</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
