<?php

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Buku.class.php");
include("includes/Member.class.php");
include("includes/Peminjaman.class.php");

$buku = new Buku($db_host, $db_user, $db_pass, $db_name);
$buku->open();
$member = new Member($db_host, $db_user, $db_pass, $db_name);
$member->open();
$peminjaman = new Peminjaman($db_host, $db_user, $db_pass, $db_name);
$peminjaman->open();
$peminjaman->getPeminjaman();

if (isset($_POST['add'])) {
    //memanggil add
    $peminjaman->add($_POST);
    header("location: peminjaman.php");
}

if (!empty($_GET['id_hapus'])) {
    //memanggil delete
    $peminjaman->delete($_GET['id_hapus']);
    header("location: peminjaman.php");
}

if (!empty($_GET['id_edit'])) {
    //memanggil update
    $peminjaman->statusPeminjaman($_GET['id_edit']);
    header("location: peminjaman.php");
}

$data = NULL;
$no = 1;

while (list($id_peminjaman, $nim, $id_buku, $status) = $peminjaman->getResult()) {
    $member->getMemberDetail($nim);
    $dataMember = $member->getResult();
    
    $buku->getBukuDetail($id_buku);
    $dataBuku = $buku->getResult();

    if ($status == "Dikembalikan") {
        $data .= "
            <tr>
                <td>". $no ."</td>
                <td>". $dataMember['nama'] ."</td>
                <td>". $dataBuku['judul_buku'] ."</td>
                <td>". $status ."</td>
                <td>
                    <a href='peminjaman.php?id_hapus=". $id_peminjaman ."' class='btn btn-danger'>Hapus</a>
                </td>
            </tr>";
    }else {
        $data .= "
            <tr>
                <td>". $no ."</td>
                <td>". $dataMember['nama'] ."</td>
                <td>". $dataBuku['judul_buku'] ."</td>
                <td>". $status ."</td>
                <td>
                    <a href='peminjaman.php?id_edit=". $id_peminjaman ."' class='btn btn-warning'>Kembali</a>
                    <a href='peminjaman.php?id_hapus=". $id_peminjaman ."' class='btn btn-danger'>Hapus</a>
                </td>
            </tr>";
    }
    $no++;
}

$dataForm = NULL;
$dataForm .="
    <div class='form-row'>
        <div class='form-group col'>
            <label for='nama'>Peminjam</label>
            <select class='custom-select form-control' name='nama'>
                <option selected>Open this select menu</option>
                DATA_PEMINJAM
            </select>
        </div>
    </div>
    <div class='form-row'>
        <div class='form-group col'>
            <label for='judul_buku'>Judul Buku</label>
            <select class='custom-select form-control' name='judul_buku'>
                <option selected>Open this select menu</option>
                DATA_BUKU
            </select>
        </div>
    </div>
    <button type='submit' name='add' class='btn btn-primary mt-3'>Add</button>
";

$dataPeminjam = NULL;
$member->getMember();

while (list($nim, $nama, $jurusan) = $member->getResult()) {
    $dataPeminjam .= "<option value='".$nim."'>".$nama."</option>";
}

$dataBuku = NULL;
$buku->getBuku();

while (list($id_buku, $judul, $penerbit, $deskripsi, $status, $id_author) = $buku->getResult()) {
    $dataBuku .= "<option value='".$id_buku."'>".$judul."</option>";
}

$buku->close();
$member->close();
$peminjaman->close();
$tpl = new Template("templates/peminjaman.html");
$tpl->replace("DATA_FORM", $dataForm);
$tpl->replace("DATA_PEMINJAM", $dataPeminjam);
$tpl->replace("DATA_BUKU", $dataBuku);
$tpl->replace("DATA_TABEL", $data);
$tpl->write();

?>