<?php
session_start();

// Function to generate and store new CAPTCHA in session
function generateCaptcha() {
    // Generate random numbers and operation
    $number1 = rand(1, 10);
    $number2 = rand(1, 10);
    $operations = ['+', '-', '*'];
    $operation = $operations[array_rand($operations)];

    // Calculate the result
    switch ($operation) {
        case '+':
            $result = $number1 + $number2;
            break;
        case '-':
            $result = $number1 - $number2;
            break;
        case '*':
            $result = $number1 * $number2;
            break;
    }

    // Store the result in the session for validation
    $_SESSION['captcha'] = $result;

    // Return the new CAPTCHA text
    echo "$number1 $operation $number2 = ?";
}

// Generate and output new CAPTCHA
generateCaptcha();
?>
