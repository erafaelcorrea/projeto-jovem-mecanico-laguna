<?php

class Request
{
    public static function input($key)
    {
        // tenta POST normal primeiro
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        // tenta JSON
        $json = json_decode(file_get_contents("php://input"), true);

        return $json[$key] ?? null;
    }
}