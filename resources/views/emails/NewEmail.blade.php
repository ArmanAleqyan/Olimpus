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
    <h2>{{ __('¡Nueva dirección de correo electrónico añadida!') }}</h2>

    <p>{{ __('Hola') }} {{ $details['name'] }},</p>
    <p>{{ __('Queremos informarte que una nueva dirección de correo electrónico ha sido añadida a tu cuenta en nuestro sitio web.') }}</p>
    <p>{{ __('A continuación, encontrarás los detalles:') }}</p>

    <ul>
        <li><strong>{{ __('Nombre:') }}</strong> {{ $details['name'] }}</li>
        <li><strong>{{ __('Nueva Dirección de Correo Electrónico:') }}</strong> {{ $details['email'] }}</li>
        <li><strong>{{ __('Código de Confirmación:') }}</strong> {{ $details['code'] }}</li>
    </ul>

    <p>{{ __('Si no reconoces esta acción, por favor contacta con nuestro equipo de soporte.') }}</p>

    <p>{{ __('¡Gracias!') }}</p>
</div>
</body>
</html>
