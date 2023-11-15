<!-- Delete Modal-->
<div class="modal fade" id="qr-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __($item->code_alat) }}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body text-center">
                {{ QrCode::size(400)->generate($item->code_alat) }}
            </div>
            <div class="modal-footer">
                <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-primary" target="__blank"
                    href="{{ route('alat.print', $item->id) }}">{{ __('Cetak') }}</a>
            </div>
        </div>
    </div>
</div>
