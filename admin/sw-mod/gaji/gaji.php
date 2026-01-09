<?php
if (empty($connection)) {
  header('location:../../');
} else {
  include_once 'sw-mod/sw-panel.php';
  echo '
  <div class="content-wrapper">';
  switch (@$_GET['op']) {
    default:
      echo '
<section class="content-header">
  <h1>Data<small> Penggajian</small></h1>
    <ol class="breadcrumb">
      <li><a href="./"><i class="fa fa-dashboard"></i> Beranda</a></li>
      <li class="active">Data Penggajian</li>
    </ol>
</section>';
      echo '
<section class="content">
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title"><b>Data Penggajian</b></h3>
          <div class="box-tools pull-right">';
      if ($level_user == 1) {
        echo '
            <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modalAdd"><i class="fa fa-plus"></i> Tambah Baru</button>';
      } else {
        echo '
            <button type="button" class="btn btn-success btn-flat access-failed"><i class="fa fa-plus"></i> Tambah Baru</button>';
      }
      echo '
          </div>
        </div>
        <div class="box-body" style="overflow-x: scroll;">
          <table id="swdatatable" class="table table-bordered table-striped nowrap" style="width: 100%;">
            <thead>
            <tr>
              <th class="text-center">No</th>
              <th>Nama Karyawan</th>
              <th class="text-center">Bulan</th>
              <th class="text-center">Tahun</th>
              <th>Gaji</th>
              <th>Potongan Uang Hadir</th>
              <th>Potongan Keterlambatan</th>
              <th class="text-center">Perhitungan Mangkir</th>
              <th class="text-center">Konversi Alpa</th>
              <th class="text-center">Konversi Izin</th>
              <th class="text-center">Konversi Sakit Dengan Surat</th>
              <th class="text-center">Konversi Sakit Tanpa Surat</th>
              <th class="text-center">Tanggal</th>
              <th class="text-center">Aksi</th>
            </tr>
            </thead>
            <tbody>';
      $query = "SELECT gaji.id, gaji.employees_id, gaji.gaji, gaji.potongan_uang_hadir, gaji.potongan_keterlambatan, 
                       gaji.perhitungan_mangkir, gaji.konversi_alpa, gaji.konversi_izin, gaji.konversi_sakit_surat, 
                       gaji.konversi_sakit_tanpa_surat, gaji.bulan, gaji.tahun, gaji.tanggal, employees.employees_name 
                FROM gaji 
                LEFT JOIN employees ON gaji.employees_id = employees.id 
                ORDER BY gaji.tahun DESC, gaji.bulan DESC";
      $result = $connection->query($query);
      if ($result->num_rows > 0) {
        $no = 0;
        while ($row = $result->fetch_assoc()) {
          $no++;
          echo '
                <tr>
                  <td class="text-center">' . $no . '</td>
                  <td>' . $row['employees_name'] . '</td>
                  <td class="text-center">' . $row['bulan'] . '</td>
                  <td class="text-center">' . $row['tahun'] . '</td>
                  <td>Rp ' . number_format($row['gaji'], 0, ',', '.') . '</td>
                  <td>Rp ' . number_format($row['potongan_uang_hadir'], 0, ',', '.') . '</td>
                  <td>Rp ' . number_format($row['potongan_keterlambatan'], 0, ',', '.') . '</td>
                  <td class="text-center">' . $row['perhitungan_mangkir'] . '</td>
                  <td class="text-center">' . $row['konversi_alpa'] . '</td>
                  <td class="text-center">' . $row['konversi_izin'] . '</td>
                  <td class="text-center">' . $row['konversi_sakit_surat'] . '</td>
                  <td class="text-center">' . $row['konversi_sakit_tanpa_surat'] . '</td>
                  <td class="text-center" style="white-space: nowrap;">' . date('d-m-Y', strtotime($row['tanggal'])) . '</td>
                  <td class="text-center" style="white-space: nowrap;">';
          if ($level_user == 1) {
            echo '
                      <a href="#modalEdit" class="btn btn-warning btn-xs enable-tooltip" title="Edit" data-toggle="modal"'; ?> onclick="getElementById('txtid').value='<?PHP echo $row['id']; ?>';getElementById('txtemployeesid').value='<?PHP echo $row['employees_id']; ?>';getElementById('txtbulan').value='<?PHP echo $row['bulan']; ?>';getElementById('txttahun').value='<?PHP echo $row['tahun']; ?>';getElementById('txtgaji').value='<?PHP echo $row['gaji']; ?>';getElementById('txtpotonganuanghadir').value='<?PHP echo $row['potongan_uang_hadir']; ?>';getElementById('txtpotonganketerlambatan').value='<?PHP echo $row['potongan_keterlambatan']; ?>';getElementById('txtperhitunganmangkir').value='<?PHP echo $row['perhitungan_mangkir']; ?>';getElementById('txtkonversialpa').value='<?PHP echo $row['konversi_alpa']; ?>';getElementById('txtkonversiizin').value='<?PHP echo $row['konversi_izin']; ?>';getElementById('txtkonversisakitsurat').value='<?PHP echo $row['konversi_sakit_surat']; ?>';getElementById('txtkonversisakittanpasurat').value='<?PHP echo $row['konversi_sakit_tanpa_surat']; ?>';"><i class="fa fa-pencil-square-o"></i> Ubah</a>
  <?php echo '
                      <buton data-id="' . epm_encode($row['id']) . '" class="btn btn-xs btn-danger delete" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</button>';
          } else {
            echo '
                      <button type="button" class="btn btn-warning btn-xs access-failed enable-tooltip" title="Edit"><i class="fa fa-pencil-square-o"></i> Ubah</button>
                      <buton type="button" class="btn btn-xs btn-danger access-failed" title="Hapus"><i class="fa fa-trash-o"></i> Hapus</button>';
          }
          echo '
                  </td>
                </tr>';
        }
      }
      echo '
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div> 
</section>

<!-- Add -->
<div class="modal fade" id="modalAdd" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Tambah Baru</h4>
      </div>
      <form class="form validate add-gaji">
      <div class="modal-body">
        <div class="form-group">
            <label>Karyawan</label>
            <select class="form-control" name="employees_id" required>
                <option value="">-- Pilih Karyawan --</option>';
      $query_emp = "SELECT id, employees_name FROM employees ORDER BY employees_name ASC";
      $result_emp = $connection->query($query_emp);
      if ($result_emp->num_rows > 0) {
        while ($emp = $result_emp->fetch_assoc()) {
          echo '<option value="' . $emp['id'] . '">' . $emp['employees_name'] . '</option>';
        }
      }
      echo '
            </select>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label>Bulan</label>
                <input type="number" class="form-control" name="bulan" min="1" max="12" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label>Tahun</label>
                <input type="number" class="form-control" name="tahun" min="2020" required>
            </div>
          </div>
        </div>

        <div class="form-group">
            <label>Gaji</label>
            <input type="number" class="form-control" name="gaji" required>
        </div>

        <hr>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label>Potongan Uang Hadir</label>
                <input type="number" class="form-control" name="potongan_uang_hadir" value="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label>Potongan Keterlambatan</label>
                <input type="number" class="form-control" name="potongan_keterlambatan" value="0">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label>Perhitungan Mangkir</label>
                <input type="number" class="form-control" name="perhitungan_mangkir" value="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label>Konversi Alpa</label>
                <input type="number" class="form-control" name="konversi_alpa" value="0">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
                <label>Konversi Izin</label>
                <input type="number" class="form-control" name="konversi_izin" value="0">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
                <label>Konversi Sakit Dengan Surat</label>
                <input type="number" class="form-control" name="konversi_sakit_surat" value="0">
            </div>
          </div>
        </div>

        <div class="form-group">
            <label>Konversi Sakit Tanpa Surat</label>
            <input type="number" class="form-control" name="konversi_sakit_tanpa_surat" value="0">
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Data</h4>
      </div>
      <form class="form update-gaji" method="post">
       <input type="hidden" name="id" id="txtid" required" value="" readonly>
      <div class="modal-body">
          <div class="form-group">
              <label>Karyawan</label>
              <select class="form-control" id="txtemployeesid" name="employees_id" required>
                  <option value="">-- Pilih Karyawan --</option>';
      $query_emp2 = "SELECT id, employees_name FROM employees ORDER BY employees_name ASC";
      $result_emp2 = $connection->query($query_emp2);
      if ($result_emp2->num_rows > 0) {
        while ($emp2 = $result_emp2->fetch_assoc()) {
          echo '<option value="' . $emp2['id'] . '">' . $emp2['employees_name'] . '</option>';
        }
      }
      echo '
              </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>Bulan</label>
                  <input type="number" class="form-control" id="txtbulan" name="bulan" min="1" max="12" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label>Tahun</label>
                  <input type="number" class="form-control" id="txttahun" name="tahun" min="2020" required>
              </div>
            </div>
          </div>

          <div class="form-group">
              <label>Gaji</label>
              <input type="number" class="form-control" id="txtgaji" name="gaji" required>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>Potongan Uang Hadir</label>
                  <input type="number" class="form-control" id="txtpotonganuanghadir" name="potongan_uang_hadir">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label>Potongan Keterlambatan</label>
                  <input type="number" class="form-control" id="txtpotonganketerlambatan" name="potongan_keterlambatan">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>Perhitungan Mangkir</label>
                  <input type="number" class="form-control" id="txtperhitunganmangkir" name="perhitungan_mangkir">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label>Konversi Alpa</label>
                  <input type="number" class="form-control" id="txtkonversialpa" name="konversi_alpa">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                  <label>Konversi Izin</label>
                  <input type="number" class="form-control" id="txtkonversiizin" name="konversi_izin">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                  <label>Konversi Sakit Dengan Surat</label>
                  <input type="number" class="form-control" id="txtkonversisakitsurat" name="konversi_sakit_surat">
              </div>
            </div>
          </div>

          <div class="form-group">
              <label>Konversi Sakit Tanpa Surat</label>
              <input type="number" class="form-control" id="txtkonversisakittanpasurat" name="konversi_sakit_tanpa_surat">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left"><i class="fa fa-check"></i> Simpan</button>
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="fa fa-remove"></i> Batal</button>
      </div>
    </form>
    </div>
  </div>
</div>';
      break;
  } ?>

  </div>
<?php } ?>