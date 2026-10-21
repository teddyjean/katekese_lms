<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Akun Administrator Bawaan
    |--------------------------------------------------------------------------
    |
    | Digunakan oleh AdminSeeder untuk membuat/memperbarui satu akun dengan
    | role administrator (akses global ke semua data). Isi ADMIN_EMAIL dan
    | ADMIN_PASSWORD di .env server produksi dengan nilai yang kuat dan rahasia
    | -- jangan biarkan nilai default ini terpakai di luar lingkungan lokal.
    |
    */

    'email' => env('ADMIN_EMAIL', 'admin@gerejakalasan.org'),

    'password' => env('ADMIN_PASSWORD', 'admin123'),

];
