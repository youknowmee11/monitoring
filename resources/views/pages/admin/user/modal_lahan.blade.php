<div class="modal fade" id="lahan-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Data lahan milik ' . $item->name) }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover">
                    @foreach (App\Models\DataLahan::where('id_user', $item->id)->get() as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_lahan }}</td>
                            <td>Luas {{ $item->luas_lahan }} Ha</td>
                            <td>Code Alat :<br> <strong>{{ $item->code_alat }} </strong></td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>

            </div>
        </div>
    </div>
</div>
