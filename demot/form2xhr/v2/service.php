<?php

$httpBody = file_get_contents('php://input');
debug($httpBody);

function debug($data) {
    file_put_contents('php://stderr', $data . PHP_EOL);
}
