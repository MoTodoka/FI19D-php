<?php
// In PHP beginnen alle Variablen mit '$'
// Concatenation wird mit '.' und nicht mit '+' gemacht.
$hello = "Hello"
    . " "
    . "World!";
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title><?php echo($hello); ?></title>
</head>
<body>
<H1><?php echo($hello); ?></H1>
</body>
</html>