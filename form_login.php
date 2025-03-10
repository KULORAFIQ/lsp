<?php
session_start();

// Function to generate and display CAPTCHA
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

    // Return the CAPTCHA text
    return "$number1 $operation $number2 = ?";
}

// Generate and store the initial CAPTCHA on page load
$captchaText = generateCaptcha();

// Store the initial CAPTCHA text in the session for later verification
$_SESSION['initialCaptcha'] = $captchaText;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="styless.css">

</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="process_login.php">
            <div class="captcha-container">
                <label for="captcha">CAPTCHA:</label>
                <br>
                <span id="captcha-text"><?php echo $captchaText; ?></span>
                <button type="button" onclick="refreshCaptcha()">Refresh CAPTCHA</button>
                <br>
                <input type="text" id="captcha" name="captcha" required>
                <br>
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="captcha-container">
            <br>
            <a href='index.php'>Kembali</a>
            <br>
        </div>
    </div>

    <script>
        // Function to refresh CAPTCHA
        function refreshCaptcha() {
            // Generate a new CAPTCHA text
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('captcha-text').innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'refresh_captcha.php', true);
            xhr.send();
        }
    </script>
</body>
</html>
