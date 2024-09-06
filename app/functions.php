<?php

if (!function_exists('generate_unique_pin')) {
    function generate_unique_pin(int $length = 6): string
    {
        return substr(hexdec(uniqid()), -$length);
    }
}
