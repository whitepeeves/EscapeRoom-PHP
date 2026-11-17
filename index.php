<?php
session_start();

$habitaciones = [
    0 => ['habitación 0: Inicio', 'Bienvenidos al Escape Room. Debéis cruzar 7 habitaciones resolviendo 5 candados. Cada candado tiene 3 números y las pistas están repartidas por las habitaciones que ya habéis visitado.', ['C1, primer número: las patas de una silla.', 'C2, primer número: el número de la suerte en un dado que va después del cero en los números naturales.', 'C4, segundo número: las horas de un día partidas entre 8 (24/8=3), más 4.']],
    1 => ['habitación 1: La biblioteca', 'Hay estanterías llenas de libros. Una puerta con el candado C1 lleva a la habitación 2.', ['C1, segundo número: los ojos de una persona.', 'C1, tercer número: los días de la semana.', 'C2, segundo número: la mitad de 10.', 'C3, primer número: un círculo y un palo forman el número 8.', 'C5, primer número: el último dígito del año 2019.']],
    2 => ['habitación 2: El laboratorio', 'Tubos de ensayo y un microscopio. Una puerta con el candado C2 lleva a la habitación 3.', ['C2, tercer número: el cuadrado de 3.', 'C3, segundo número: el número de lados de un triángulo menos 3.', 'C4, primer número: los lados de un triángulo.']],
    3 => ['habitación 3: La cocina', 'Huele a quemado. Una puerta con el candado C3 lleva a la habitación 4.', ['C3, tercer número: la mitad de 12.', 'C5, segundo número: las estaciones del año menos 3.']],
    4 => ['habitación 4: El sótano', 'Oscuro y húmedo. Una puerta con el candado C4 lleva a la habitación 5.', ['C4, tercer número: los dedos de una mano menos 3.', 'C5: el candado final está en la habitación 5.']],
    5 => ['habitación 5: La sala del final', 'Una gran puerta con el candado C5. Solo tenéis 3 intentos: si falláis el tercero volveréis a la habitación 0.', ['C5, tercer número: 4 más 1.']],
    6 => ['Habitación 6: FIN del juego', 'Enhorabuena! Habéis escapado.', []],
];

$codigos = [1 => '427', 2 => '159', 3 => '806', 4 => '327', 5 => '915'];

if (!isset($_SESSION['nivel'])) {
    $_SESSION['nivel'] = 0;
    $_SESSION['hab'] = 0;
    $_SESSION['intentos'] = 0;
}

if (isset($_GET['reiniciar'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

$mensaje = '';

if (isset($_GET['ir'])) {
    $destino = (int)$_GET['ir'];
    if ($destino >= 0 && $destino <= $_SESSION['nivel'] + 1 && $destino <= 5) {
        $_SESSION['hab'] = $destino;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $h = $_SESSION['hab'];
    $n = $_SESSION['nivel'];
    if ($h === $n + 1 && $h <= 5) {
        $codigo = trim($_POST['codigo'] ?? '');
        if ($codigo === $codigos[$h]) {
            $_SESSION['nivel'] = $h;
            $_SESSION['hab'] = $h + 1;
            $_SESSION['intentos'] = 0;
        } elseif ($h === 5) {

            $_SESSION['intentos']++;


            if ($_SESSION['intentos'] >= 3) {

                $_SESSION['nivel'] = 0;
                $_SESSION['hab'] = 0;
                $_SESSION['intentos'] = 0;

                $mensaje = 'Tercer intento fallido. Volvéis a la habitación 0.';




            } else {

                $mensaje = 'Código incorrecto. Intentos restantes: ' . (3 - $_SESSION['intentos']);
            
                }
        } 
        else {
        
        $mensaje = 'Código incorrecto.';
        
        }
    }
}









$h = $_SESSION['hab'];
$n = $_SESSION['nivel'];

list($titulo, $texto, $pistas) = $habitaciones[$h];

?>

<!DOCTYPE html>
<html lang="es">
        
    <head>
         <meta charset="UTF-8">
        <title>Escapé de la room</title>


        <style>

             body { font-family: sans-serif; max-width: 600px; margin: 30px auto; }
              .error { color: red; }
     
     </style>
    </head>

    <body>
        <h1><?= $titulo ?></h1>
        <p><?= $texto ?></p>


        <?php if ($mensaje) { ?><p class="error"><?= $mensaje ?></p><?php } ?>


        <?php if ($pistas) { ?>
        
        <h3>Pistas</h3>
        <ul>
        <?php foreach ($pistas as $p) { ?><li><?= $p ?></li><?php } ?>
        </ul>
        <?php } ?>


        <?php if ($h === $n + 1 && $h <= 5) { ?>
        <form method="post">
        <label>Candado C<?= $h ?>: <input type="text" name="codigo" maxlength="3" size="4" autofocus></label>
        <button type="submit">Probar</button>
        </form>

        <?php } ?>


        <?php if ($h < 6) { ?>
        <h3>Ir a</h3>
        <p>
        <?php for ($i = 0; $i <= min($n + 1, 5); $i++) { ?>
        <a href="?ir=<?= $i ?>">Habitación <?= $i ?></a>
        <?php } ?>
        </p>

        <?php } ?>


        
        <p><a href="?reiniciar=1">Reiniciar</a></p>
    
    
    </body>




















    
</html>
