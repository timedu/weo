<?php

$httpBody = file_get_contents('php://input');
$data = json_decode($httpBody, true);

debug($data['data1']);
debug($data['data2']);

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
