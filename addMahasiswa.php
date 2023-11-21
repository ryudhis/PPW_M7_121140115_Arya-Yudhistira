<?php include "db_connect.php";
session_start();
// Tambahkan data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] === "add") {
    $nama = $_POST["nama"];
    $nim = $_POST["nim"];
    $prodi = $_POST["prodi"];

    // Periksa apakah NIM sudah ada
    $check_nim_sql = "SELECT * FROM mahasiswa WHERE nim='$nim'";
    $result_check_nim = $conn->query($check_nim_sql);

    if ($result_check_nim->num_rows > 0) {
        echo "Error: NIM sudah ada dalam database.";
    } else {
        // Tambahkan data baru jika NIM belum ada
        $insert_sql = "INSERT INTO mahasiswa (nama, nim, prodi) VALUES ('$nama', '$nim', '$prodi')";
        if ($conn->query($insert_sql) === TRUE) {
            $_SESSION["logadd"] = "Data berhasil dimasukkan.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["logadd"] = "Data gagal dimasukkan.";
            header("Location: index.php");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
