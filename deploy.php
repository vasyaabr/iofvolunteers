<?php
$result = '';
if (isset($_POST)) {
    $result = 'POST data: '.implode(';', $_POST)."\n";
}
$result .= 'Exec result: ' . shell_exec('git pull');
mail('vasyaabr@gmail.com', 'Deploy', 'IOF deployed: ' . $result);
echo $result;
die();
