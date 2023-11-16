<div class="col-12">
    <div class="card">
        <div class="card-header">
            <strong class="">Lahan {{ $item->nama_lahan }}</strong> | Status Alat
            <span class="badge badge-danger" id="status_alat">OFFLINE</span>
        </div>
        <div class="card-body">
            <canvas id="myChart" width="100%" style="max-height: 400px;"></canvas>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary">Data lahan :</h5>
                    <strong>Nama Lahan :</strong> {{ $item->nama_lahan }}<br>
                    <strong>Luas Lahan :</strong> {{ $item->luas_lahan }} Ha<br>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary">Data Pengukuran :</h5>
                    <strong>PH 1 :</strong> <span id="ph1"></span><br>
                    <strong>PH 2 :</strong> <span id="ph2"></span><br>
                    <strong>TDS 1 :</strong> <span id="salinitas1"></span><br>
                    <strong>TDS 2 :</strong> <span id="salinitas2"></span><br>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary">Data Alat :</h5>
                    <strong>Code Alat :</strong> {{ $item->alat->code_alat }}<br>
                    <strong>Tanggal Pembuatan :</strong> {{ $item->alat->tanggal_buat }}<br>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var statusAlatElement = document.getElementById('status_alat');
            setInterval(function() {
                updateSensorData();
                checkAlatStatus();
            }, 5000);
            // Fungsi untuk mengupdate data setiap 5 detik
            setInterval(updateChart, 5000);

            // Fungsi untuk mengupdate data sensor
            function updateSensorData() {
                // Menggunakan fetch untuk mendapatkan data terbaru dari API
                fetch('https://mon-ph.mixdev.id/api/sensor')
                    .then(response => response.json())
                    .then(data => {
                        // Sort data berdasarkan created_at dalam urutan descending
                        const sortedData = data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                        // Filter data berdasarkan code_alat
                        const filteredData = sortedData.filter(item => item.code_alat ===
                            '{{ $item->code_alat }}');

                        // Filter data hanya untuk hari ini
                        const todayData = filterToday(filteredData);

                        // Memeriksa apakah data ada (valid)
                        if (todayData.length > 0) {
                            // Mengupdate HTML dengan data terbaru yang diperoleh dari API
                            document.getElementById('ph1').innerText = todayData[0].ph1;
                            document.getElementById('ph2').innerText = todayData[0].ph2;
                            document.getElementById('salinitas1').innerText = todayData[0].salinitas1;
                            document.getElementById('salinitas2').innerText = todayData[0].salinitas2;
                        } else {
                            console.error('Invalid or empty data received from the server.');
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Fungsi untuk mendapatkan data terbaru
            function getLatestData() {
                // Menggunakan fetch untuk mendapatkan data terbaru dari API
                return fetch('https://mon-ph.mixdev.id/api/sensor')
                    .then(response => response.json())
                    .then(data => {
                        // Sort data berdasarkan created_at dalam urutan descending
                        const sortedData = data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

                        // Filter data berdasarkan code_alat
                        const filteredData = sortedData.filter(item => item.code_alat ===
                            '{{ $item->code_alat }}');

                        // Ambil data pertama (terbaru)
                        const latestData = filteredData.length > 0 ? filteredData[0] : null;

                        return latestData;
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }
            // Fungsi untuk memeriksa status alat
            function checkAlatStatus() {
                // Mendapatkan waktu saat ini
                var currentTime = new Date().getTime();

                // Mendapatkan waktu update terakhir
                var lastUpdate = new Date(getLatestData().created_at).getTime();

                // Menghitung selisih waktu dalam detik
                var timeDifferenceInSeconds = (currentTime - lastUpdate) / 1000;

                // Memperbarui status alat berdasarkan selisih waktu
                if (timeDifferenceInSeconds > 5) {
                    statusAlatElement.innerText = 'OFFLINE';
                    statusAlatElement.classList.add('badge-danger');
                    statusAlatElement.classList.remove('badge-success');
                } else {
                    statusAlatElement.innerText = 'ONLINE';
                    statusAlatElement.classList.remove('badge-danger');
                    statusAlatElement.classList.add('badge-success');
                }
            }

            function updateChart() {
                // Menggunakan fetch untuk mendapatkan data dari API
                fetch('https://mon-ph.mixdev.id/api/sensor')
                    .then(response => response.json())
                    .then(data => {
                        // Filter data berdasarkan code_alat
                        var filteredData = filterByCodeAlat(data, '{{ $item->code_alat }}');

                        // Filter data hanya untuk hari ini
                        filteredData = filterToday(filteredData);

                        // Mengelompokkan data berdasarkan properti created_at
                        var groupedData = groupDataByCreatedAt(filteredData);

                        // Membuat array untuk labels dan datasets
                        var labels = Object.keys(groupedData);
                        var datasets = [];

                        // Mengolah data untuk setiap properti (ph1, ph2, salinitas1, salinitas2)
                        ['ph1', 'ph2', 'salinitas1', 'salinitas2'].forEach(property => {
                            var values = [];

                            // Mengisi array values sesuai dengan properti
                            labels.forEach(created_at => {
                                values.push(groupedData[created_at][property]);
                            });

                            // Menambahkan dataset untuk properti tertentu
                            datasets.push({
                                label: property,
                                data: values,
                                backgroundColor: getBackgroundColor(property),
                                borderColor: getBorderColor(property),
                                borderWidth: 1
                            });
                        });

                        // Memperbarui data grafik dengan data yang diperoleh dari API
                        if (myChart) {
                            myChart.destroy(); // Hapus grafik sebelum membuat yang baru
                        }

                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels.map(
                                    formatDateTime
                                ), // Menggunakan fungsi formatDate untuk format tanggal
                                datasets: datasets
                            },
                            options: {
                                animation: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Fungsi untuk menyaring data berdasarkan code_alat
            function filterByCodeAlat(data, codeAlat) {
                return data.filter(item => item.code_alat === codeAlat);
            }

            // Fungsi untuk mengelompokkan data berdasarkan created_at
            function groupDataByCreatedAt(data) {
                return data.reduce(function(acc, item) {
                    var created_at = item.created_at;
                    if (!acc[created_at]) {
                        acc[created_at] = {};
                    }
                    // Menyimpan data ph1, ph2, salinitas1, salinitas2 untuk setiap created_at
                    acc[created_at].ph1 = item.ph1;
                    acc[created_at].ph2 = item.ph2;
                    acc[created_at].salinitas1 = item.salinitas1;
                    acc[created_at].salinitas2 = item.salinitas2;
                    return acc;
                }, {});
            }

            // Fungsi untuk mendapatkan warna latar belakang sesuai dengan properti
            function getBackgroundColor(property) {
                switch (property) {
                    case 'ph1':
                        return 'rgba(255, 99, 132, 0.2)';
                    case 'ph2':
                        return 'rgba(54, 162, 235, 0.2)';
                    case 'salinitas1':
                        return 'rgba(255, 206, 86, 0.2)';
                    case 'salinitas2':
                        return 'rgba(75, 192, 192, 0.2)';
                    default:
                        return '';
                }
            }

            // Fungsi untuk mendapatkan warna batas sesuai dengan properti
            function getBorderColor(property) {
                switch (property) {
                    case 'ph1':
                        return 'rgba(255, 99, 132, 1)';
                    case 'ph2':
                        return 'rgba(54, 162, 235, 1)';
                    case 'salinitas1':
                        return 'rgba(255, 206, 86, 1)';
                    case 'salinitas2':
                        return 'rgba(75, 192, 192, 1)';
                    default:
                        return '';
                }
            }

            // Fungsi untuk format tanggal dan waktu
            function formatDateTime(dateString) {
                return new Intl.DateTimeFormat('id-ID', {
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: false,
                }).format(new Date(dateString));
            }

            // Fungsi untuk menyaring data hanya untuk hari ini
            function filterToday(data) {
                var today = new Date();
                var todayDateString = today.toISOString().split('T')[0];
                return data.filter(item => item.created_at.includes(todayDateString));
            }

            // Inisialisasi grafik
            var myChart = null;
            updateChart(); // Panggil pertama kali untuk menginisialisasi grafik
            // Panggil pertama kali untuk menginisialisasi data
            updateSensorData();
            checkAlatStatus();
        });
    </script>
@endpush
