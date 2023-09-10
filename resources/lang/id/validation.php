<?php
// Example language file (resources/lang/en/validation.php)
return [
    'required' => 'Kolom harus diisi.',
    'email' => 'Email harus Valid',
    'numeric' => 'Kolom harus berupa angka',
    'min' => [
        'numeric' =>  'Kolom harus lebih dari :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'Kolom harus lebih dari :min.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'unique' => 'Kolom sudah pernah ada',
    'confirmed' => 'Kolom konfirmasi tidak sama',
    'same' => 'Kolom :other tidak sama'
    // Add more translations as needed
];
