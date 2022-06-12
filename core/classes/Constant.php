<?php

namespace MyApp;

class Constant{
    public static $firstNameCharacters = "First Name must be between 2 and 25 characters";
    public static $lastNameCharacters = "Last Name must be between 2 and 25 characters";
    public static $UsernameCharacters = "Username must be between 2 and 25 characters";
    public static $PasswordCharacters = "Password must be between 5 and 25 characters";
    public static $UsernameTaken = "Username already exists";
    public static $EmailTaken = "Email already exists";
    public static $EmailInvalid = "Email is invalid";
    public static $PasswordNotAlphaNumeric = "Password not alphanumeric";
    public static $loginFailed = "Password or Username is incorrect";
}
