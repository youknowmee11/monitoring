<div class="modal fade" id="edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit {{ $item->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <input class="modal-body" type="text" value="{{ $item->name }}">
            <input class="modal-body" type="text" value="{{ $item->email }}">
            <input class="modal-body" type="text" value="{{ $item->last_name }}">
            <input class="modal-body" type="text" value="{{ $item->tempat_lahir }}">
            <input class="modal-body" type="text" value="{{ $item->tanggal_lahir }}">
            <input class="modal-body" type="text" value="{{ $item->luas_lahan }}">
            <input class="modal-body" type="text" value="{{ $item->nama_lahan }}">
            <input class="modal-body" type="text" value="{{ $item->data_lahan }}">


            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>

            </div>
        </div>
    </div>
</div>

<!-- Delete Modal-->
<div class="modal fade" id="delete-{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Delete this?') }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Delete" for delete this data.</div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <form method="POST" action="{{ route('profile.delete', $item->id) }}" class="d-inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
