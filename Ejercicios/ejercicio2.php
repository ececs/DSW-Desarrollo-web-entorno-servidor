<?php
$arr1 = [ 0 => 5, 1 => 8, 2 => -11, 23, -14, 27, 30, -18, 20, 7 ];

$positivos = 0;
$negativos = 0;
$ceros = 0;

foreach ($arr1 as $num) {
    if ($num > 0) {
        $positivos++;
    } elseif ($num < 0) {
        $negativos++;
    } else {
        $ceros++;
    }
}
echo "Tenemos $positivos números positivos" . PHP_EOL;
echo "Tenemos $negativos números negativos" . PHP_EOL;
echo "Tenemos $ceros números ceros" . PHP_EOL;