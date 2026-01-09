<?php
if (empty($connection)) {
  header('location:./404');
  exit;
}
?>

<aside class="main-sidebar" style="padding-left: 10px; padding-right: 10px;">
  <!-- sidebar: style can be found in sidebar.less -->
  <div class="slimScrollDiv">
    <section class="sidebar">
      <!-- Sidebar user panel -->

      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>

        <!-- Dashboard -->
        <li <?php echo ($mod == 'home') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./">
            <i class="fa fa-home"></i>
            <span>Dashboard</span>
          </a>
        </li>

        <!-- Master Data -->
        <li <?php echo (in_array($mod, ['karyawan', 'jabatan', 'shift', 'lokasi', 'kota'])) ? 'class="active treeview"' : 'class="treeview"'; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-green text-dark" href="#">
            <i class="fa fa-database"></i>
            <span>Master Data</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li <?php echo ($mod == 'karyawan') ? 'class="active"' : ''; ?>>
              <a class="navbar navbar-expand-lg navbar-light bg-yellow text-dark" href="./karyawan">
                <i class="fa fa-circle-o"></i> Data Karyawan
              </a>
            </li>

            <li <?php echo ($mod == 'jabatan') ? 'class="active"' : ''; ?>>
              <a class="navbar navbar-expand-lg navbar-light bg-yellow text-dark" href="./jabatan">
                <i class="fa fa-circle-o"></i> Data Jabatan
              </a>
            </li>

            <li <?php echo ($mod == 'shift') ? 'class="active"' : ''; ?>>
              <a class="navbar navbar-expand-lg navbar-light bg-yellow text-dark" href="./shift">
                <i class="fa fa-circle-o"></i> Data Jam Kerja
              </a>
            </li>

            <li <?php echo ($mod == 'kota') ? 'class="active"' : ''; ?>>
              <a class="navbar navbar-expand-lg navbar-light bg-yellow text-dark" href="./kota">
                <i class="fa fa-circle-o"></i> Data Kota
              </a>
            </li>

            <li <?php echo ($mod == 'lokasi') ? 'class="active"' : ''; ?>>
              <a class="navbar navbar-expand-lg navbar-light bg-yellow text-dark" href="./lokasi">
                <i class="fa fa-circle-o"></i> Data Cabang
              </a>
            </li>
          </ul>
        </li>

        <!-- Data Permohonan Cuti -->
        <li <?php echo ($mod == 'cuty') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./cuty">
            <i class="fa fa-calendar" aria-hidden="true"></i>
            <span>Data Permohonan Cuti</span>
          </a>
        </li>

        <!-- Data Absensi -->
        <li <?php echo ($mod == 'absensi') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./absensi">
            <i class="fa fa-list-alt" aria-hidden="true"></i>
            <span>Data Absensi</span>
          </a>
        </li>

        <!-- Data Penggajian -->
        <li <?php echo ($mod == 'gaji') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./gaji">
            <i class="fa fa-money" aria-hidden="true"></i>
            <span>Data Penggajian</span>
          </a>
        </li>

        <!-- Pengaturan Web -->
        <li <?php echo ($mod == 'setting') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./setting">
            <i class="fa fa-cogs" aria-hidden="true"></i>
            <span>Pengaturan Web</span>
          </a>
        </li>

        <!-- Admin -->
        <li <?php echo ($mod == 'user') ? 'class="active"' : ''; ?>>
          <a class="navbar navbar-expand-lg navbar-light bg-blue text-dark" href="./user">
            <i class="fa fa-user"></i>
            <span>Admin</span>
          </a>
        </li>

        <!-- Logout -->
        <li>
          <a class="navbar navbar-expand-lg navbar-light bg-red text-dark" href="javascript:void(0);" onclick="location.href='./logout';">
            <i class="fa fa-sign-out text-white"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </section>
  </div>
  <!-- /.sidebar -->
</aside>