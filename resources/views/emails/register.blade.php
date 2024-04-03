<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333333;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li strong {
            display: inline-block;
            width: 150px;
            font-weight: bold;
        }

        p {
            line-height: 1.5;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>{{ __('¡Bienvenido a nuestro sitio!') }}</h2>

    <p>{{ __('Hola') }} {{ $details['name'] }},</p>
    <p>{{ __('Te damos la bienvenida a nuestro sitio web. Tu registro ha sido exitoso.') }}</p>
    <p>{{ __('A continuación, encontrarás tus detalles de registro:') }}</p>

    <ul>
        <li><strong>{{ __('Nombre:') }}</strong> {{ $details['name'] }}</li>
        <li><strong>{{ __('Correo Electrónico:') }}</strong> {{ $details['email'] }}</li>
        <li><strong>{{ __('Código:') }}</strong> {{ $details['code'] }}</li>
    </ul>

    <p>{{ __('¡Gracias por unirte a nosotros!') }}</p>

    <p>{{ __('Comando: Olimpus') }}</p>
</div>
</body>
</html>
