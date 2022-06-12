<?php

namespace MyApp;

class FormSanitizer {
    public static function sanitizeFormString($input) {
        $input = strip_tags($input);
        $input = trim($input);
        $input = strtolower($input);
        $input = ucfirst($input);
        return $input;
    }
    public static function sanitizeFormUsername($input) {
        $input = strip_tags($input);
        $input = trim($input);
        $input = strtolower($input);
        return $input;
    }
    public static function sanitizeFormEmail($input) {
        $input = htmlentities($input, ENT_QUOTES);
        $input = stripslashes($input);
        $input = trim($input);
        return $input;
    }
    public static function sanitizeFormPassword($input) {
        $input = strip_tags($input);
        return $input;
    }
}
