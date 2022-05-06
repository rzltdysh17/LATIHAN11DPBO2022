<?php

class Peminjaman extends DB
{
    function getPeminjaman()
    {
        $query = "SELECT * FROM peminjaman";

        // Mengeksekusi query
        return $this->execute($query);
    }

    function add($data)
    {
        $nama = $data['nama'];
        $judul_buku = $data['judul_buku'];
        $status = "Dipinjam";

        $query = "insert into peminjaman values ('', '$nama', '$judul_buku', '$status')";

        // Mengeksekusi query
        return $this->execute($query);
    }

    function delete($id)
    {
        $query = "delete from peminjaman WHERE id_peminjaman = '$id'";

        // Mengeksekusi query
        return $this->execute($query);
    }
    
    function statusPeminjaman($id){
        $status = "Dikembalikan";

        $query = "update peminjaman set status = '$status' WHERE id_peminjaman = '$id'";
        
        // Mengeksekusi query
        return $this->execute($query);
    }
}
?>