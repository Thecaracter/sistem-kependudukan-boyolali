@extends('layouts.app')

@section('title', 'Dashboard - SIDESPIN')

@section('content')
<div class="space-y-8">
    <!-- Header dengan Statistik Utama -->
    <div class="bg-white rounded-xl border border-gray-100 p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->username }}!</h2>
                <p class="mt-2 text-gray-600">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="hidden md:block">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Total KK dengan Growth -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-blue-50 to-blue-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600">Total Kartu Keluarga</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalKK) }}</p>
                        <div class="mt-2 flex items-center gap-1">
                            @if($kkGrowth > 0)
                                <span class="text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-green-600">+{{ number_format($kkGrowth, 1) }}%</span>
                            @else
                                <span class="text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-red-600">{{ number_format($kkGrowth, 1) }}%</span>
                            @endif
                            <span class="text-sm text-gray-500 ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3">
                        <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Penduduk dengan Growth -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-green-50 to-green-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600">Total Penduduk</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalPenduduk) }}</p>
                        <div class="mt-2 flex items-center gap-1">
                            @if($pendudukGrowth > 0)
                                <span class="text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-green-600">+{{ number_format($pendudukGrowth, 1) }}%</span>
                            @else
                                <span class="text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-red-600">{{ number_format($pendudukGrowth, 1) }}%</span>
                            @endif
                            <span class="text-sm text-gray-500 ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Rumah dengan Growth -->
            <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-purple-50 to-purple-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Total Rumah</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($totalRumah) }}</p>
                        <div class="mt-2 flex items-center gap-1">
                            @if($rumahGrowth > 0)
                                <span class="text-green-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-green-600">+{{ number_format($rumahGrowth, 1) }}%</span>
                            @else
                                <span class="text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                                    </svg>
                                </span>
                                <span class="text-sm text-red-600">{{ number_format($rumahGrowth, 1) }}%</span>
                            @endif
                            <span class="text-sm text-gray-500 ml-1">vs bulan lalu</span>
                        </div>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3">
                        <svg class="h-8 w-8 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Demografi -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Distribusi Usia -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Distribusi Usia Penduduk</h3>
            </div>
            <div class="p-6">
                <canvas id="usiaChart" height="300"></canvas>
            </div>
        </div>

        <!-- Komposisi Keluarga -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Komposisi Keluarga</h3>
            </div>
            <div class="p-6">
                <canvas id="komposisiChart" height="300"></canvas>
            </div>
        </div>

        <!-- Trend Pertumbuhan Penduduk -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden lg:col-span-2">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Trend Pertumbuhan Penduduk</h3>
            </div>
            <div class="p-6">
                <canvas id="pertumbuhanChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistik Pendidikan dan Verifikasi -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Statistik Pendidikan -->
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="border-b border-gray-100 px-6 py-4">
        <h3 class="font-semibold text-gray-900">Tingkat Pendidikan</h3>
    </div>
    <div class="p-6">
        <canvas id="pendidikanChart" height="300"></canvas>
    </div>
</div>

        <!-- Trend Verifikasi -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Trend Verifikasi</h3>
            </div>
            <div class="p-6">
                <canvas id="verifikasiTrendChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Statistik Rumah -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- Tipe Lantai dan Atap -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Tipe Lantai dan Atap</h3>
            </div>
            <div class="p-6">
                <canvas id="rumahChart" height="300"></canvas>
            </div>
        </div>

        <!-- Distribusi Kamar -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4">
                <h3 class="font-semibold text-gray-900">Distribusi Jumlah Kamar</h3>
            </div>
            <div class="p-6">
                <canvas id="kamarChart" height="300"></canvas>
            </div>
        </div>
    </div>

    <!-- Data Terbaru -->
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        <!-- KK Terbaru -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900">KK Terbaru</h3>
                <span class="text-sm text-gray-500">{{ count($recentKK) }} data terbaru</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentKK as $kk)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ $kk['kepala_keluarga']['nama'] ?? 'Belum ada kepala keluarga' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">No. KK: {{ $kk['nomor_kk'] }}</p>
                                <p class="mt-1 text-xs text-gray-500">
                                    Anggota: {{ $kk['jumlah_anggota'] }} orang
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                    {{ $kk['status_verifikasi'] === 'verified' ? 'bg-green-50 text-green-700' :
                                       ($kk['status_verifikasi'] === 'pending' ? 'bg-yellow-50 text-yellow-700' :
                                        'bg-gray-50 text-gray-700') }}">
                                    {{ ucfirst($kk['status_verifikasi']) }}
                                </span>
                                <p class="mt-1 text-xs text-gray-500">{{ $kk['tanggal_pembuatan'] }}</p>
                            </div>
                        </div>
                        @if($kk['alamat'])
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="font-medium">Alamat:</span> {{ $kk['alamat'] }}
                            </p>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="mt-4 text-sm text-gray-500">Belum ada data KK</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Verifikasi Pending -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900">Verifikasi Pending</h3>
                <span class="text-sm text-gray-500">{{ count($pendingVerifikasi) }} menunggu verifikasi</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($pendingVerifikasi as $verifikasi)
                    <div class="p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $verifikasi['kk']['kepala_keluarga'] ?? 'Belum ada kepala keluarga' }}</p>
                                <p class="mt-1 text-sm text-gray-500">No. KK: {{ $verifikasi['kk']['nomor'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                                    Menunggu Verifikasi
                                </span>
                                <p class="mt-1 text-xs text-gray-500">{{ $verifikasi['tanggal_pengajuan'] }}</p>
                            </div>
                        </div>
                        @if($verifikasi['kk']['alamat'])
                            <p class="mt-2 text-sm text-gray-500">
                                <span class="font-medium">Alamat:</span> {{ $verifikasi['kk']['alamat'] }}
                            </p>
                        @endif
                        @if($verifikasi['keterangan'])
                            <div class="mt-2 rounded-md bg-gray-50 p-2">
                                <p class="text-sm text-gray-600">
                                    <span class="font-medium">Keterangan:</span> {{ $verifikasi['keterangan'] }}
                                </p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="mt-4 text-sm text-gray-500">Tidak ada verifikasi yang pending</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css">
<style>
    .chart-container {
        position: relative;
        margin: auto;
    }
    .chart-container canvas {
        transition: all 0.3s ease;
    }
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    // Utility Functions
    function createGradient(ctx, colors) {
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        colors.forEach((color, index) => {
            gradient.addColorStop(index / (colors.length - 1), color);
        });
        return gradient;
    }

    // Global Chart Settings
    Chart.defaults.global.defaultFontFamily = 'Inter var, system-ui, -apple-system, sans-serif';
    Chart.defaults.global.defaultFontColor = '#4B5563';
    Chart.defaults.global.defaultFontSize = 12;
    Chart.defaults.global.elements.line.tension = 0.4;
    Chart.defaults.global.tooltips.backgroundColor = 'rgba(17, 24, 39, 0.9)';
    Chart.defaults.global.tooltips.titleFontSize = 13;
    Chart.defaults.global.tooltips.titleFontStyle = 'normal';
    Chart.defaults.global.tooltips.bodyFontSize = 12;
    Chart.defaults.global.tooltips.xPadding = 12;
    Chart.defaults.global.tooltips.yPadding = 12;

    // Distribusi Usia Chart
    const usiaData = {!! json_encode($demografiStats['usia']) !!};
    new Chart(document.getElementById('usiaChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['Balita', 'Anak', 'Remaja', 'Dewasa', 'Lansia'],
            datasets: [{
                data: Object.values(usiaData),
                backgroundColor: ['#60A5FA', '#34D399', '#FBBF24', '#F87171', '#818CF8'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12,
                    padding: 20
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const value = data.datasets[0].data[tooltipItem.index];
                        const percentage = Math.round((value / Object.values(usiaData).reduce((a, b) => a + b)) * 100);
                        return `${value.toLocaleString()} orang (${percentage}%)`;
                    }
                }
            }
        }
    });

    // Komposisi Keluarga Chart
    const komposisiData = {!! json_encode($demografiStats['komposisi_keluarga']) !!};
    new Chart(document.getElementById('komposisiChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(komposisiData).map(label => label.replace('_', ' ').toUpperCase()),
            datasets: [{
                data: Object.values(komposisiData),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                    '#EC4899', '#8B5CF6', '#14B8A6', '#F97316'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 70,
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12,
                    padding: 20
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const value = data.datasets[0].data[tooltipItem.index];
                        const total = data.datasets[0].data.reduce((a, b) => a + b);
                        const percentage = Math.round((value / total) * 100);
                        return `${value.toLocaleString()} orang (${percentage}%)`;
                    }
                }
            }
        }
    });

    // Trend Pertumbuhan Chart
    const pertumbuhanData = {!! json_encode($demografiStats['pertumbuhan_bulanan']) !!};
    const ctx = document.getElementById('pertumbuhanChart').getContext('2d');
    const gradient = createGradient(ctx, ['rgba(59, 130, 246, 0.5)', 'rgba(59, 130, 246, 0.0)']);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: Object.keys(pertumbuhanData).map(date => {
                const [year, month] = date.split('-');
                return new Date(year, month - 1).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
            }),
            datasets: [{
                label: 'Jumlah Penduduk',
                data: Object.values(pertumbuhanData),
                borderColor: '#3B82F6',
                backgroundColor: gradient,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#FFFFFF',
                pointBorderColor: '#3B82F6',
                pointHoverRadius: 6,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 6
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toLocaleString() + ' orang';
                        }
                    },
                    gridLines: {
                        color: '#EDF2F7',
                        zeroLineColor: '#EDF2F7',
                        drawBorder: false
                    }
                }]
            },
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return `${tooltipItem.value.toLocaleString()} orang`;
                    }
                }
            }
        }
    });

    // Pendidikan Chart
    const pendidikanData = {!! json_encode($pendidikanStats) !!};
    const pendidikanCtx = document.getElementById('pendidikanChart').getContext('2d');
    const pendidikanGradient = createGradient(pendidikanCtx, ['#4F46E5', '#818CF8']);

    new Chart(pendidikanCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(pendidikanData).map(label => label.toUpperCase()),
            datasets: [{
                label: 'Jumlah Penduduk',
                data: Object.values(pendidikanData),
                backgroundColor: pendidikanGradient,
                borderWidth: 0,
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        padding: 10,
                        fontColor: '#4B5563'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toLocaleString() + ' orang';
                        },
                        padding: 10,
                        fontColor: '#4B5563'
                    },
                    gridLines: {
                        color: '#E5E7EB',
                        drawBorder: false,
                        zeroLineColor: '#E5E7EB'
                    }
                }]
            },
            tooltips: {
                displayColors: false,
                callbacks: {
                    label: function(tooltipItem, data) {
                        const value = tooltipItem.value;
                        const total = Object.values(pendidikanData).reduce((a, b) => a + b);
                        const percentage = Math.round((value / total) * 100);
                        return `${parseInt(value).toLocaleString()} orang (${percentage}%)`;
                    }
                }
            }
        }
    });

    // Verifikasi Chart
    const verifikasiData = {!! json_encode($verifikasiStats) !!};
    new Chart(document.getElementById('verifikasiChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(verifikasiData).map(status => status.charAt(0).toUpperCase() + status.slice(1)),
            datasets: [{
                data: Object.values(verifikasiData),
                backgroundColor: ['#22C55E', '#EAB308', '#EF4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 75,
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    boxWidth: 12,
                    usePointStyle: true
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const value = data.datasets[0].data[tooltipItem.index];
                        const total = data.datasets[0].data.reduce((a, b) => a + b);
                        const percentage = Math.round((value / total) * 100);
                        return `${value.toLocaleString()} KK (${percentage}%)`;
                    }
                }
            }
        }
    });

    // Rumah Stats Chart
    const rumahStats = {
        tipeLantai: {!! json_encode($rumahStats['tipe_lantai']) !!},
        atap: {!! json_encode($rumahStats['atap']) !!}
    };

    new Chart(document.getElementById('rumahChart').getContext('2d'), {
        type: 'radar',
        data: {
            labels: [...Object.keys(rumahStats.tipeLantai), ...Object.keys(rumahStats.atap)].map(label => 
                label.charAt(0).toUpperCase() + label.slice(1)
            ),
            datasets: [{
                label: 'Jumlah Rumah',
                data: [...Object.values(rumahStats.tipeLantai), ...Object.values(rumahStats.atap)],
                backgroundColor: 'rgba(99, 102, 241, 0.2)',
                borderColor: '#6366F1',
                pointBackgroundColor: '#6366F1',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#6366F1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scale: {
                ticks: {
                    beginAtZero: true,
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        return `${tooltipItem.value.toLocaleString()} rumah`;
                    }
                }
            }
        }
    });

    // Distribusi Kamar Chart
    const kamarData = {
        kamarTidur: {!! json_encode($rumahStats['distribusi_kamar']['kamar_tidur']) !!},
        kamarMandi: {!! json_encode($rumahStats['distribusi_kamar']['kamar_mandi']) !!}
    };

    new Chart(document.getElementById('kamarChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(kamarData.kamarTidur).map(num => `${num} Kamar`),
            datasets: [
                {
                    label: 'Kamar Tidur',
                    data: Object.values(kamarData.kamarTidur),
                    backgroundColor: '#60A5FA',
                    borderWidth: 0,
                    barPercentage: 0.6
                },
                {
                    label: 'Kamar Mandi',
                    data: Object.values(kamarData.kamarMandi),
                    backgroundColor: '#F472B6',
                    borderWidth: 0,
                    barPercentage: 0.6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: false,
                    gridLines: {
                        display: false
                    }
                }],
                yAxes: [{
                    stacked: false,
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {
                            return value.toLocaleString() + ' Rumah';
                        }
                    },
                    gridLines: {
                        color: '#EDF2F7',
                        zeroLineColor: '#EDF2F7',
                        drawBorder: false
                    }
                }]
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        const dataset = data.datasets[tooltipItem.datasetIndex];
                        const value = dataset.data[tooltipItem.index];
                        const total = data.datasets[0].data.reduce((a, b) => a + b);
                        const percentage = Math.round((value / total) * 100);
                        return `${dataset.label}: ${value.toLocaleString()} rumah (${percentage}%)`;
                    }
                }
            }
        }
    });
</script>
@endpush