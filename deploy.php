<?php
echo shell_exec('git pull');
mail('vasyaabr@gmail.com', 'Deploy', 'IOF deployed');
die();
