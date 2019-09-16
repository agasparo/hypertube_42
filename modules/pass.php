<?php

require '../modules/bdd.php';
require '../panel/control.panel.php';

$data = file_get_contents('../template/reset_pass.html');
$data = str_replace('{{util_name}}', $util_name, $data);
$data = str_replace('{{mail}}', $mail, $data);
$data = str_replace('{{reset}}', $val, $data);

echo $data;
?>