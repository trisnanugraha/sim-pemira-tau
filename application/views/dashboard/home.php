<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $lahan; ?></h3>

                        <p>Total Lahan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <a href="<?php echo base_url('lahan'); ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3><?php echo $budidaya; ?></h3>

                        <p>Total Budidaya</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <a href="<?php echo base_url('budidaya'); ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $panen; ?></h3>

                        <p>Total Panen</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <a href="<?php echo base_url('panen'); ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $tanam; ?></h3>

                        <p>Total Tanam</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <a href="<?php echo base_url('tanam'); ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <br>
    </div>
    <!-- /.row -->
</section>