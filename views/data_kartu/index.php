<section class="content-header">
  <h1>Cetak Kartu <small>Pilih filter untuk cetak kartu peserta</small></h1>
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

  <!-- Filter Section -->
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Filter Cetak Kartu</h3>
        </div>
        <div class="box-body">
          <form action="<?= url('/data-cetak/cetak') ?>" method="GET" target="_blank">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="rombongan">Rombongan</label>
                  <select name="rombongan" id="rombongan" class="form-control">
                    <option value="semua">Semua</option>
                    <?php foreach ($rombonganList as $rombongan): ?>
                      <option value="<?= htmlspecialchars($rombongan) ?>"><?= htmlspecialchars($rombongan) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="regu">Regu</label>
                  <select name="regu" id="regu" class="form-control">
                    <option value="semua">Semua</option>
                    <!-- Regu options will be populated based on selected rombongan -->
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-print"></i> Print Kartu
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$title = 'Cetak Kartu';
$scripts = '
<script>
  document.getElementById("rombongan").addEventListener("change", function() {
    var rombongan = this.value;
    var reguSelect = document.getElementById("regu");

    // Clear existing options
    reguSelect.innerHTML = "<option value=\"semua\">Semua</option>";

    if (rombongan !== "semua") {
      fetch("' . url('/get-regu/') . '" + encodeURIComponent(rombongan))
        .then(response => response.json())
        .then(data => {
          if (Array.isArray(data)) {
            data.forEach(function(regu) {
              var option = document.createElement("option");
              option.value = regu;
              option.textContent = regu;
              reguSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error("Error:", error);
        });
    }
  });
</script>';
?>
