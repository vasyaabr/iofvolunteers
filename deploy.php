<?php
// This is a deploy script, which is triggered on GitHub `push` event
$result = '';
if (isset($_POST)) {
    $result = 'POST data: '.implode(';', $_POST)."\n";
}
$result .= 'Exec result: ' . shell_exec('git pull');
mail('vasyaabr@gmail.com', 'Deploy', "IOF deployed!\n " . $result);
echo $result;
die();
