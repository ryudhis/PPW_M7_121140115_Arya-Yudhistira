<?php include "db_connect.php";
session_start();
// Hapus data jika parameter hapus di set
if (isset($_GET["hapus"])) {
    $nim_to_delete = $_GET["hapus"];
    $delete_sql = "DELETE FROM mahasiswa WHERE nim='$nim_to_delete'";
    if ($conn->query($delete_sql) === TRUE) {
        $_SESSION["logadd"] = "Data berhasil dimasukkan.";
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["logadd"] = "Data gagal dimasukkan.";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
