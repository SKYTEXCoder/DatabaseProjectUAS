<?php
$server = "localhost";
$user = "root";
$pass = "";
$table = "mahasiswa";
$dataname = "project_uas_basdat";

$conn = mysqli_connect($server, $user, $pass, $dataname);

if (!$conn) {
    die("Database Connection Failed, " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST["action"] ?? 'back';
    switch ($action) {
        case 'statistik_lima_serangkai':
            $sql = "SELECT MIN(ukt) AS Minimum, MAX(ukt) AS Maximum, SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(25/100 * (COUNT(*) + 1))), ',', -1) AS Q1, SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(50/100 * (COUNT(*) + 1))), ',', -1) AS Q2, SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(75/100 * (COUNT(*) + 1))), ',', -1) AS Q3 FROM mahasiswa";
            $result = mysqli_query($conn, $sql);
            $statistics = mysqli_fetch_assoc($result);
            $all_mahasiswas = [];
            break;

        case 'pencilan':
            $sql = "SELECT 
                m.id,
                m.nama,
                m.nim,
                m.alamat,
                m.prodi,
                m.ukt,
                CASE
                    WHEN m.ukt > (iqr.Q3 + 1.5 * iqr.IQR) THEN 'Pencilan Atas'
                    WHEN m.ukt < (iqr.Q1 - 1.5 * iqr.IQR) THEN 'Pencilan Bawah'
                END AS kategori_pencilan
            FROM mahasiswa m
            JOIN (
                SELECT 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(25/100 * (COUNT(*) + 1))), ',', -1) AS Q1,
                    SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(75/100 * (COUNT(*) + 1))), ',', -1) AS Q3,
                    (SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(75/100 * (COUNT(*) + 1))), ',', -1) - 
                    SUBSTRING_INDEX(SUBSTRING_INDEX(GROUP_CONCAT(ukt ORDER BY ukt), ',', ROUND(25/100 * (COUNT(*) + 1))), ',', -1)) AS IQR
                FROM mahasiswa
            ) iqr ON 1=1
            WHERE m.ukt > (iqr.Q3 + 1.5 * iqr.IQR) OR m.ukt < (iqr.Q1 - 1.5 * iqr.IQR)
            LIMIT 0, 50";
            $result = mysqli_query($conn, $sql);
            $pencilan = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $all_mahasiswas = [];
            break;

        case 'standard_deviation':
            $sql = "SELECT STDDEV(ukt) AS ukt_standard_deviation FROM mahasiswa";
            $result = mysqli_query($conn, $sql);
            $stddev = mysqli_fetch_assoc($result)['ukt_standard_deviation'];
            $all_mahasiswas = [];
            break;

        case 'register':
            register();
            break;

        case 'back':
        default:
            $sql = "SELECT * FROM mahasiswa ORDER BY id ASC";
            $result = mysqli_query($conn, $sql);
            $all_mahasiswas = mysqli_fetch_all($result, MYSQLI_ASSOC);
            break;
    }
} else {
    $sql = "SELECT * FROM mahasiswa ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);
    $all_mahasiswas = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function register(): void
{

    global $conn, $table;

    $nama = trim($_POST['nama']);
    $nim = $_POST['nim'];
    $alamat = $_POST['alamat'];
    $prodi = $_POST['prodi'];
    $ukt = intval($_POST['ukt']);

    $submitSQL = "INSERT INTO $table (nama, nim, alamat, prodi, ukt) VALUES ('$nama', '$nim', '$alamat', '$prodi', $ukt)";

    if (mysqli_query($conn, $submitSQL)) {
        header('Location: index.php');
    } else {
        echo "Error: " . $submitSQL . "<br>" . $conn->error;
        die;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form</title>
    <style>
        body {
            background: url(images/unj2.jpg) no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        .form-container {
            width: 400px;
            padding: 20px;
            background-color: rgba(0, 115, 0, 0.5);
            /* Green with transparency */
            backdrop-filter: blur(10px);
            border-radius: 10px;
            margin: 50px auto;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            color: #D9D9D9;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: white
        }

        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            opacity: 0.95;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #00C000;
            /* Bright green */
            border: none;
            border-radius: 5px;
            color: black;
            font-size: 14px;
            font-weight: 550;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .form-container button:hover {
            background-color: #00a344;
            transform: scale(0.95);
            transition: transform 0.3s ease;
        }

        .gap {
            margin-bottom: 50px;
            /* Creates a gap below the submit button */
        }

        .query-title {
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .table-container {
            width: 80%;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #00C000;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Form Registrasi Mahasiswa</h2>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="register" autocorrect="off" autocomplete="off"
                autocapitalize="off" required>
            <input type="text" name="nama" pattern="^[a-zA-Z\s]+$" title="Nama hanya boleh berisi huruf dan spasi."
                placeholder="Nama Lengkap" required>
            <input type="text" name="nim" pattern="^\d+$" title="NIM hanya boleh berisi angka."
                placeholder="Nomor Induk Mahasiswa" required>
            <input type="text" pattern="^[a-zA-Z\s]+$" title="Program Studi hanya boleh berisi huruf dan spasi."
                name="prodi" placeholder="Program Studi" required>
            <input type="text" name="alamat" placeholder="Alamat" required>
            <input type="text" name="ukt" pattern="^\d+$" title="UKT hanya boleh berisi angka."
                placeholder="Uang Kuliah Tunggal (UKT)" required>
            <button type="submit" class="gap">SUBMIT</button>
        </form>
        <div class="query-title">Pilih Query</div>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="statistik_lima_serangkai">
            <button type="submit">Statistik 5 Serangkai</button>
        </form>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="pencilan">
            <button type="submit">Data Pencilan Atas dan Bawah</button>
        </form>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="standard_deviation">
            <button type="submit">Standar Deviasi</button>
        </form>
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="back">
            <button type="submit">Back to Data Mahasiswa</button>
        </form>
    </div>

    <div class="table-container">
        <h2 style="text-align: center;">
            <?php
            if (isset($statistics)) {
                echo "Statistik 5 Serangkai";
            } elseif (isset($stddev)) {
                echo "Standar Deviasi";
            } elseif (isset($pencilan)) {
                echo "Data Pencilan Atas dan Bawah";
            } else {
                echo "Data Mahasiswa";
            }
            ?>
        </h2>
        <table>
            <thead>
                <tr>
                    <?php
                    if (isset($statistics)) {
                        echo "<th>Minimum UKT</th>";
                        echo "<th>Q1</th>";
                        echo "<th>Q2</th>";
                        echo "<th>Q3</th>";
                        echo "<th>Maximum UKT</th>";
                    } elseif (isset($stddev)) {
                        echo "<th>Standar Deviasi UKT</th>";
                    } elseif (isset($pencilan)) {
                        echo "<th>ID</th>";
                        echo "<th>Nama</th>";
                        echo "<th>NIM</th>";
                        echo "<th>Program Studi</th>";
                        echo "<th>Alamat</th>";
                        echo "<th>UKT</th>";
                        echo "<th>Kategori Pencilan</th>";
                    } else {
                        echo "<th>ID</th>";
                        echo "<th>Nama</th>";
                        echo "<th>NIM</th>";
                        echo "<th>Program Studi</th>";
                        echo "<th>Alamat</th>";
                        echo "<th>UKT</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($statistics)) {
                    // Display Statistik 5 Serangkai
                    echo "<tr><td>Rp " . number_format($statistics['Minimum'], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($statistics['Q1'], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($statistics['Q2'], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($statistics['Q3'], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($statistics['Maximum'], 0, ',', '.') . "</td></tr>";
                } elseif (isset($stddev)) {
                    // Display Standard Deviation
                    echo "<tr><td>Rp " . number_format($stddev, 12, ',', '.') . "</td></tr>";
                } elseif (isset($pencilan)) {
                    // Display rows from mahasiswa or pencilan
                    foreach ($pencilan as $pencilan_mahasiswa) {
                        echo "<tr>
                                <td>" . htmlspecialchars(ucwords(strtolower($pencilan_mahasiswa['id']))) . "</td>
                                <td>" . htmlspecialchars($pencilan_mahasiswa['nama']) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($pencilan_mahasiswa['nim']))) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($pencilan_mahasiswa['prodi']))) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($pencilan_mahasiswa['alamat']))) . "</td>
                                <td>Rp " . number_format($pencilan_mahasiswa['ukt'], 0, ',', '.') . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($pencilan_mahasiswa['kategori_pencilan']))) . "</td>
                            </tr>";
                    }
                } else {
                    foreach ($all_mahasiswas as $mahasiswa) {
                        echo "<tr>
                                <td>" . htmlspecialchars(ucwords(strtolower($mahasiswa['id']))) . "</td>
                                <td>" . htmlspecialchars($mahasiswa['nama']) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($mahasiswa['nim']))) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($mahasiswa['prodi']))) . "</td>
                                <td>" . htmlspecialchars(ucwords(strtolower($mahasiswa['alamat']))) . "</td>
                                <td>Rp " . number_format($mahasiswa['ukt'], 0, ',', '.') . "</td>
                            </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function handleStatistik() {
            alert('Statistik 5 Serangkai functionality will be implemented here.');
        }

        function handlePencilan() {
            alert('Data Pencilan Atas dan Bawah functionality will be implemented here.');
        }

        function handleStandarDeviasi() {
            alert('Standar Deviasi functionality will be implemented here.');
        }

        function handleBackToDatabase() {
            alert('Back TO Database functionality will be implemented here.');
        }
    </script>
</body>

</html>