<div class="modal fade" id="edit-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('data_lahan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Ubah Data') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Code Alat</label>
                        <input class="form-control" type="text" name="code_alat" placeholder="Code Alat" required
                            value="{{ $item->code_alat }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Lahan </label>
                        <input class="form-control" type="text" name="nama_lahan" placeholder="Nama Lahan" required
                            value="{{ $item->nama_lahan }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Luas Lahan (Ha)</label>
                        <input class="form-control" type="number" name="luas_lahan" placeholder="Luas Lahan" required
                            value="{{ $item->luas_lahan }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Informasi Lahan</label>
                        <input class="form-control" type="text" name="informasi_lahan" placeholder="Data Lahan"
                            required value="{{ $item->informasi_lahan }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Jenis Jagung</label>
                        <select name="id_jenis_jagung" class="form-control">
                            @foreach (App\Models\JenisJagung::latest()->get() as $list)
                                <option value="{{ $list->id }}" @if ($item->id_jenis_jagung == $list->id) selected @endif>
                                    {{ $list->jenis_jagung }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label>Data Terakhir Tanam</label>
                        <input class="form-control" type="date" name="terakhir_tanam"
                            value="{{ $item->terakhir_tanam }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Pilih Lokasi</label><br>
                        <small class="text-danger">*Geser pin dan sesuaikan dengan lahan</small>
                        <div id="map-edit-{{ $item->id }}" style="height: 300px;"></div>
                    </div>
                    <input type="hidden" name="latitude" id="latitude-edit-{{ $item->id }}" required
                        value="{{ $item->latitude }}">
                    <input type="hidden" name="longitude" id="longitude-edit-{{ $item->id }}" required
                        value="{{ $item->longitude }}">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('script')
    <!-- Add Leaflet JavaScript -->

    <script>
        // Fungsi ini akan dipanggil setelah modal ditampilkan
        $('#edit-{{ $item->id }}').on('shown.bs.modal', function() {
            var initialCenter = [{{ $item->latitude }}, {{ $item->longitude }}];
            console.log('Initial Center:', initialCenter);

            var mymap = L.map('map-edit-{{ $item->id }}');

            if (initialCenter[0] !== null && initialCenter[1] !== null) {
                mymap.setView(initialCenter, 10);
            } else {
                console.warn('Invalid initial center, using default center.');
                initialCenter = [-8.42347, 140.37370];
                mymap.setView(initialCenter, 10);
            }

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mymap);
            console.log('Tile layer added.');

            var marker = L.marker(initialCenter, {
                draggable: true
            }).addTo(mymap);

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                if (position && position.lat !== null) {
                    document.getElementById('latitude-edit-{{ $item->id }}').value = position.lat;
                    document.getElementById('longitude-edit-{{ $item->id }}').value = position.lng;
                } else {
                    console.error('Invalid position:', position);
                }
            });

            console.log('Map initialized and marker added.');
        });
    </script>
@endpush
