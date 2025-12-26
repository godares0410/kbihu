<section class="content-header">
  <h1>Dashboard <small>Control panel</small></h1>
</section>

<section class="content">
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-green">
        <div class="inner">
          <h3><?= $totalPeserta ?></h3>
          <p>Jumlah Peserta</p>
        </div>
        <div class="icon">
          <i class="fa fa-users"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?= $totalScan ?></h3>
          <p>Peserta Sudah Scan</p>
        </div>
        <div class="icon">
          <i class="fa fa-check-circle"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-blue">
        <div class="inner">
          <h3><?= $totalRombongan ?></h3>
          <p>Jumlah Rombongan</p>
        </div>
        <div class="icon">
          <i class="fa fa-sitemap"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-red">
        <div class="inner">
          <h3><?= $totalRegu ?></h3>
          <p>Jumlah Regu</p>
        </div>
        <div class="icon">
          <i class="fa fa-random"></i>
        </div>
      </div>
    </div>
  </div>
</section>
