<?php session_start();
error_reporting(0);
require_once '../../../sw-library/sw-config.php';
require_once '../../../sw-library/sw-function.php';
include_once '../../../sw-library/vendor/autoload.php';
if (empty($_SESSION['SESSION_USER']) && empty($_SESSION['SESSION_ID'])) {
  //Kondisi tidak login
  header('location:../login/');
} else {
  //kondisi login
  switch (@$_GET['action']) {
    /* -------  CETAK PDF-----------------------------------------------*/
    case 'pdf':
      if (empty($_GET['id'])) {
        $error[] = 'ID tidak boleh kosong';
      } else {
        $id = mysqli_real_escape_string($connection, $_GET['id']);
      }

      if (empty($error)) {
        $query = "SELECT employees.id,employees.employees_name,employees.position_id,position.position_name,employees.shift_id FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$id'";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
          $row            = $result->fetch_assoc();
          $employees_name = $row['employees_name'];

          if (isset($_GET['from']) or isset($_GET['to'])) {
            $bulan   = date($_GET['from']);
          } else {
            $bulan  = date("m");
          }
          $hari       = date("d");
          $tahun      = date("Y");
          $jumlahhari = date("t", mktime(0, 0, 0, $bulan, $hari, $tahun));
          $s          = date("w", mktime(0, 0, 0, $bulan, 1, $tahun));
          $mpdf = new \Mpdf\Mpdf();
          ob_start();

          $mpdf->SetHTMLFooter('
      <table width="100%" style="border-top:solid 1px #333;font-size:11px;">
          <tr>
              <td width="60%" style="text-align:left;">Simpanlah lembar Absensi ini.</td>
              <td width="35%" style="text-align: right;">Dicetak tanggal ' . tgl_indo($date) . '</td>
          </tr>
      </table>');
          echo '<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi ' . $employees_name . '</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{line-height:25px;font-size:20px;margin:0px 0 10px;text-transform:uppercase}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{font-size:30px}table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}
    .label {display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap; vertical-align: baseline;border-radius: .25em;}
    .label-success{background-color: #00a65a !important;}.label-warning {background-color: #f0ad4e;}.label-info {background-color: #5bc0de;}.label-danger{background-color: #dd4b39 !important;}
    p{line-height:20px;padding:0px;margin: 5px;}.pull-right{float:right}
    </style>
</head>
<body>';
          echo '
    <section class="container_box">
      <div class="row">';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            echo '<h3 class="text-center">LAPORAN DETAIL HARIAN<br>PERIODE WAKTU ' . tanggal_indo($_GET['from']) . ' - ' . $_GET['to'] . '</h3>';
          } else {
            echo '<h3 class="text-center">LAPORAN DETAIL BULAN<br>' . tanggal_indo($month) . ' - ' . $year . '</h3>';
          }
          echo '
        <p>Nama   : ' . $row['employees_name'] . '</p>
        <p>Jabatan : ' . $row['position_name'] . '</p><br>
      <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th class="text-center">Jam Masuk</th>
              <th class="text-center">Scan Masuk</th>
              <th>Terlambat</th>
              <th class="text-center">Jam Pulang</th>
              <th class="text-center">Scan Pulang</th>
              <th class="text-center">Pulang Cepat</th>
              <th>Durasi</th>
              <th>Lembur</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';
          for ($d = 1; $d <= $jumlahhari; $d++) {
            $warna      = '';
            $background = '';
            $status     = 'Tidak Hadir';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              $warna = 'white';
              $background = '#FF0000';
              $status = 'Libur Akhir Pekan';
            }
            $date_month_year = '' . $year . '-' . $bulan . '-' . $d . '';

            if (isset($_GET['from']) or isset($_GET['to'])) {
              $month = $_GET['from'];
              $year  = $_GET['to'];
              $filter = "employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
            } else {
              $filter = "employees_id='$id' AND  presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
            }


            $query_shift = "SELECT time_in,time_out FROM shift WHERE shift_id='$row[shift_id]'";
            $result_shift = $connection->query($query_shift);
            $row_shift = $result_shift->fetch_assoc();
            $shift_time_in = $row_shift['time_in'];
            $shift_time_out = $row_shift['time_out'];
            $newtimestamp = strtotime('' . $shift_time_in . ' + 05 minute');
            $newtimestamp = date('H:i:s', $newtimestamp);

            $query_absen = "SELECT presence_id,presence_date,time_in,time_out,picture_in,picture_out,present_id,latitude_longtitude_in,information,TIMEDIFF(TIME(time_in),'$shift_time_in') AS selisih,if (time_in>'$shift_time_in','Telat',if(time_in='00:00:00','Tidak Masuk','Tepat Waktu')) AS status,TIMEDIFF(TIME(time_out),'$shift_time_out') AS selisih_out FROM presence WHERE $filter ORDER BY presence_id DESC";
            $result_absen = $connection->query($query_absen);
            $row_absen = $result_absen->fetch_assoc();

            $querya = "SELECT present_id,present_name FROM present_status WHERE present_id='$row_absen[present_id]'";
            $resulta = $connection->query($querya);
            $rowa =  $resulta->fetch_assoc();

            if ($row_absen['time_in'] == NULL) {
              if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
                $status = 'Libur Akhir Pekan';
              } else {
                $status = '<span class="label label-danger">Tidak Hadir</span>';
              }
              $time_in = $row_absen['time_in'];
            } else {
              $status = $rowa['present_name'];
              $time_in = $row_absen['time_in'];
            }

            // Status Absensi Jam Masuk
            if ($row_absen['status'] == 'Telat') {
              $status_time_in = '<label class="label label-danger pull-right">' . $row_absen['status'] . '</label>';
            } elseif ($row_absen['status'] == 'Tepat Waktu') {
              $status_time_in = '<label class="label label-info pull-right">' . $row_absen['status'] . '</label>';
              $terlamat   = '';
            } else {
              $status_time_in = '';
              $terlamat   = '';
            }

            // DURASI KERJA  =========================================
            $durasi_kerja_start = strtotime('' . $date_month_year . ' ' . $row_absen['time_in'] . '');
            $durasi_kerja_end   = strtotime('' . $date_month_year . ' ' . $row_absen['time_out'] . '');
            $diff          = $durasi_kerja_end - $durasi_kerja_start;
            $durasi_jam       = floor($diff / (60 * 60));
            $durasi_menit     = $diff - ($durasi_jam * (60 * 60));
            $durasi_detik     = $diff % 60;
            $durasi_kerja     = '' . $durasi_jam . ' jam, ' . floor($durasi_menit / 60) . ' menit';

            // JAM LEMBUR =========================================
            if ($row_absen['time_out'] > $shift_time_out) {
              $lembur_kerja_start = strtotime('' . $date_month_year . ' ' . $shift_time_out . '');
              $lembur_kerja_end   = strtotime('' . $date_month_year . ' ' . $row_absen['time_out'] . '');
              $diff          = $lembur_kerja_end - $lembur_kerja_start;
              $lembur_jam       = floor($diff / (60 * 60));
              $lembur_menit     = $diff - ($lembur_jam * (60 * 60));
              $lembur       = '' . $lembur_jam . ' jam, ' . floor($lembur_menit / 60) . ' menit';
            } else {
              $lembur = '';
            }
            echo '
         <tr style="background:' . $background . ';color:' . $warna . '">
            <td class="text-center">' . $d . '</td>
            <td>' . format_hari_tanggal($date_month_year) . '</td>';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_in'] == '') {
                echo '
                <td class="text-center" colspan="3">Libur Akhir Pekan</td>';
              } else {
                echo '
                <td class="text-center">' . $row_absen['time_in'] . '</td>
                <td class="text-center">' . $row_absen['time_in'] . '</td>
              	<td class="text-center">' . $row_absen['selisih'] . '</td>';
              }
            } else {
              echo '
              <td class="text-center">' . $shift_time_in . '</td>
              <td class="text-center">' . $row_absen['time_in'] . '</td>
              <td class="text-center">' . $row_absen['selisih'] . '</td>';
            }

            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_out'] == '') {
                echo '
                <td class="text-center" colspan="3">Libur Akhir Pekan</td>';
              } else {
                echo '
                <td class="text-center">' . $row_shift['time_out'] . '</td>
              	<td class="text-center">' . $row_absen['time_out'] . '</td>
              	<td class="text-center">' . $row_absen['selisih_out'] . '</td>';
              }
            } else {
              echo '
              <td class="text-center">' . $row_shift['time_out'] . '</td>
              <td class="text-center">' . $row_absen['time_out'] . '</td>
              <td class="text-center">' . $row_absen['selisih_out'] . '</td>';
            }
            echo '
              <td>' . $durasi_kerja . '</td>
              <td>' . $lembur . '</td>
              <td>' . $status . ' ' . $status_time_in . '</td>
              <td>' . $row_absen['information'] . '</td>
          </tr>';
          }

          echo '<tbody>
      </table>';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            $month = $_GET['from'];
            $year  = $_GET['to'];
            $filter = "employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
          } else {
            $filter = "employees_id='$id' AND MONTH(presence_date) ='$month' AND employees_id='$id'";
          }

          $query_hadir = "SELECT presence_id FROM presence WHERE $filter AND present_id='1' ORDER BY presence_id DESC";
          $hadir = $connection->query($query_hadir);

          $query_sakit = "SELECT presence_id FROM presence WHERE $filter AND present_id='2' ORDER BY presence_id";
          $sakit = $connection->query($query_sakit);

          $query_izin = "SELECT presence_id FROM presence WHERE $filter AND present_id='3' ORDER BY presence_id";
          $izin = $connection->query($query_izin);

          $query_telat = "SELECT presence_id FROM presence WHERE $filter AND time_in>'$shift_time_in'";
          $telat = $connection->query($query_telat);

          echo '<p>Hadir : <span class="label label-success">' . $hadir->num_rows . '</span></p>
          <p>Telat : <span class="label label-danger">' . $telat->num_rows . '</span></p>
          <p>Sakit : <span class="label label-warning">' . $sakit->num_rows . '</span></p>
          <p>Izin : <span class="label label-info">' . $izin->num_rows . '</span></p>

      </div>
    </div>
  </section>
</body>
</html>';
          $html = ob_get_contents();
          ob_end_clean();
          $mpdf->WriteHTML(utf8_encode($html));
          $mpdf->Output("Absensi-$date.pdf", 'I');
        } else {
          echo '<center><h3>Data Tidak Ditemukan</h3></center>';
        }
      } else {
        echo 'Data tidak boleh ada yang kosong!';
      }

      //Explore to Excel -------------------------------------------------------
      break;
    case 'excel':

      if (empty($_GET['id'])) {
        $error[] = 'ID tidak boleh kosong';
      } else {
        $id = mysqli_real_escape_string($connection, $_GET['id']);
      }

      if (empty($error)) {
        $query = "SELECT employees.id,employees.employees_name,employees.shift_id,employees.position_id,position.position_name FROM employees,position WHERE employees.position_id=position.position_id AND employees.id='$id'";
        $result = $connection->query($query);

        if ($result->num_rows > 0) {
          $row            = $result->fetch_assoc();
          $employees_name = $row['employees_name'];

          if (isset($_GET['from']) or isset($_GET['to'])) {
            $bulan   = date($_GET['from']);
          } else {
            $bulan  = date("m");
          }

          $hari       = date("d");
          $tahun      = date("Y");
          $jumlahhari = date("t", mktime(0, 0, 0, $bulan, $hari, $tahun));
          $s          = date("w", mktime(0, 0, 0, $bulan, 1, $tahun));
          $mpdf = new \Mpdf\Mpdf();
          ob_start();

          if (empty($_GET['print'])) {
            header("Content-type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename=Data-Absensi-$employees_name-$date.xls");
          } else {
            echo '<script>
      window.print();
    </script>';
          }


          echo '<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi ' . $employees_name . '</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{line-height:25px;font-size:20px;margin:0px 0 10px;text-transform:uppercase}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{font-size:30px}table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}
    .label {display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap; vertical-align: baseline;border-radius: .25em;}
    .label-success{background-color: #00a65a !important;}.label-warning {background-color: #f0ad4e;}.label-info {background-color: #5bc0de;}.label-danger{background-color: #dd4b39 !important;}
    p{line-height:20px;padding:0px;margin: 5px;}.pull-right{float:right}
    </style>
  <script>
     window.print();
  </script>
</head>
<body>';
          echo '
    <section class="container_box">
      <div class="row">';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            echo '<h3 class="text-center">LAPORAN DETAIL HARIAN<br>PERIODE WAKTU ' . tanggal_indo($_GET['from']) . ' - ' . $_GET['to'] . '</h3>';
          } else {
            echo '<h3 class="text-center">LAPORAN DETAIL BULAN<br>' . tanggal_indo($month) . ' - ' . $year . '</h3>';
          }
          echo '
        <p>Nama   : ' . $row['employees_name'] . '</p>
        <p>Jabatan : ' . $row['position_name'] . '</p><br>
        <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th class="text-center">Jam Masuk</th>
              <th class="text-center">Scan Masuk</th>
              <th>Terlambat</th>
              <th class="text-center">Jam Pulang</th>
              <th class="text-center">Scan Pulang</th>
              <th class="text-center">Pulang Cepat</th>
              <th>Durasi</th>
              <th>Lembur</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';
          for ($d = 1; $d <= $jumlahhari; $d++) {
            $warna      = '';
            $background = '';
            $status     = 'Tidak Hadir';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              $warna = 'white';
              $background = '#FF0000';
              $status = 'Libur Akhir Pekan';
            }
            $date_month_year = '' . $year . '-' . $bulan . '-' . $d . '';

            if (isset($_GET['from']) or isset($_GET['to'])) {
              $month = $_GET['from'];
              $year  = $_GET['to'];
              $filter = "employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
            } else {
              $filter = "employees_id='$id' AND  presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
            }


            $query_shift = "SELECT time_in,time_out FROM shift WHERE shift_id='$row[shift_id]'";
            $result_shift = $connection->query($query_shift);
            $row_shift = $result_shift->fetch_assoc();
            $shift_time_in = $row_shift['time_in'];
            $shift_time_out = $row_shift['time_out'];
            $newtimestamp = strtotime('' . $shift_time_in . ' + 05 minute');
            $newtimestamp = date('H:i:s', $newtimestamp);

            $query_absen = "SELECT presence_id,presence_date,time_in,time_out,picture_in,picture_out,present_id,latitude_longtitude_in,information,TIMEDIFF(TIME(time_in),'$shift_time_in') AS selisih,if (time_in>'$shift_time_in','Telat',if(time_in='00:00:00','Tidak Masuk','Tepat Waktu')) AS status,TIMEDIFF(TIME(time_out),'$shift_time_out') AS selisih_out FROM presence WHERE $filter ORDER BY presence_id DESC";
            $result_absen = $connection->query($query_absen);
            $row_absen = $result_absen->fetch_assoc();

            $querya = "SELECT present_id,present_name FROM present_status WHERE present_id='$row_absen[present_id]'";
            $resulta = $connection->query($querya);
            $rowa =  $resulta->fetch_assoc();

            if ($row_absen['time_in'] == NULL) {
              if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
                $status = 'Libur Akhir Pekan';
              } else {
                $status = '<span class="label label-danger">Tidak Hadir</span>';
              }
              $time_in = $row_absen['time_in'];
            } else {
              $status = $rowa['present_name'];
              $time_in = $row_absen['time_in'];
            }

            // Status Absensi Jam Masuk
            if ($row_absen['status'] == 'Telat') {
              $status_time_in = '<label class="label label-danger pull-right">' . $row_absen['status'] . '</label>';
              /*$waktu_kerja  = strtotime(''.$date_month_year.' '.$shift_time_in.'');
          $waktu_absen  = strtotime(''.$date_month_year.' '.$row_absen['time_in'].'');
          $diff    		= $waktu_absen - $waktu_kerja;
          $terlambat_jam	= floor($diff / (60 * 60));
          $terlambat_menit	= $diff - $terlambat_jam * (60 * 60);
          $terlamat 	= ''.$terlambat_jam.' jam '.floor($terlambat_menit/60).' menit';*/
            } elseif ($row_absen['status'] == 'Tepat Waktu') {
              $status_time_in = '<label class="label label-info pull-right">' . $row_absen['status'] . '</label>';
              $terlamat   = '';
            } else {
              $status_time_in = '';
              $terlamat   = '';
            }

            // DURASI KERJA  =========================================
            $durasi_kerja_start = strtotime('' . $date_month_year . ' ' . $row_absen['time_in'] . '');
            $durasi_kerja_end   = strtotime('' . $date_month_year . ' ' . $row_absen['time_out'] . '');
            $diff          = $durasi_kerja_end - $durasi_kerja_start;
            $durasi_jam       = floor($diff / (60 * 60));
            $durasi_menit     = $diff - ($durasi_jam * (60 * 60));
            $durasi_detik     = $diff % 60;
            $durasi_kerja     = '' . $durasi_jam . ' jam, ' . floor($durasi_menit / 60) . ' menit';

            // JAM LEMBUR =========================================
            if ($row_absen['time_out'] > $shift_time_out) {
              $lembur_kerja_start = strtotime('' . $date_month_year . ' ' . $shift_time_out . '');
              $lembur_kerja_end   = strtotime('' . $date_month_year . ' ' . $row_absen['time_out'] . '');
              $diff          = $lembur_kerja_end - $lembur_kerja_start;
              $lembur_jam       = floor($diff / (60 * 60));
              $lembur_menit     = $diff - ($lembur_jam * (60 * 60));
              $lembur       = '' . $lembur_jam . ' jam, ' . floor($lembur_menit / 60) . ' menit';
            } else {
              $lembur = '';
            }
            echo '
         <tr style="background:' . $background . ';color:' . $warna . '">
            <td class="text-center">' . $d . '</td>
            <td>' . format_hari_tanggal($date_month_year) . '</td>';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_in'] == '') {
                echo '
                <td class="text-center" colspan="3">Libur Akhir Pekan</td>';
              } else {
                echo '
                <td class="text-center">' . $row_absen['time_in'] . '</td>
                <td class="text-center">' . $row_absen['time_in'] . '</td>
              	<td class="text-center">Terlambat</td>';
              }
            } else {
              echo '
              <td class="text-center">' . $shift_time_in . '</td>
              <td class="text-center">' . $row_absen['time_in'] . '</td>
              <td class="text-center">' . $row_absen['selisih'] . '</td>';
            }

            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_out'] == '') {
                echo '
                <td class="text-center" colspan="3">Libur Akhir Pekan</td>';
              } else {
                echo '
                <td class="text-center">' . $row_shift['time_out'] . '</td>
              	<td class="text-center">' . $row_absen['time_out'] . '</td>
              	<td class="text-center">' . $row_absen['selisih_out'] . '</td>';
              }
            } else {
              echo '
              <td class="text-center">' . $row_shift['time_out'] . '</td>
              <td class="text-center">' . $row_absen['time_out'] . '</td>
              <td class="text-center">' . $row_absen['selisih_out'] . '</td>';
            }
            echo '
              <td>' . $durasi_kerja . '</td>
              <td>' . $lembur . '</td>
              <td>' . $status . ' ' . $status_time_in . '</td>
              <td>' . $row_absen['information'] . '</td>
          </tr>';
          }

          echo '<tbody>
      </table>';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            $month = $_GET['from'];
            $year  = $_GET['to'];
            $filter = "employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
          } else {
            $filter = "employees_id='$id' AND MONTH(presence_date) ='$month' AND employees_id='$id'";
          }

          $query_hadir = "SELECT presence_id FROM presence WHERE $filter AND present_id='1' ORDER BY presence_id DESC";
          $hadir = $connection->query($query_hadir);

          $query_sakit = "SELECT presence_id FROM presence WHERE $filter AND present_id='2' ORDER BY presence_id";
          $sakit = $connection->query($query_sakit);

          $query_izin = "SELECT presence_id FROM presence WHERE $filter AND present_id='3' ORDER BY presence_id";
          $izin = $connection->query($query_izin);

          $query_telat = "SELECT presence_id FROM presence WHERE $filter AND time_in>'$shift_time_in'";
          $telat = $connection->query($query_telat);

          echo '<p>Hadir : <span class="label label-success">' . $hadir->num_rows . '</span></p>
          <p>Telat : <span class="label label-danger">' . $telat->num_rows . '</span></p>
          <p>Sakit : <span class="label label-warning">' . $sakit->num_rows . '</span></p>
          <p>Izin : <span class="label label-info">' . $izin->num_rows . '</span></p>

        </div>
      </div>
    </section>
</body>
</html>';
        } else {
          echo '<center><h3>Data Tidak Ditemukan</h3></center>';
        }
      } else {
        echo 'Data tidak boleh ada yang kosong!';
      }

      break;
    /* -------  CETAK ALL Karyawan PDF-----------------------------------------------*/
    case 'allpdf':
      $query = "SELECT employees.id,employees.employees_name,employees.position_id,position.position_name,shift.time_in,shift.time_out FROM employees,position,shift WHERE employees.position_id=position.position_id AND employees.shift_id=shift.shift_id ORDER BY employees.id DESC";
      $result = $connection->query($query);
      if ($result->num_rows > 0) {

        $mpdf = new \Mpdf\Mpdf();
        ob_start();
        /*$mpdf->SetHTMLFooter('
      <table width="100%" style="border-top:solid 1px #333;font-size:11px;">
          <tr>
              <td width="60%" style="text-align:left;">Simpanlah lembar Absensi ini.</td>
              <td width="35%" style="text-align: right;">Dicetak tanggal '.tgl_indo($date).'</td>
          </tr>
      </table>');*/
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Cetak Data Absensi</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative}.container_box .row h3{line-height:25px;font-size:20px;margin:0px 0 10px;text-transform:uppercase}.container_box .text-center{text-align:center}.container_box .content_box{position:relative}.container_box .content_box .des_info{margin:20px 0;text-align:right}.container_box h3{font-size:30px}table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}
    .label {display: inline;padding: .2em .6em .3em;font-size: 75%;font-weight: 700;line-height: 1;color: #fff;text-align: center;white-space: nowrap; vertical-align: baseline;border-radius: .25em;}
    .label-success{background-color: #00a65a !important;}.label-warning {background-color: #f0ad4e;}.label-info {background-color: #5bc0de;}.label-danger{background-color: #dd4b39 !important;}
    p{line-height:20px;padding:0px;margin: 5px;}.pull-right{float:right}
    </style>
</head>
<body>';
        while ($row = $result->fetch_assoc()) {
          $employees_name = $row['employees_name'];
          $id             = $row['id'];

          if (isset($_GET['from']) or isset($_GET['to'])) {
            $bulan   = date($_GET['from']);
          } else {
            $bulan  = date("m");
          }
          $hari       = date("d");
          $tahun      = date("Y");
          $jumlahhari = date("t", mktime(0, 0, 0, $bulan, $hari, $tahun));
          $s          = date("w", mktime(0, 0, 0, $bulan, 1, $tahun));


          $shift_time_in  = $row['time_in'];
          $newtimestamp   = strtotime('' . $shift_time_in . ' + 05 minute');
          $newtimestamp   = date('H:i:s', $newtimestamp);
          echo '
    <section class="container_box">
      <div class="row">';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            echo '<h3>DATA ABSENSI BULAN ' . tanggal_indo($_GET['from']) . ' - ' . $_GET['to'] . '</h3>';
          } else {
            echo '<h3>DATA ABSENSI BULAN ' . tanggal_indo($month) . ' - ' . $year . '</h3>';
          }
          echo '
        <p>Nama   : ' . $row['employees_name'] . '</p>
        <p>Jabatan : ' . $row['position_name'] . '</p><br>
      <div class="content_box">
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th>Waktu Masuk</th>
              <th>Waktu Pulang</th>
              <th>Status</th>
              <th>Keterangan</th>
            </tr>
          </thead>
        <tbody>';
          for ($d = 1; $d <= $jumlahhari; $d++) {
            $warna      = '';
            $background = '';
            $status     = 'Tidak Hadir';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              $warna = 'white';
              $background = '#FF0000';
              $status = 'Libur Akhir Pekan';
            }
            $date_month_year = '' . $year . '-' . $bulan . '-' . $d . '';

            if (isset($_GET['from']) or isset($_GET['to'])) {
              $month = $_GET['from'];
              $year  = $_GET['to'];
              $filter = "employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date)='$month' AND year(presence_date)='$year'";
            } else {
              $filter = "employees_id='$id' AND presence_date='$date_month_year' AND MONTH(presence_date) ='$month'";
            }


            $query_absen = "SELECT presence_id,presence_date,time_in,time_out,picture_in,picture_out,present_id,latitude_longtitude_in,information,TIMEDIFF(TIME(time_in),'$shift_time_in') AS selisih,if (time_in>'$shift_time_in','Telat',if(time_in='00:00:00','Tidak Masuk','Tepat Waktu')) AS status FROM presence WHERE $filter";
            $result_absen = $connection->query($query_absen);
            $row_absen = $result_absen->fetch_assoc();

            $querya = "SELECT present_id,present_name FROM present_status WHERE present_id='$row_absen[present_id]'";
            $resulta = $connection->query($querya);
            $rowa =  $resulta->fetch_assoc();

            if ($row_absen['time_in'] == NULL) {
              if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
                $status = 'Libur Akhir Pekan';
              } else {
                $status = '<span class="label label-danger">Tidak Hadir</span>';
              }
              $time_in = $row_absen['time_in'];
            } else {
              $status = $rowa['present_name'];
              $time_in = $row_absen['time_in'];
            }


            if ($row_absen['status'] == 'Telat') {
              $status_time_in = '<label class="label label-danger pull-right">' . $row_absen['status'] . '</label>';
            } elseif ($row_absen['status'] == 'Tepat Waktu') {
              $status_time_in = '<label class="label label-info pull-right">' . $row_absen['status'] . '</label>';
            } else {
              $status_time_in = '';
            }


            echo '
         <tr style="background:' . $background . ';color:' . $warna . '">
            <td class="text-center">' . $d . '</td>
            <td>' . format_hari_tanggal($date_month_year) . '</td>';
            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_in'] == '') {
                echo '
                <td class="text-center">Libur Akhir Pekan</td>';
              } else {
                echo '
                <td>' . $row_absen['time_in'] . '</td>';
              }
            } else {
              echo '
              <td>' . $row_absen['time_in'] . '</td>';
            }

            if (date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday") {
              if ($row_absen['time_out'] == '') {
                echo '
                <td class="text-center">Libur Akhir Pekan</td>';
              } else {
                echo '
                  <td>' . $row_absen['time_out'] . '</td>';
              }
            } else {
              echo '
              <td>' . $row_absen['time_out'] . '</td>';
            }
            echo '
              <td>' . $status . ' ' . $status_time_in . '</td>
              <td>' . $row_absen['information'] . '</td>
          </tr>';
          }

          echo '<tbody>
      </table>';
          if (isset($_GET['from']) or isset($_GET['to'])) {
            $month = $_GET['from'];
            $year  = $_GET['to'];
            $filter = "employees_id='$id' AND MONTH(presence_date)='$month' AND year(presence_date)='$year' AND employees_id='$id'";
          } else {
            $filter = "employees_id='$id' AND MONTH(presence_date) ='$month' AND employees_id='$id'";
          }

          $query_hadir = "SELECT presence_id FROM presence WHERE $filter AND present_id='1' ORDER BY presence_id DESC";
          $hadir = $connection->query($query_hadir);

          $query_sakit = "SELECT presence_id FROM presence WHERE $filter AND present_id='2' ORDER BY presence_id";
          $sakit = $connection->query($query_sakit);

          $query_izin = "SELECT presence_id FROM presence WHERE $filter AND present_id='3' ORDER BY presence_id";
          $izin = $connection->query($query_izin);

          $query_telat = "SELECT presence_id FROM presence WHERE $filter AND time_in>'$shift_time_in'";
          $telat = $connection->query($query_telat);

          echo '<p>Hadir : <span class="label label-success">' . $hadir->num_rows . '</span></p>
          <p>Telat : <span class="label label-danger">' . $telat->num_rows . '</span></p>
          <p>Sakit : <span class="label label-warning">' . $sakit->num_rows . '</span></p>
          <p>Izin : <span class="label label-info">' . $izin->num_rows . '</span></p>

      </div>
    </div>
  </section>';
        }
        echo '
</body>
</html>';
        $html = ob_get_contents();
        ob_end_clean();
        $mpdf->WriteHTML(utf8_encode($html));
        $mpdf->Output("Absensi-All-$date.pdf", 'I');
      } else {
        echo '<center><h3>Data Tidak Ditemukan</h3></center>';
      }
      break;


    /* -------  CETAK ALL EXCEL-----------------------------------------------*/
    case 'allexcel':
      // Ambil parameter bulan dan tahun
      if (isset($_GET['from']) && isset($_GET['to'])) {
        $bulan = $_GET['from'];
        $tahun = $_GET['to'];
      } else {
        $bulan = date("m");
        $tahun = date("Y");
      }

      $jumlahhari = date("t", mktime(0, 0, 0, $bulan, 1, $tahun));

      // Query yang lebih sederhana dulu
      $query = "SELECT 
          employees.id,
          employees.employees_code,
          employees.employees_name,
          employees.position_id,
          employees.shift_id,
          position.position_name,
          building.name as building_name,
          shift.time_in,
          shift.time_out 
        FROM employees 
        INNER JOIN position ON employees.position_id = position.position_id 
        INNER JOIN shift ON employees.shift_id = shift.shift_id 
        LEFT JOIN building ON employees.building_id = building.building_id 
        ORDER BY employees.id ASC";

      $result = $connection->query($query);

      // Cek apakah query berhasil
      if (!$result) {
        die("Error Query: " . $connection->error);
      }

      if ($result->num_rows > 0) {

        // Header untuk Excel
        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=Rekap-Absensi-" . $bulan . "-" . $tahun . ".xls");

?>
        <!DOCTYPE html>
        <html>

        <head>
          <meta charset="utf-8">
          <style>
            body {
              font-family: Arial;
              font-size: 14px;
            }

            table {
              border-collapse: collapse;
              width: 100%;
            }

            th,
            td {
              border: 1px solid #000;
              padding: 5px;
              text-align: center;
              font-size: 14px;
            }

            th {
              background: #4CAF50;
              color: #fff;
              font-weight: bold;
            }

            .text-left {
              text-align: left;
            }
          </style>
        </head>

        <body>

          <h3>REKAP ABSENSI KARYAWAN - <?= strtoupper(date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun))); ?></h3>

          <table>
            <thead>
              <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">NIK</th>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Jabatan</th>
                <th rowspan="2">Cabang</th>

                <?php
                // Header Tanggal (baris pertama)
                for ($d = 1; $d <= $jumlahhari; $d++) {
                  $is_sunday = date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday";
                  echo '<th ' . ($is_sunday ? 'style="background:red;color:white"' : '') . '>' . $d . '</th>';
                }
                ?>

                <th colspan="20">Monitoring</th>
                <th rowspan="2">Potongan Uang Hadir</th>
                <th rowspan="2">Potongan Keterlambatan</th>
                <th rowspan="2">Perhitungan Mangkir</th>
              </tr>

              <tr>
                <?php
                // Nama Hari (baris kedua)
                for ($d = 1; $d <= $jumlahhari; $d++) {
                  $day_name = date("D", mktime(0, 0, 0, $bulan, $d, $tahun));
                  $is_sunday = date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday";
                  echo '<th ' . ($is_sunday ? 'style="background:red;color:white"' : '') . '>' . $day_name . '</th>';
                }
                ?>

                <th>Alpa</th>
                <th>Konversi Rp.</th>
                <th>Izin</th>
                <th>Izin Sesuai UU</th>
                <th>Konversi Rp.</th>
                <th>Sakit Dengan Surat</th>
                <th>Konversi Rp.</th>
                <th>Sakit Tanpa Surat</th>
                <th>Konversi Rp.</th>
                <th>Cuti Tahunan</th>
                <th>Gaji</th>
                <th>Hadir</th>
                <th>Telat 6-10</th>
                <th>Telat 11-15</th>
                <th>Telat 16-20</th>
                <th>Telat 21-25</th>
                <th>Telat &gt;26</th>
                <th>Tidak Checkclock Datang</th>
                <th>Tidak Checkclock Pulang</th>
                <th>Pulang Lebih Dulu</th>
              </tr>
            </thead>

            <tbody>
              <?php
              $no = 1;
              $today = date('Y-m-d');

              while ($row = $result->fetch_assoc()) {
                $employees_id = $row['id'];
                $shift_time_in = $row['time_in'];
                $shift_time_out = $row['time_out'];

                $total_hadir = $total_alpa = $total_izin = $total_izin_uu = 0;
                $total_sakit_surat = $total_sakit_tanpa_surat = $total_cuti = 0;
                $total_telat_6_10 = $total_telat_11_15 = 0;
                $total_telat_16_20 = $total_telat_21_25 = 0;
                $total_telat_26_plus = $total_pulang_cepat = 0;
                $total_tidak_clock_in = $total_tidak_clock_out = 0;

                // Query untuk mengambil total cuti tahunan
                $query_cuti = "SELECT SUM(cuty_total) as total_cuti_tahunan 
                 FROM cuty 
                 WHERE employees_id = '$employees_id' 
                 AND YEAR(cuty_start) = '$tahun' 
                 AND cuty_status = '1'";
                $result_cuti = $connection->query($query_cuti);

                if ($result_cuti && $result_cuti->num_rows > 0) {
                  $row_cuti = $result_cuti->fetch_assoc();
                  $total_cuti = $row_cuti['total_cuti_tahunan'] ? $row_cuti['total_cuti_tahunan'] : 0;
                }

                // Query untuk mengambil data gaji karyawan di bulan dan tahun tertentu
                $query_gaji = "SELECT 
                  gaji,
                  potongan_uang_hadir,
                  potongan_keterlambatan,
                  perhitungan_mangkir,
                  konversi_alpa,
                  konversi_izin,
                  konversi_sakit_surat,
                  konversi_sakit_tanpa_surat
                 FROM gaji 
                 WHERE employees_id = '$employees_id' 
                 AND bulan = '$bulan' 
                 AND tahun = '$tahun'
                 LIMIT 1";
                $result_gaji = $connection->query($query_gaji);

                // Inisialisasi variabel gaji dengan default 0
                $gaji_pokok = 0;
                $potongan_uang_hadir = 0;
                $potongan_keterlambatan = 0;
                $perhitungan_mangkir = 0;
                $konversi_alpa = 0;
                $konversi_izin = 0;
                $konversi_sakit_surat = 0;
                $konversi_sakit_tanpa_surat = 0;

                // Jika ada data gaji, ambil nilainya
                if ($result_gaji && $result_gaji->num_rows > 0) {
                  $row_gaji = $result_gaji->fetch_assoc();
                  $gaji_pokok = $row_gaji['gaji'];
                  $potongan_uang_hadir = $row_gaji['potongan_uang_hadir'];
                  $potongan_keterlambatan = $row_gaji['potongan_keterlambatan'];
                  $perhitungan_mangkir = $row_gaji['perhitungan_mangkir'];
                  $konversi_alpa = $row_gaji['konversi_alpa'];
                  $konversi_izin = $row_gaji['konversi_izin'];
                  $konversi_sakit_surat = $row_gaji['konversi_sakit_surat'];
                  $konversi_sakit_tanpa_surat = $row_gaji['konversi_sakit_tanpa_surat'];
                }

                echo "<tr>
    <td>$no</td>
    <td>{$row['employees_code']}</td>
    <td class='text-left'>{$row['employees_name']}</td>
    <td>{$row['position_name']}</td>
    <td>{$row['building_name']}</td>";

                // Loop untuk setiap tanggal
                for ($d = 1; $d <= $jumlahhari; $d++) {
                  $date = sprintf("%04d-%02d-%02d", $tahun, $bulan, $d);
                  $is_sunday = date("l", mktime(0, 0, 0, $bulan, $d, $tahun)) == "Sunday";

                  // Jika tanggal belum sampai
                  if ($date > $today) {
                    echo "<td></td>";
                    continue;
                  }

                  // Jika hari Minggu/Libur
                  if ($is_sunday) {
                    echo "<td style='background:#ffcccc'>L</td>";
                    continue;
                  }

                  // Query absensi
                  $q = $connection->query("
      SELECT present_id, time_in, time_out
      FROM presence
      WHERE employees_id='$employees_id'
      AND presence_date='$date'
    ");

                  if ($q && $q->num_rows > 0) {
                    $a = $q->fetch_assoc();

                    // Hitung keterlambatan
                    $late_minutes = 0;
                    if ($a['time_in'] && $shift_time_in) {
                      $time_in_stamp = strtotime($date . ' ' . $a['time_in']);
                      $shift_in_stamp = strtotime($date . ' ' . $shift_time_in);
                      $late_minutes = round(($time_in_stamp - $shift_in_stamp) / 60);
                    }

                    // Hitung pulang cepat
                    $early_minutes = 0;
                    if ($a['time_out'] && $a['time_out'] != '00:00:00' && $shift_time_out) {
                      $time_out_stamp = strtotime($date . ' ' . $a['time_out']);
                      $shift_out_stamp = strtotime($date . ' ' . $shift_time_out);
                      $early_minutes = round(($shift_out_stamp - $time_out_stamp) / 60);
                    }

                    // Cek status kehadiran
                    if ($a['present_id'] == 1) { // Hadir

                      // PENTING: Semua yang present_id = 1 dihitung HADIR
                      $total_hadir++;

                      $cell_color = '#90EE90';
                      $cell_text = 'H';

                      // Cek keterlambatan terlebih dahulu (untuk counting)
                      if ($late_minutes >= 6 && $late_minutes <= 10) {
                        $total_telat_6_10++;
                        $cell_color = '#FFA500';
                        $cell_text = 'T';
                      } elseif ($late_minutes >= 11 && $late_minutes <= 15) {
                        $total_telat_11_15++;
                        $cell_color = '#FFA500';
                        $cell_text = 'T';
                      } elseif ($late_minutes >= 16 && $late_minutes <= 20) {
                        $total_telat_16_20++;
                        $cell_color = '#FFA500';
                        $cell_text = 'T';
                      } elseif ($late_minutes >= 21 && $late_minutes <= 25) {
                        $total_telat_21_25++;
                        $cell_color = '#FFA500';
                        $cell_text = 'T';
                      } elseif ($late_minutes > 25) {
                        $total_telat_26_plus++;
                        $cell_color = '#FF0000';
                        $cell_text = 'T';
                      }

                      // Cek tidak checkclock pulang (ini akan override tampilan tapi tidak menghapus counting terlambat)
                      if (!$a['time_out'] || $a['time_out'] == '00:00:00') {
                        $total_tidak_clock_out++;
                        $cell_color = '#FFA500';
                        $cell_text = 'NCP'; // Tidak Checkclock Pulang
                      } else {
                        // Cek pulang cepat (hanya jika ada data pulang)
                        if ($early_minutes > 0) {
                          $total_pulang_cepat++;
                        }
                      }

                      echo "<td style='background:$cell_color'>$cell_text</td>";
                    } elseif ($a['present_id'] == 2) { // Sakit dengan surat
                      echo "<td style='background:#FFD700'>S</td>";
                      $total_sakit_surat++;
                    } elseif ($a['present_id'] == 3) { // Izin
                      echo "<td style='background:#87CEEB'>I</td>";
                      $total_izin++;
                    } elseif ($a['present_id'] == 4) { // Sakit tanpa surat
                      echo "<td style='background:#FFD700'>ST</td>";
                      $total_sakit_tanpa_surat++;
                    } elseif ($a['present_id'] == 5) { // Izin Sesuai UU
                      echo "<td style='background:#87CEEB'>IUU</td>";
                      $total_izin_uu++;
                    } else {
                      echo "<td style='background:#FFB6C1'>A</td>";
                      $total_alpa++;
                      $total_tidak_clock_in++; // Alpa = tidak checkclock datang
                    }
                  } else {
                    // Tidak ada data absensi = Alpa
                    echo "<td style='background:#FFB6C1'>A</td>";
                    $total_alpa++;
                    $total_tidak_clock_in++; // Alpa = tidak checkclock datang
                  }
                }

                // Kolom Monitoring dengan data dari tabel gaji
                echo "
    <td>$total_alpa</td>
    <td>$konversi_alpa</td>
    <td>$total_izin</td>
    <td>$total_izin_uu</td>
    <td>$konversi_izin</td>
    <td>$total_sakit_surat</td>
    <td>$konversi_sakit_surat</td>
    <td>$total_sakit_tanpa_surat</td>
    <td>$konversi_sakit_tanpa_surat</td>
    <td>$total_cuti</td>
    <td>$gaji_pokok</td>
    <td>$total_hadir</td>
    <td>$total_telat_6_10</td>
    <td>$total_telat_11_15</td>
    <td>$total_telat_16_20</td>
    <td>$total_telat_21_25</td>
    <td>$total_telat_26_plus</td>
    <td>$total_tidak_clock_in</td>
    <td>$total_tidak_clock_out</td>
    <td>$total_pulang_cepat</td>
    <td>$potongan_uang_hadir</td>
    <td>$potongan_keterlambatan</td>
    <td>$perhitungan_mangkir</td>
  </tr>";

                $no++;
              }
              ?>
            </tbody>
          </table>

          <br>
          <b>Keterangan:</b><br>
          H = Hadir | A = Alpa | I = Izin | IUU = Izin Sesuai UU | S = Sakit Dengan Surat | ST = Sakit Tanpa Surat | L = Libur | T = Terlambat | NCP = Tidak Checkclock Pulang

        </body>

        </html>
<?php

      } else {
        echo '<center><h3>Data Tidak Ditemukan</h3></center>';
      }

      break;
  }
} ?>