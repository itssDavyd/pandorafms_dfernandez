<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Confirmada</title>
</head>
<body>
<h1>¡Cita Confirmada!</h1>
<p>Estimado {{ $cita->user_name }},</p>
<p>Tu cita ha sido confirmada para el {{ $cita->date }} a las {{ $cita->hour }}.</p>
<p>Gracias por confiar en nosotros.</p>
</body>
</html>
