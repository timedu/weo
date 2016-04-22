<?php

$httpBody = file_get_contents('php://input');
$input = json_decode($httpBody, true);

$output = [];

if ($input['data1'] == $input['data2']){
    $output['tulos'] = 'samat';
} else {
    $output['tulos'] = 'erit';
}

header('Content-type: application/json');
echo json_encode($output);

//debug($data['data1']);
//debug($data['data2']);

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
