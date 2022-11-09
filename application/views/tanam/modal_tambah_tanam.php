<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h3 class="modal-title">Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id_tanam" />
                    <div class="form-group row ">
                    <label for="cluster" class="col-sm-3 col-form-label">Lahan</label>
                        <div class="col-sm-9 kosong">
                            <select class="form-control select2" name="id_lahan" id="id_lahan">
                                <option value="0" selected disabled>-- Pilih Lahan --</option>
                                <?php
                                foreach ($lahan as $l) { ?>
                                    <option value="<?= $l->id_lahan; ?>"><?= $l->lokasi; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row ">
                    <label for="cluster" class="col-sm-3 col-form-label">Kode Produksi</label>
                        <div class="col-sm-9 kosong">
                            <select class="form-control select2" name="id_produksi" id="id_produksi">
                                <option value="0" selected disabled>-- Pilih Kode Produksi --</option>
                                <?php
                                foreach ($budidaya as $b) { ?>
                                    <option value="<?= $b->id_produksi; ?>"><?= $b->kd_produksi; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Tanam</label>
                        <div class="input-group col-sm-9 kosong">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input type="date" class="form-control float-right" name="tgl_tanam" id="tgl_tanam">
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label for="judul" class="col-sm-3 col-form-label">Jumlah Tanaman</label>
                        <div class="col-sm-9 kosong">
                            <input type="number" class="form-control" name="jml_tanam" id="jml_tanam" placeholder="Jumlah Tanaman">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Simpan</button>
                <button type="button" id="btnCancel" class="btn btn-danger" onclick="batal()" data-dismiss="modal">Batal</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->