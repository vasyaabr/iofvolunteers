<?php
// This is a deploy script, which is triggered on GitHub `push` event
$result = "IOF deploy started.<br/>";

foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0) {
        $chunks = explode('_', $key);
        $header = '';
        for ($i = 1; $y = sizeof($chunks) - 1, $i < $y; $i++) {
            $header .= ucfirst(strtolower($chunks[$i])).'-';
        }
        $header .= ucfirst(strtolower($chunks[$i])).': '.$value;
        $result .= $header."<br/>";
    }
}

$body = file_get_contents('php://input');
if ($body != '') {
    $result .= $body."<br/>";
}

$result .= 'Exec result: ' . shell_exec('git pull') . '<br/>';

mail('vasyaabr@gmail.com', 'Deploy', $result);
echo $result;
die();
