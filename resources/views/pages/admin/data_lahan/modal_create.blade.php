<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('data_lahan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Tambah Data') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Code Alat</label>
                        <input class="form-control" type="text" name="code_alat" placeholder="Code Alat" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Lahan </label>
                        <input class="form-control" type="text" name="nama_lahan" placeholder="Nama Lahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Luas Lahan (Ha)</label>
                        <input class="form-control" type="number" name="luas_lahan" placeholder="Luas Lahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Lahan</label>
                        <input class="form-control" type="text" name="data_lahan" placeholder="Data Lahan" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Jenis Jagung</label>
                        <select name="id_jenis_jagung" class="form-control">
                            @foreach (App\Models\JenisJagung::latest()->get() as $list)
                                <option value="{{ $list->id }}">{{ $list->jenis_jagung }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Terakhir Tanam</label>
                        <input class="form-control" type="date" name="terakhir_tanam" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
