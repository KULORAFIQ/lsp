<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku - User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include DataTables CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.10/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.10/js/jquery.dataTables.js"></script>
    
    <!-- Include jsPDF library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 80%;
            max-width: 800px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
            overflow: hidden; /* Menyembunyikan overflow agar shadow tetap terlihat */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
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

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Buku - User</h2>
        <div class="btn-container">
            <button class="btn btn-success" onclick="location.href='form_login.php'">Tambah Data</button>
            <button class="btn btn-primary" onclick="generatePDF()">Cetak PDF</button>
        </div>
        <table id="dataTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th>Kategori</th>
                    <th>No Telepon</th>
                    <th>Website</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['datatable'])) {
                    foreach ($_SESSION['datatable'] as $data) {
                        echo "<tr>";
                        foreach ($data as $value) {
                            echo "<td>$value</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data yang tersedia.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Initiate DataTables -->
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <!-- Include jQuery and Bootstrap JS files -->
    <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function untuk menghasilkan PDF dari data tabel
        function generatePDF() {
            // Inisialisasi objek jsPDF
            var pdf = new jsPDF();

            // Tambahkan judul pada PDF
            pdf.text("Data Buku - User", 14, 16);

            // Ambil data dari tabel
            var data = [];
            var columns = ["Buku", "Penerbit", "Tahun", "Kategori", "No Telepon", "Website"];

            <?php
            // Ambil data dari sesi PHP dan tambahkan ke dalam objek jsPDF
            if (isset($_SESSION['datatable'])) {
                foreach ($_SESSION['datatable'] as $data) {
                    echo "data.push([" . implode(",", array_map("json_encode", $data)) . "]);";
                }
            }
            ?>

            // Tambahkan data ke dalam PDF
            pdf.autoTable({
                head: [columns],
                body: data,
            });

            // Simpan atau tampilkan PDF
            pdf.save("Data_Buku_User.pdf");
        }
    </script>
</body>
</html>
