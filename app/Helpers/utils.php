<?php

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 8)
    {
        $characters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $serialNumber = '';
        for ($i = 0; $i < $length; $i++) {
            $serialNumber .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $serialNumber;
    }
}


if (!function_exists('normalizeNumber')) {
    function normalizeNumber($number, $add_zero = 1) {
        // ## Menghilangkan semua nol di depan
        $result = ltrim($number, '0');

        // ## Jika setelah dihilangkan nol semua, hasilnya kosong, set menjadi '0'
        if (empty($result)) {
            $result = '0';
        }

        // ## Menambahkan nol di depan jika hanya terdapat 1 digit
        $result = str_pad($result, $add_zero + 1, '0', STR_PAD_LEFT);

        return $result;
    }
}

if (!function_exists('formatStringRole')) {
    function formatStringRole($input) {
        // ## Mengganti underscore dengan spasi dan memisahkan string berdasarkan spasi
        $words = explode(' ', str_replace('_', ' ', $input));
        
        // ## Mengubah setiap kata menjadi kapital
        $formattedWords = array_map('ucwords', $words);
        
        // ## Menggabungkan kembali kata-kata yang telah diformat
        return implode(' ', $formattedWords);
    }
}
