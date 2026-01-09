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

      if (empty($_POST['employees_id'])) {
        $error[] = 'Karyawan tidak boleh kosong';
      } else {
        $employees_id = mysqli_real_escape_string($connection, $_POST['employees_id']);
      }

      if (empty($_POST['bulan'])) {
        $error[] = 'Bulan tidak boleh kosong';
      } else {
        $bulan = mysqli_real_escape_string($connection, $_POST['bulan']);
      }

      if (empty($_POST['tahun'])) {
        $error[] = 'Tahun tidak boleh kosong';
      } else {
        $tahun = mysqli_real_escape_string($connection, $_POST['tahun']);
      }

      if (empty($_POST['gaji'])) {
        $error[] = 'Gaji tidak boleh kosong';
      } else {
        $gaji = mysqli_real_escape_string($connection, $_POST['gaji']);
      }

      // Optional fields dengan default 0
      $potongan_uang_hadir = isset($_POST['potongan_uang_hadir']) ? mysqli_real_escape_string($connection, $_POST['potongan_uang_hadir']) : 0;
      $potongan_keterlambatan = isset($_POST['potongan_keterlambatan']) ? mysqli_real_escape_string($connection, $_POST['potongan_keterlambatan']) : 0;
      $perhitungan_mangkir = isset($_POST['perhitungan_mangkir']) ? mysqli_real_escape_string($connection, $_POST['perhitungan_mangkir']) : 0;
      $konversi_alpa = isset($_POST['konversi_alpa']) ? mysqli_real_escape_string($connection, $_POST['konversi_alpa']) : 0;
      $konversi_izin = isset($_POST['konversi_izin']) ? mysqli_real_escape_string($connection, $_POST['konversi_izin']) : 0;
      $konversi_sakit_surat = isset($_POST['konversi_sakit_surat']) ? mysqli_real_escape_string($connection, $_POST['konversi_sakit_surat']) : 0;
      $konversi_sakit_tanpa_surat = isset($_POST['konversi_sakit_tanpa_surat']) ? mysqli_real_escape_string($connection, $_POST['konversi_sakit_tanpa_surat']) : 0;

      $tanggal = date('Y-m-d');

      if (empty($error)) {
        $add = "INSERT INTO gaji (
                  employees_id, 
                  gaji, 
                  potongan_uang_hadir, 
                  potongan_keterlambatan, 
                  perhitungan_mangkir, 
                  konversi_alpa, 
                  konversi_izin, 
                  konversi_sakit_surat, 
                  konversi_sakit_tanpa_surat, 
                  bulan, 
                  tahun, 
                  tanggal
                ) VALUES (
                  '$employees_id', 
                  '$gaji', 
                  '$potongan_uang_hadir', 
                  '$potongan_keterlambatan', 
                  '$perhitungan_mangkir', 
                  '$konversi_alpa', 
                  '$konversi_izin', 
                  '$konversi_sakit_surat', 
                  '$konversi_sakit_tanpa_surat', 
                  '$bulan', 
                  '$tahun', 
                  '$tanggal'
                )";

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

      if (empty($_POST['employees_id'])) {
        $error[] = 'Karyawan tidak boleh kosong';
      } else {
        $employees_id = mysqli_real_escape_string($connection, $_POST['employees_id']);
      }

      if (empty($_POST['bulan'])) {
        $error[] = 'Bulan tidak boleh kosong';
      } else {
        $bulan = mysqli_real_escape_string($connection, $_POST['bulan']);
      }

      if (empty($_POST['tahun'])) {
        $error[] = 'Tahun tidak boleh kosong';
      } else {
        $tahun = mysqli_real_escape_string($connection, $_POST['tahun']);
      }

      if (empty($_POST['gaji'])) {
        $error[] = 'Gaji tidak boleh kosong';
      } else {
        $gaji = mysqli_real_escape_string($connection, $_POST['gaji']);
      }

      // Optional fields
      $potongan_uang_hadir = isset($_POST['potongan_uang_hadir']) ? mysqli_real_escape_string($connection, $_POST['potongan_uang_hadir']) : 0;
      $potongan_keterlambatan = isset($_POST['potongan_keterlambatan']) ? mysqli_real_escape_string($connection, $_POST['potongan_keterlambatan']) : 0;
      $perhitungan_mangkir = isset($_POST['perhitungan_mangkir']) ? mysqli_real_escape_string($connection, $_POST['perhitungan_mangkir']) : 0;
      $konversi_alpa = isset($_POST['konversi_alpa']) ? mysqli_real_escape_string($connection, $_POST['konversi_alpa']) : 0;
      $konversi_izin = isset($_POST['konversi_izin']) ? mysqli_real_escape_string($connection, $_POST['konversi_izin']) : 0;
      $konversi_sakit_surat = isset($_POST['konversi_sakit_surat']) ? mysqli_real_escape_string($connection, $_POST['konversi_sakit_surat']) : 0;
      $konversi_sakit_tanpa_surat = isset($_POST['konversi_sakit_tanpa_surat']) ? mysqli_real_escape_string($connection, $_POST['konversi_sakit_tanpa_surat']) : 0;

      if (empty($error)) {
        $update = "UPDATE gaji SET 
                    employees_id='$employees_id', 
                    gaji='$gaji', 
                    potongan_uang_hadir='$potongan_uang_hadir', 
                    potongan_keterlambatan='$potongan_keterlambatan', 
                    perhitungan_mangkir='$perhitungan_mangkir', 
                    konversi_alpa='$konversi_alpa', 
                    konversi_izin='$konversi_izin', 
                    konversi_sakit_surat='$konversi_sakit_surat', 
                    konversi_sakit_tanpa_surat='$konversi_sakit_tanpa_surat', 
                    bulan='$bulan', 
                    tahun='$tahun' 
                  WHERE id='$id'";

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
      $deleted = "DELETE FROM gaji WHERE id='$id'";

      if ($connection->query($deleted) === true) {
        echo 'success';
      } else {
        echo 'Data tidak berhasil dihapus.!';
        die($connection->error . __LINE__);
      }
      break;
  }
}
