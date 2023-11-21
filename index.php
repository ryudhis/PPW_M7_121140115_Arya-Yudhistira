<?php
include "db_connect.php";
session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prak M7</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .input-container {
            display: grid;
            row-gap: 20px;
            grid-template-columns: 5% 20%;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .add-button {
            cursor: pointer;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }

        .search-box {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        body {
            background-color: whitesmoke;
            font-family: Inter;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2),
                0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border: none;
            border-radius: 20px;
            padding: 50px;
            width: 65%;
            margin-top: 3em;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        select {
            margin-top: 20px;
            letter-spacing: 1px;
            font-weight: bold;
            cursor: pointer;
            background-color: #1e293b;
            padding: 10px 8px 10px 8px;
            border: none;
            border-radius: 6px;
            color: #f1f5f9;
        }

        input[type=submit] {
            margin-top: 20px;
            letter-spacing: 1px;
            font-weight: bold;
            cursor: pointer;
            background-color: #1e293b;
            padding: 10px 8px 10px 8px;
            border: none;
            border-radius: 6px;
            color: #f1f5f9;
        }

        input[type=submit]:hover {
            transform: scale(1.1);
            transition: all;
            transition-duration: 500;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2),
                0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        input[type=submit]:active {
            transform: scale(0.9);
            opacity: 0.7;
            transition: all;
            transition-duration: 500;
        }

        input {
            margin-top: -5px;
            padding: 11px;
            padding-left: 16px;
            border: none;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.2),
                0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Data Mahasiswa Kampus Tekotok</h2>
        <?php if (isset($_SESSION['logadd'])) { ?>
            <p class="logadd"><?php echo $_SESSION['logadd']; ?> </p>
        <?php
            $_SESSION['logadd'] = "";
        } ?>

        <?php

        if (isset($_GET["hapus"])) {
            $nim_to_delete = $_GET["hapus"];
            $delete_sql = "DELETE FROM mahasiswa WHERE nim='$nim_to_delete'";
            if ($conn->query($delete_sql) === TRUE) {
                $_SESSION["logadd"] = "Data berhasil dihapus.";
            } else {
                $_SESSION["logadd"] = "Data gagal dihapus.";
            }
        }

        // Query untuk mengambil data dari database
        $sql = "SELECT nama, nim, prodi FROM mahasiswa";

        // Filter data berdasarkan prodi jika dipilih
        if (isset($_GET['prodi_filter'])) {
            $prodi_filter = $_GET['prodi_filter'];
            if ($prodi_filter === "Semua Prodi") {
                $sql = "SELECT * FROM mahasiswa";
            } else {
                $sql .= " WHERE prodi='$prodi_filter'";
            }
        }

        $result = $conn->query($sql);

        // Tampilkan data dalam tabel HTML
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Nama</th><th>NIM</th><th>Prodi</th><th>Aksi</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["nama"] . "</td>
                        <td>" . $row["nim"] . "</td>
                        <td>" . $row["prodi"] . "</td>
                        <td>
                            <a href='?hapus=" . $row["nim"] . "'>Hapus</a> | 
                            <a href='javascript:void(0);' onclick='openModal(\"edit\", \"" . $row["nama"] . "\", \"" . $row["nim"] . "\", \"" . $row["prodi"] . "\")'>Edit</a>
                        </td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "Tidak ada data.";
        }

        // Tutup koneksi
        $conn->close();
        ?>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <form method="post" id="formMahasiswa">
                    <h3><span id="modalTitle">Tambah Data</span></h3>
                    <!-- Tambahkan input hidden untuk menyimpan aksi -->

                    <input type="hidden" name="action" id="action">
                    <div class="input-container">
                        <label for="nama">Nama:</label>
                        <input type="text" name="nama" id="nama" required>
                        <label for="nim">NIM:</label>
                        <input type="text" name="nim" id="nim" required>
                        <label for="prodi">Prodi:</label>
                        <input type="text" name="prodi" id="prodi" required>
                    </div>
                    <input type="hidden" name="nim_lama" id="nim_lama">
                    <input type="submit" value="Simpan">


                </form>

            </div>
        </div>

        <div class="add-button" onclick="openModal('add','', '', '');">Tambah Data</div>

        <div class="search-box">
            <h3>Cari Berdasarkan Prodi:</h3>
            <form method="get">
                <label for="prodi_filter">Pilih Prodi:</label>
                <select name="prodi_filter" id="prodi_filter">
                    <option value="Semua Prodi">Semua Prodi</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Teknik Kimia">Teknik Kimia</option>
                    <option value="Sains Data">Sains Data</option>
                    <option value="Teknik Fisika">Teknik Fisika</option>
                    <option value="Teknik Biomedis">Teknik Biomedis</option>
                    <option value="Teknologi Pangan">Teknologi Pangan</option>
                    <option value="Matematika">Matematika</option>
                    <option value="Manajemen">Manajemen</option>
                    <option value="Teknik Pinjam Dulu Seratus">Teknik Pinjam Dulu Seratus</option>
                </select>
                <input type="submit" value="Cari">
            </form>
        </div>
    </div>

    <script>
        function openModal(action, nama, nim, prodi) {
            document.getElementById("modalTitle").innerText = (action === 'edit') ? "Edit Data" : "Tambah Data";
            document.getElementById("formMahasiswa").action = `${action}Mahasiswa.php`;
            document.getElementById("myModal").style.display = "block";
            document.getElementById("action").value = action; // Tambahkan input hidden untuk menyimpan aksi (add atau edit)
            document.getElementById("nama").value = nama;
            document.getElementById("nim").value = nim;
            document.getElementById("prodi").value = prodi;
            document.getElementById("nim_lama").value = nim;
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById("myModal")) {
                closeModal();
            }
        }
    </script>


</body>

</html>