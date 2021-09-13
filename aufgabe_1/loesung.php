<?php

class Guess
{
    const CORRECT = "Correct! It took you %d Tries, to guess the number %s. Feel free to guess another number!";
    const HIGH = "You guessed too big!";
    const LOW = "You guessed too small!";
    const NONE = "Please enter a Number!";

    static public function check($guess, $random_num)
    {
        $result = self::NONE;
        if ($guess == "") {
            $result = self::NONE;
        } else {
            if ($guess > $random_num) {
                $result = self::HIGH;
            } else if ($guess < $random_num) {
                $result = self::LOW;
            } else if ($guess == $random_num) {
                $result = self::CORRECT;
            }
        }
        return $result;
    }
}

$MIN_INT = 0;
$MAX_INT = 100;

if ($_POST) {
    $random_num = $_POST["random_num"];
    $try = $_POST["try"];
    $guess = $_POST["guess"];
    $out = Guess::check($guess, $random_num);
    if ($out != Guess::NONE) {
        $try += 1;
    }
    if ($out == Guess::CORRECT) {
        $out = sprintf($out, $try, $guess);
        list($random_num, $try) = init_new_rand($MIN_INT, $MAX_INT);
    }
} else {
    list($random_num, $try) = init_new_rand($MIN_INT, $MAX_INT);
    $out = "";
}

/**
 * @param $MIN_INT
 * @param $MAX_INT
 * @return array $random_num, $try
 */
function init_new_rand($MIN_INT, $MAX_INT)
{
    $random_num = rand($MIN_INT, $MAX_INT);
    $try = 0;
    return array($random_num, $try);
}

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Aufgabe 1</title>
</head>
<body>
<H1>Guess my Number. Its between <?php echo($MIN_INT . " and " . $MAX_INT) ?>.</H1>
<form method="post">
    <label>
        Guess:
        <input type="number" name="guess">
    </label>
    <input type="hidden" name="random_num" value="<?php echo($random_num) ?>">
    <input type="hidden" name="try" value="<?php echo($try) ?>">
</form>
<p><?php echo($out) ?></p>
<p><?php echo("Try: " . $try) ?></p>
<button type="reset" onclick="window.location = window.location.href;">Reset</button>
</body>
</html>