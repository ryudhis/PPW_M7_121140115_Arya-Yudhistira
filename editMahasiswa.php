<?php include "db_connect.php";
session_start();
// Edit data jika form edit disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nim_lama"]) && isset($_POST["action"]) && $_POST["action"] === "edit") {
    $nim_lama = $_POST["nim_lama"];
    $nama = $_POST["nama"];
    $nim_baru = $_POST["nim"];
    $prodi = $_POST["prodi"];

    // Periksa apakah NIM baru sudah ada dalam database (selain NIM yang sedang diubah)
    $check_nim_sql = "SELECT * FROM mahasiswa WHERE nim='$nim_baru' AND nim<>'$nim_lama'";
    $result_check_nim = $conn->query($check_nim_sql);

    if (
        $result_check_nim->num_rows > 0
    ) {
        echo "Error: NIM baru sudah ada dalam database.";
    } else {
        // Tambahkan data baru jika NIM belum ada
        $update_sql = "UPDATE mahasiswa SET nama='$nama', prodi='$prodi', nim='$nim_baru' WHERE nim='$nim_lama'";
        if ($conn->query($update_sql) === TRUE) {
            $_SESSION["logadd"] = "Data berhasil diupdate.";
            header("Location: index.php");
            exit();
        } else {
            $_SESSION["logadd"] = "Data gagal diupdate.";
            header("Location: index.php");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
