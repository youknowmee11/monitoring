<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('/profile/create') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Tambah Admin') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama</label>
                        <input class="form-control" type="text" name="name" placeholder="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama akhir</label>
                        <input class="form-control" type="text" name="last_name" placeholder="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input class="form-control" type="text" name="email" placeholder="email" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>password</label>
                        <input class="form-control" type="password" name="password" placeholder="password" required>
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
