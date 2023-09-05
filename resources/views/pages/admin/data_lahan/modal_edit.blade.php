<div class="modal fade" id="edit-{{ $data_lahan->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Nama Lahan</label>
                    <input class="form-control" type="text" name="nama_lahan" placeholder="nama_lahan"
                        value="{{ $data_lahan->nama_lahan }}">
                </div>
                <div class="form-group mb-3">
                    <label>Data Lahan</label>
                    <input class="form-control" type="text" name="data_lahan" placeholder="data_lahan"
                        value="{{ $data_lahan->data_lahan }}">
                </div>
                <div class="form-group mb-3">
                    <label>luas Lahan</label>
                    <input class="form-control" type="text" name="luas_lahan" placeholder="luas_lahan"
                        value="{{ $data_lahan->luas_lahan }}">
                </div>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

            </div>
        </div>
    </div>
</div>
