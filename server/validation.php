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

function checkPassword($field){
    if (isset($_POST[$field]) ) {
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

function checkUsername($field){
    if (isset($_POST[$field]) ) {

        if ($_POST[$field] != ""){ 

            return $_POST[$field];
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function checkPost($topic){
    if (isset($_POST[$topic]) ) {

        if ($_POST[$topic] != ""){ 

            return $_POST[$topic];
        } else {
            return false;
        }
    } else {
        return false;
    }
}
