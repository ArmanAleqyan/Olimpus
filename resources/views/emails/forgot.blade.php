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
    <h2>{{ __('Restablecimiento de Contraseña') }}</h2>

    <p>{{ __('Hola') }} {{ $details['name'] }},</p>
    <p>{{ __('Has solicitado un restablecimiento de contraseña en nuestro sitio web. Aquí están los detalles:') }}</p>

    <ul>
        <li><strong>{{ __('Nombre:') }}</strong> {{ $details['name'] }}</li>
        <li><strong>{{ __('Correo Electrónico:') }}</strong> {{ $details['email'] }}</li>
    </ul>

    <p>{{ __('Para restablecer tu contraseña, ingresa el siguiente código de verificación en la página de restablecimiento:') }}</p>
    <p><strong>{{ __('Código de Verificación:') }}</strong> {{ $details['code'] }}</p>

    <p>{{ __('Este código de verificación es válido por un corto período de tiempo.') }}</p>

    <p>{{ __('Si no has solicitado un restablecimiento de contraseña, no es necesario realizar ninguna acción.') }}</p>

    <p>{{ __('¡Gracias!') }}</p>
</div>
</body>
</html>
