<?php
session_start();

// Fungsi untuk menghasilkan pertanyaan matematika sebagai CAPTCHA
function generateMathCaptcha() {
    $operators = ['+', '-', '*'];
    $operator = $operators[array_rand($operators)];
    $number1 = rand(1, 10);
    $number2 = rand(1, 10);

    $_SESSION['math_captcha'] = compact('number1', 'number2', 'operator');

    switch ($operator) {
        case '+':
            return "$number1 + $number2 = ?";
        case '-':
            return "$number1 - $number2 = ?";
        case '*':
            return "$number1 * $number2 = ?";
        default:
            return ''; // Invalid operator
    }
}

// Generate pertanyaan matematika baru untuk form selanjutnya
echo json_encode(['captcha' => generateMathCaptcha()]);
?>
