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

// Inisialisasi CAPTCHA saat halaman pertama kali dimuat
if (!isset($_SESSION['math_captcha'])) {
    generateMathCaptcha();
}

// Fungsi untuk menyimpan data ke dalam datatable
function saveToDataTable($data) {
    $_SESSION['datatable'][] = $data;
}

// Fungsi untuk menghapus data dari datatable
function deleteFromDataTable($index) {
    if (isset($_SESSION['datatable'][$index])) {
        unset($_SESSION['datatable'][$index]);
        $_SESSION['datatable'] = array_values($_SESSION['datatable']); // Reset array keys
    }
}

// Proses form saat tombol submit ditekan atau delete ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is for submitting data
    if (isset($_POST['submit'])) {
        // Validasi CAPTCHA
        if (isset($_POST['captcha'])) {
            $userInput = $_POST['captcha'];
            $mathCaptchaResult = null;

            // Evaluasi hasil CAPTCHA
            switch ($_SESSION['math_captcha']['operator']) {
                case '+':
                    $mathCaptchaResult = $_SESSION['math_captcha']['number1'] + $_SESSION['math_captcha']['number2'];
                    break;
                case '-':
                    $mathCaptchaResult = $_SESSION['math_captcha']['number1'] - $_SESSION['math_captcha']['number2'];
                    break;
                case '*':
                    $mathCaptchaResult = $_SESSION['math_captcha']['number1'] * $_SESSION['math_captcha']['number2'];
                    break;
            }

            if ($mathCaptchaResult !== null && $mathCaptchaResult == $userInput) {
                // CAPTCHA valid, simpan data
                $buku = $_POST['buku'];
                $penerbit = $_POST['penerbit'];
                $tahun = $_POST['tahun'];
                $kategori = $_POST['kategori'];
                $noTelepon = $_POST['no_telepon'];
                $website = $_POST['website'];

                // Data valid, simpan ke datatable
                $data = array(
                    'Buku' => $buku,
                    'Penerbit' => $penerbit,
                    'Tahun' => $tahun,
                    'Kategori' => $kategori,
                    'No Telepon' => $noTelepon,
                    'Website' => $website
                );

                saveToDataTable($data);

                // Generate pertanyaan matematika baru untuk form selanjutnya
                generateMathCaptcha();
            } else {
                // CAPTCHA tidak valid, tampilkan pesan error
                echo "<script>alert('CAPTCHA tidak valid!');</script>";
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Delete action
        $indexToDelete = (int)$_POST['delete'];
        deleteFromDataTable($indexToDelete);
    }
}
?>

<!-- HTML Form dan Tabel -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Buku</title>
    <link rel="stylesheet" href="style.css">
    <!-- Include DataTables CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.10/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 10px;
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button.logout {
            background-color: #f44336;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4caf50;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .delete-btn {
            background-color: #f44336;
            color: #fff;
            border: none;
            padding: 8px;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Form Buku</h2>
        <form method="post" action="" onsubmit="return validateAndRefreshCaptcha();">
            <label for="buku">Buku:</label>
            <input type="text" name="buku" required>

            <label for="penerbit">Penerbit:</label>
            <input type="text" name="penerbit"   required>

            <label for="tahun">Tahun:</label>
            <input type="date" name="tahun" required>

            <label for="kategori">Kategori:</label>
            <input type="text" name="kategori" required>

            <label for="no_telepon">No Telepon:</label>
            <input type="text" name="no_telepon" required>

            <label for="website">Website:</label>
            <input type="text" name="website" required>

            <label for="captcha">CAPTCHA:</label>
            <span id="math-captcha"><?php echo generateMathCaptcha(); ?></span>
            <input type="text" id="captcha" name="captcha" required>

            <button type="submit" name="submit">Submit</button>
            <button class="logout" onclick="location.href='index.php'">Logout</button>
        </form>
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>No Telepon</th>
                    <th>Website</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['datatable'])) {
                    foreach ($_SESSION['datatable'] as $index => $data) {
                        echo "<tr>";
                        foreach ($data as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "<td>
                                <button class='delete-btn' type='button' onclick='confirmDelete($index)'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                } 
                ?>
            </tbody>
        </table>
    </div>
    <script>
        // Fungsi untuk mengonfirmasi sebelum menghapus
        function confirmDelete(index) {
            var confirmation = confirm("Apakah Anda yakin ingin menghapus data ini?");
            if (confirmation) {
                // Setelah dikonfirmasi, submit form delete
                var form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete';
                input.value = index;
                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

