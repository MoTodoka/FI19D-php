<?php
if ($_POST) {
    $name = $_POST["name"];
} else {
    $name = "World";
}
$out = "Hello " . $name . "!";
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Formular</title>
</head>
<body>
<H1><?php echo($out); ?></H1>
<form method="post">
    Name: <label>
        <input type="text" name="name">
    </label><br>
    <input type="submit">
</form>
</body>
</html>