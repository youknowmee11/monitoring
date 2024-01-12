<div class="col-12">
    <div class="card">
        <div class="card-header">
            <strong class="">Lahan {{ $item->nama_lahan ?? 'null' }}</strong> | Status Alat
            <span class="badge badge-danger" id="status_alat">OFFLINE</span>
        </div>
        <div class="card-body">
            <canvas id="chart1" width="100%" style="max-height: 200px;"></canvas>
            <canvas id="chart2" width="100%" style="max-height: 200px;"></canvas>
            <hr>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary">Data lahan :</h5>
                    <strong>Nama Lahan :</strong> {{ $item->nama_lahan ?? 'null' }}<br>
                    <strong>Luas Lahan :</strong> {{ $item->luas_lahan ?? '0' }} Ha<br>
                </div>
                <div class="col-lg-4 col-md-6" id="pengukuranData">
                    <h5 class="text-primary">Data Pengukuran :</h5>
                    <strong>PH 1 :</strong> <span id="ph1"></span><br>
                    <strong>PH 2 :</strong> <span id="ph2"></span><br>
                    <strong>TDS 1 :</strong> <span id="salinitas1"></span><br>
                    <strong>TDS 2 :</strong> <span id="salinitas2"></span><br>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-primary">Data Alat :</h5>
                    <strong>Code Alat :</strong> {{ $item->alat->code_alat ?? 'null' }}<br>
                    <strong>Tanggal Pembuatan :</strong> {{ $item->alat->tanggal_buat ?? 'null' }}<br>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx1 = document.getElementById('chart1').getContext('2d');
            var ctx2 = document.getElementById('chart2').getContext('2d');
            var statusAlatElement = document.getElementById('status_alat');

            setInterval(function() {
                updateSensorData();
                checkAlatStatus();
            }, 5000);

            // Fungsi untuk mengupdate data setiap 5 detik
            setInterval(updateCharts, 5000);

            function updateCharts() {
                updateChart('chart1', ['ph1', 'ph2'], ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'], [
                    'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'
                ]);
                updateChart('chart2', ['salinitas1', 'salinitas2'], ['rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ], ['rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)']);
            }

            function updateSensorData() {
                // Menggunakan fetch untuk mendapatkan data terbaru dari API
                // fetch('https://mon-ph.mixdev.id/api/sensor')
                fetch("{{ url('api/sensor') }}")
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

                            // Additional code to update the values in the #pengukuranData div
                            document.getElementById('pengukuranData').innerHTML = `
                    <h5 class="text-primary">Data Pengukuran :</h5>
                    <strong>PH 1 :</strong> ${todayData[0].ph1}<br>
                    <strong>PH 2 :</strong> ${todayData[0].ph2}<br>
                    <strong>TDS 1 :</strong> ${todayData[0].salinitas1}<br>
                    <strong>TDS 2 :</strong> ${todayData[0].salinitas2}<br>
                `;
                        } else {
                            console.error('Invalid or empty data received from the server.');
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function updateChart(chartId, properties, backgroundColors, borderColors) {
                // Menggunakan fetch untuk mendapatkan data dari API
                fetch("{{ url('api/sensor') }}")
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
                        properties.forEach((property, index) => {
                            var values = [];

                            // Mengisi array values sesuai dengan properti
                            labels.forEach(created_at => {
                                values.push(groupedData[created_at][property]);
                            });

                            // Menambahkan dataset untuk properti tertentu
                            datasets.push({
                                label: property,
                                data: values,
                                backgroundColor: backgroundColors[index],
                                borderColor: borderColors[index],
                                borderWidth: 1
                            });
                        });

                        // Memperbarui data grafik dengan data yang diperoleh dari API
                        var ctx = document.getElementById(chartId).getContext('2d');
                        if (chartId === 'chart1' && myChart1) {
                            myChart1.destroy(); // Hapus grafik sebelum membuat yang baru
                        } else if (chartId === 'chart2' && myChart2) {
                            myChart2.destroy(); // Hapus grafik sebelum membuat yang baru
                        }

                        var myChart = new Chart(ctx, {
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

                        if (chartId === 'chart1') {
                            myChart1 = myChart;
                        } else if (chartId === 'chart2') {
                            myChart2 = myChart;
                        }
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
            var myChart1 = null;
            var myChart2 = null;
            updateCharts(); // Panggil pertama kali untuk menginisialisasi grafik
            // Panggil pertama kali untuk menginisialisasi data
            updateSensorData();
            checkAlatStatus();
        });
    </script>
@endpush
