<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Salón</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;700;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="/build/css/app.css">
</head>
<body>
    <div class="contenedor-app">
        <div class="imagen"></div> <!-- la imagen se inserta como background con css -->
        <div class="app">
            <?php echo $contenido; ?>
        </div>
    </div>
    
    <?php
        echo $script ?? ''; // Si existe la variable script la imprime, con esto cargamos el archivo app.js
    ?>
    
</body>
</html>