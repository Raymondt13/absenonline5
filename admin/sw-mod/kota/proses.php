<?php
session_start();
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
  header('location:../../login/');
  exit;
} else {
  require_once '../../../sw-library/sw-config.php';
  require_once '../../login/login_session.php';
  include('../../../sw-library/sw-function.php');

  switch (@$_GET['action']) {
    case 'add':

      $error = array();

      if (empty($_POST['namakota'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $namakota = mysqli_real_escape_string($connection, $_POST['namakota']);
      }

      if (empty($error)) {

        $add = "INSERT INTO city (namakota) values('$namakota')";
        if ($connection->query($add) === false) {
          die($connection->error . __LINE__);
          echo 'Data tidak berhasil disimpan!';
        } else {
          echo 'success';
        }
      } else {
        echo 'Bidang inputan masih ada yang kosong..!';
      }
      break;

    /* ------------------------------
    Update
---------------------------------*/
    case 'update':
      $error = array();
      if (empty($_POST['id'])) {
        $error[] = 'ID tidak boleh kosong';
      } else {
        $id = mysqli_real_escape_string($connection, $_POST['id']);
      }

      if (empty($_POST['namakota'])) {
        $error[] = 'tidak boleh kosong';
      } else {
        $namakota = mysqli_real_escape_string($connection, $_POST['namakota']);
      }

      if (empty($error)) {
        $update = "UPDATE city SET namakota='$namakota' WHERE city_id='$id'";
        if ($connection->query($update) === false) {
          die($connection->error . __LINE__);
          echo 'Data tidak berhasil disimpan!';
        } else {
          echo 'success';
        }
      } else {
        echo 'Bidang inputan tidak boleh ada yang kosong..!';
      }

      break;

    /* --------------- Delete ------------*/
    case 'delete':
      $id = mysqli_real_escape_string($connection, epm_decode($_POST['id']));

      // Cek apakah kota masih digunakan di tabel lain (sesuaikan dengan relasi database Anda)
      // Contoh: cek di tabel employees atau building jika ada foreign key ke city
      // $query ="SELECT city_id FROM employees WHERE city_id='$id'";
      // $result = $connection->query($query);

      // Jika tidak ada relasi, langsung hapus
      $deleted = "DELETE FROM city WHERE city_id='$id'";
      if ($connection->query($deleted) === true) {
        echo 'success';
      } else {
        echo 'Data tidak berhasil dihapus.!';
        die($connection->error . __LINE__);
      }

      /* Jika ada relasi, gunakan kode ini:
  if(!$result->num_rows > 0){
    $deleted = "DELETE FROM city WHERE city_id='$id'";
    if($connection->query($deleted) === true) {
        echo'success';
    } else { 
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
    }
  } else {
      echo'Kota masih digunakan, Data tidak dapat dihapus.!';
  }
  */
      break;
  }
}
