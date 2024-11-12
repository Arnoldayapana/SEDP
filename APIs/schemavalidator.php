<?php
header('Content-Type: application/json');

$response = [
    'status' => 'success',
    'data' => [
        'id' => 123,
        'name' => 'John Doe',
        'email' => 'john.doe@example.com',
    ],
];

echo json_encode($response);
