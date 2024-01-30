<?php
//get data from the form
$hours = htmlspecialchars(INPUT_POST, 'hours');
$hours = htmlspecialchars(INPUT_POST, 'wage');

$salary = 0;
$hours = 0;

$salary = $hours * $wage;
$tax = $wages - $net;
$net = $salary * .75;

if (hours >= 40) {
    $hours = 40;
    $fullTime = $hours - 40;
    $OT = $fulltime * 1.5;
    $gross = $OT + $salary;
} else if ($hours <= 0) {
    echo "Please enter in a valid entry";
}
$gross = $salary - $net;
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset = "UTF-8">
        <title>Calculator</title>
    </head>
    <body>

        <?php
// put your code here
        ?>
    </body>
</html>
