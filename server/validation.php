<?php

include_once "console_log.php";
#ref : https://www.phptutorial.net/php-tutorial/php-sanitize-input/
#ref : https://www.phptutorial.net/php-tutorial/php-filter_input/
#ref : https://www.php.net/manual/en/filter.filters.php
#ref : https://www.php.net/manual/en/function.password-hash.php
#ref : https://www.w3schools.com/php/func_string_strtolower.asp

function checkEmail($field){
    if (isset($_POST[$field])) {
        $sanField = filter_var($_POST[$field], FILTER_VALIDATE_EMAIL);
        
        if ($sanField != ""){

            return strtolower($sanField);
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkPassword($field ,$minPassLength){
    if (isset($_POST[$field]) and strlen($_POST[$field]) >= $minPassLength ) {
        $sanField = filter_var($_POST[$field], FILTER_SANITIZE_EMAIL);
        
        if ($sanField != ""){ 

            return $sanField;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkUsername($field, $minUserLength){
    if (isset($_POST[$field]) and strlen($_POST[$field]) >= $minUserLength ) {

        if ($_POST[$field] != ""){ 

            return $_POST[$field];
        } else {
            return false;
        }
    } else {
        return false;
    }
}
