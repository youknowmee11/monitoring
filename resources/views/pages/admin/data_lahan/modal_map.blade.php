<!-- Map Modal -->
<div class="modal" id="mapModal{{ $item->id }}" tabindex="-1" role="dialog"
    aria-labelledby="mapModalLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel{{ $item->id }}">Peta Lahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Map container with a dynamic ID -->
                <div id="map{{ $item->id }}" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Leaflet JavaScript for each item -->
@push('script')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        // Fungsi ini akan dipanggil setelah modal ditampilkan
        $('#mapModal{{ $item->id }}').on('shown.bs.modal', function() {
            var initialCenter = [{{ $item->latitude }}, {{ $item->longitude }}];
            var mymap = L.map('map{{ $item->id }}').setView(initialCenter, 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(mymap);

            var marker = L.marker(initialCenter, {
                draggable: true
            }).addTo(mymap);

            marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                // Assuming you have hidden input fields for latitude and longitude
                document.getElementById('latitude-edit-{{ $item->id }}').value = position.lat;
                document.getElementById('longitude-edit-{{ $item->id }}').value = position.lng;
            });
        });
    </script>
@endpush
