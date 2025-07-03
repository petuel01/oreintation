<?php
/**
 * Validate and sanitize an email address
 * @param string $email The email to validate
 * @return array [bool $isValid, string $sanitizedEmail, string $errorMessage]
 */
function validateEmail($email) {
    $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    $isValid = filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL) !== false;
    $errorMessage = $isValid ? '' : 'Invalid email format';
    
    return [$isValid, $sanitizedEmail, $errorMessage];
}

/**
 * Validate a password meets requirements
 * @param string $password The password to validate
 * @return array [bool $isValid, string $errorMessage]
 */
function validatePassword($password) {
    $isValid = strlen($password) >= 8;
    $errorMessage = $isValid ? '' : 'Password must be at least 8 characters';
    
    return [$isValid, $errorMessage];
}

/**
 * Sanitize a string input
 * @param string $input The input to sanitize
 * @return string The sanitized input
 */
function sanitizeString($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and sanitize an integer
 * @param mixed $input The input to validate
 * @return array [bool $isValid, int $sanitizedInt, string $errorMessage]
 */
function validateInt($input) {
    $sanitizedInt = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    $isValid = filter_var($sanitizedInt, FILTER_VALIDATE_INT) !== false;
    $errorMessage = $isValid ? '' : 'Invalid integer value';
    
    return [$isValid, $sanitizedInt, $errorMessage];
}