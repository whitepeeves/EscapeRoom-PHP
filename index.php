<?php

/*
 * CSS: librería GlassKit (MIT) de JUNGHERZ
 * https://github.com/JUNGHERZ/GlassKit
 * Cargada desde https://cdn.jsdelivr.net/npm/@jungherz-de/glasskit@1.5/glasskit.min.css
 * El css lo he sacado de ahí, era bonito y quedaba como iphone.
 */



session_start();



$habitaciones = [
    
    0 => ['Habitación 0: Inicio', 'Bienvenidos al Escape Room. Debéis cruzar 7 habitaciones resolviendo 5 candados. Cada candado tiene 3 números y las pistas están repartidas por las habitaciones que ya habéis visitado.', ['C1, primer número x: 2x + 1 = 9', 'C2, primer número x: 5x - 3 = 2', 'C4, segundo número x: 3x - 4 = 17']],
    1 => ['Habitación 1: La biblioteca', 'Hay estanterías llenas de libros. Una puerta con el candado C1 lleva a la habitación 2.', ['C1, segundo número x (x > 0): x² = 4', 'C1, tercer número x: 3x - 5 = 16', 'C2, segundo número x: 2x + 3 = 13', 'C3, primer número x: x/2 + 1 = 5', 'C5, primer número x (x > 0): x² - 2x = 63']],
    2 => ['Habitación 2: El laboratorio', 'Tubos de ensayo y un microscopio. Una puerta con el candado C2 lleva a la habitación 3.', ['C2, tercer número x (x > 0): x² - 1 = 80', 'C3, segundo número x: 7x + 3 = 3', 'C4, primer número x: x³ = 27']],
    3 => ['Habitación 3: La cocina', 'Huele a quemado. Una puerta con el candado C3 lleva a la habitación 4.', ['C3, tercer número x: 4x - 10 = 14', 'C5, segundo número x: 10x - 4 = 6']],
    4 => ['Habitación 4: El sótano', 'Oscuro y húmedo. Una puerta con el candado C4 lleva a la habitación 5.', ['C4, tercer número x (x > 0): x² + x = 6', 'C5: el candado final está en la habitación 5.']],
    5 => ['Habitación 5: La sala final', 'Una gran puerta con el candado C5. Solo tenéis 3 intentos: si falláis el tercero volveréis a la habitación 0.', ['C5, tercer número x: 2x - 1 = 9']],
    6 => ['Habitación 6: Fin del juego', '¡Enhorabuena! Habéis escapado.', []],

    ];




$codigos = [1 => '427', 2 => '159', 3 => '806', 4 => '327', 5 => '915'];

if (!isset($_SESSION['nivel']))
{
    $_SESSION['nivel'] = 0;
    $_SESSION['hab'] = 0;
    $_SESSION['intentos'] = 0;
    
}

if (isset($_GET['reiniciar']))
{
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $h = $_SESSION['hab'];
    $n = $_SESSION['nivel'];
    
    if ($h === $n + 1 && $h <= 5) {
        
        $codigo = trim($_POST['codigo'] ?? '');

        
        if ($codigo === $codigos[$h]) {
            
            $_SESSION['nivel'] = $h;
            $_SESSION['hab'] = $h + 1;
            $_SESSION['intentos'] = 0;
            $_SESSION['aviso'] = '🔓 El candado C' . $h . '  está abierto!';
            
        } elseif ($h === 5) 
        {
            $_SESSION['intentos']++;
            
            if ($_SESSION['intentos'] >= 3)
            {
                $_SESSION['nivel'] = 0;
                $_SESSION['hab'] = 0;
                $_SESSION['intentos'] = 0;
                $mensaje = 'Tercer intento fallido. Vas a tener que volver a la habitación 0 sorry.';
                
            } else {
               $mensaje = 'Código incorrecto. Te quedan estos intentos: ' . (3 - $_SESSION['intentos']);
            }
        } else {
            $mensaje = 'Código incorrecto.';
            
        }



        
    }







    
}





$aviso = $_SESSION['aviso'] ?? '';
unset($_SESSION['aviso']);
$h = $_SESSION['hab'];
$n = $_SESSION['nivel'];
list($titulo, $texto, $pistas) = $habitaciones[$h];

?>











<!DOCTYPE html>

<html lang="es" data-theme="dark">
<head>
<meta charset="UTF-8">
<title>Escape Room</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@jungherz-de/glasskit@1.5/glasskit.min.css">
