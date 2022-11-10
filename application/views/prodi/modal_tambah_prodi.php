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
                    <input type="hidden" value="" name="id_prodi" />
                    <div class="form-group row ">
                        <label for="id_fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10 kosong">
                            <select class="form-control select2" name="id_fakultas" id="id_fakultas">
                                <option value="0" selected disabled>-- Pilih Fakultas --</option>
                                <?php
                                foreach ($fakultas as $f) { ?>
                                    <option value="<?= $f->id_fakultas; ?>"><?= $f->nama_fakultas; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row ">
                        <label for="nama_prodi" class="col-sm-2 col-form-label">Nama Prodi</label>
                        <div class="col-sm-10 kosong">
                            <input type="text" class="form-control" name="nama_prodi" id="nama_prodi">
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