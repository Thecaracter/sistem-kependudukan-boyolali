@extends('layouts.app')

@section('title', 'Scan QR Code')

@push('styles')
    <style>
        .scanner-container {
            background: linear-gradient(to bottom right, #1a5f7a, #0f766e);
        }

        #video-container {
            width: 100%;
            max-width: 640px;
            height: 480px;
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        #qr-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scanner-region {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.75);
        }

        .scanner-overlay {
            position: absolute;
            inset: 0;
            border: 2px solid rgba(16, 185, 129, 0.5);
            border-radius: 20px;
        }

        .scan-area {
            position: absolute;
            inset: 0;
            border: 3px solid #10b981;
        }

        .corner-line {
            position: absolute;
            background: #10b981;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
        }

        .corner-line-top,
        .corner-line-bottom {
            height: 3px;
            width: 30px;
        }

        .corner-line-top {
            top: -1.5px;
        }

        .corner-line-bottom {
            bottom: -1.5px;
        }

        .corner-line-left,
        .corner-line-right {
            width: 3px;
            height: 30px;
        }

        .corner-line-left {
            left: -1.5px;
        }

        .corner-line-right {
            right: -1.5px;
        }

        .top-left .corner-line-top,
        .top-left .corner-line-left {
            left: 0;
            top: 0;
        }

        .top-right .corner-line-top,
        .top-right .corner-line-right {
            right: 0;
            top: 0;
        }

        .bottom-left .corner-line-bottom,
        .bottom-left .corner-line-left {
            left: 0;
            bottom: 0;
        }

        .bottom-right .corner-line-bottom,
        .bottom-right .corner-line-right {
            right: 0;
            bottom: 0;
        }

        .scan-line {
            position: absolute;
            width: 100%;
            height: 2px;
            background: #10b981;
            animation: scan 2s linear infinite;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
        }

        @keyframes scan {
            0% {
                top: 0;
                opacity: 1;
            }

            50% {
                top: 100%;
                opacity: 0.5;
            }

            100% {
                top: 0;
                opacity: 1;
            }
        }

        .scan-guide {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            font-size: 0.875rem;
            white-space: nowrap;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .status-indicator {
            position: absolute;
            top: 1rem;
            right: 1rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .status-indicator.active {
            background-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
            animation: pulse 2s infinite;
        }

        .status-indicator.inactive {
            background-color: #6b7280;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            70% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Additional style for camera permission error */
        .camera-error {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.8);
            padding: 2rem;
            text-align: center;
            color: white;
        }

        .camera-error svg {
            width: 4rem;
            height: 4rem;
            color: #ef4444;
            margin-bottom: 1rem;
        }

        .camera-error h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .camera-error p {
            font-size: 0.875rem;
            color: #d1d5db;
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen scanner-container py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">QR Code Scanner</h1>
                <p class="text-emerald-200 text-lg">Arahkan kamera ke QR Code untuk memindai</p>
            </div>

            <!-- Main Scanner Card -->
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 sm:p-8">
                <div class="flex flex-col lg:flex-row gap-8 items-center">
                    <!-- Scanner Section -->
                    <div class="w-full lg:w-2/3">
                        <div class="relative">
                            <div id="video-container" class="bg-gray-900 mx-auto">
                                <video id="qr-video" playsinline></video>
                                <div class="scanner-region">
                                    <div class="scan-area">
                                        <!-- Corner markers -->
                                        <div class="top-left">
                                            <div class="corner-line corner-line-top"></div>
                                            <div class="corner-line corner-line-left"></div>
                                        </div>
                                        <div class="top-right">
                                            <div class="corner-line corner-line-top"></div>
                                            <div class="corner-line corner-line-right"></div>
                                        </div>
                                        <div class="bottom-left">
                                            <div class="corner-line corner-line-bottom"></div>
                                            <div class="corner-line corner-line-left"></div>
                                        </div>
                                        <div class="bottom-right">
                                            <div class="corner-line corner-line-bottom"></div>
                                            <div class="corner-line corner-line-right"></div>
                                        </div>
                                        <!-- Scan line -->
                                        <div class="scan-line"></div>
                                    </div>
                                    <div class="scan-guide">Posisikan QR Code di dalam kotak</div>
                                </div>
                                <div id="status-indicator" class="status-indicator inactive"></div>

                                <!-- Camera Error Message -->
                                <div id="camera-error" class="camera-error" style="display: none;">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <h3>Tidak Dapat Mengakses Kamera</h3>
                                    <p>Pastikan browser diizinkan untuk mengakses kamera perangkat Anda</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Controls Section -->
                    <div class="w-full lg:w-1/3 space-y-6">
                        <!-- Status Card -->
                        <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-white">
                            <h3 class="text-lg font-semibold mb-2">Status Scanner</h3>
                            <p id="scanner-status" class="text-emerald-200">Siap untuk memindai</p>
                        </div>

                        <!-- Control Buttons -->
                        <div class="space-y-3">
                            <button id="start-button"
                                class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                </svg>
                                <span>Mulai Scan</span>
                            </button>

                            <button id="stop-button"
                                class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl transition-all duration-200 flex items-center justify-center gap-2 group">
                                <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span>Stop Scan</span>
                            </button>
                        </div>

                        <!-- Tips Card -->
                        <div class="bg-white/20 backdrop-blur rounded-xl p-4 text-white">
                            <h3 class="text-lg font-semibold mb-2">Tips</h3>
                            <ul class="space-y-2 text-emerald-200 text-sm">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pastikan QR code berada dalam bingkai pemindaian
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Hindari gerakan yang terlalu cepat
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-emerald-400 mt-0.5 flex-shrink-0" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Pastikan pencahayaan cukup terang
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <script>
        const video = document.getElementById('qr-video');
        const startButton = document.getElementById('start-button');
        const stopButton = document.getElementById('stop-button');
        const statusIndicator = document.getElementById('status-indicator');
        const scannerStatus = document.getElementById('scanner-status');
        const cameraError = document.getElementById('camera-error');
        let stream;
        let currentDeviceId = null;

        startButton.addEventListener('click', () => startScanning(currentDeviceId));
        stopButton.addEventListener('click', stopScanning);

        async function startScanning(deviceId = null) {
            try {
                // Reset UI
                cameraError.style.display = 'none';
                updateStatus('starting', 'Memulai kamera...');

                let constraints = {
                    video: {
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                };

                // If specific device requested, use that
                if (deviceId) {
                    constraints.video.deviceId = {
                        exact: deviceId
                    };
                    currentDeviceId = deviceId;
                } else {
                    // Default to back camera if no specific device
                    constraints.video.facingMode = {
                        ideal: 'environment'
                    };
                }

                try {
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                } catch (firstError) {
                    console.log('First attempt failed, trying fallback...', firstError);

                    // Fallback to any camera
                    constraints = {
                        video: true
                    };
                    stream = await navigator.mediaDevices.getUserMedia(constraints);
                }

                video.srcObject = stream;

                // Wait for metadata to load
                await new Promise((resolve) => {
                    video.onloadedmetadata = () => {
                        resolve();
                    };
                });

                await video.play();

                // Update UI
                updateStatus('scanning', 'Memindai...');
                statusIndicator.classList.remove('inactive');
                statusIndicator.classList.add('active');
                startButton.disabled = true;
                startButton.classList.add('opacity-50', 'cursor-not-allowed');
                stopButton.disabled = false;
                stopButton.classList.remove('opacity-50', 'cursor-not-allowed');

                requestAnimationFrame(scan);

            } catch (error) {
                console.error('Camera error:', error);
                handleCameraError(error);
            }
        }

        function stopScanning() {
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop();
                });
                video.srcObject = null;
            }

            updateStatus('ready', 'Scanner berhenti');
            statusIndicator.classList.remove('active');
            statusIndicator.classList.add('inactive');
            startButton.disabled = false;
            startButton.classList.remove('opacity-50', 'cursor-not-allowed');
            stopButton.disabled = true;
            stopButton.classList.add('opacity-50', 'cursor-not-allowed');
        }

        function updateStatus(type, message) {
            scannerStatus.textContent = message;
            if (type === 'error') {
                scannerStatus.classList.remove('text-emerald-200');
                scannerStatus.classList.add('text-red-200');
            } else {
                scannerStatus.classList.remove('text-red-200');
                scannerStatus.classList.add('text-emerald-200');
            }
        }

        function handleCameraError(error) {
            console.error('Camera Error:', error);
            let errorMessage = 'Terjadi kesalahan saat mengakses kamera.';

            if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                errorMessage = 'Akses kamera ditolak. Mohon izinkan akses kamera di pengaturan browser Anda.';
            } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                errorMessage = 'Tidak ada kamera yang ditemukan pada perangkat ini.';
            } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                errorMessage =
                    'Kamera sedang digunakan oleh aplikasi lain. Mohon tutup aplikasi lain yang menggunakan kamera.';
            } else if (error.name === 'OverconstrainedError' || error.name === 'ConstraintNotSatisfiedError') {
                errorMessage = 'Tidak dapat mengakses kamera dengan pengaturan yang diminta.';
            }

            // Show error UI
            cameraError.style.display = 'flex';
            updateStatus('error', errorMessage);

            // Reset buttons
            startButton.disabled = false;
            startButton.classList.remove('opacity-50', 'cursor-not-allowed');
            stopButton.disabled = true;
            stopButton.classList.add('opacity-50', 'cursor-not-allowed');

            alert(errorMessage);
        }

        function scan() {
            if (!video.videoWidth) {
                requestAnimationFrame(scan);
                return;
            }

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                try {
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        handleQRCode(code.data);
                    } else {
                        requestAnimationFrame(scan);
                    }
                } catch (error) {
                    console.error('Scanning error:', error);
                    requestAnimationFrame(scan);
                }
            } else {
                requestAnimationFrame(scan);
            }
        }

        function handleQRCode(qrCode) {
            // Play success sound
            const audio = new Audio('/assets/sounds/beep.mp3');
            audio.play().catch(() => {}); // Ignore error if sound can't play

            // Show scanned result first
            alert('Hasil Scan: ' + qrCode);

            updateStatus('success', 'QR Code terdeteksi! Memproses...');
            stopScanning();

            addLoadingAnimation();

            fetch('{{ route('qr-scanner.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        qr_code: qrCode
                    })
                })
                .then(response => {
                    // Log raw response for debugging
                    console.log('Raw Response:', response);
                    return response.json();
                })
                .then(data => {
                    removeLoadingAnimation();
                    // Log processed data
                    console.log('Processed Data:', data);

                    if (data.success && data.redirect_url) {
                        updateStatus('success', 'QR Code valid! Mengalihkan...');
                        window.location.href = data.redirect_url;
                    } else {
                        let errorMsg = data.message || 'QR Code tidak valid';
                        // Show error details
                        alert('Error: ' + errorMsg);
                        updateStatus('error', errorMsg);
                        setTimeout(() => startScanning(currentDeviceId), 2000);
                    }
                })
                .catch(error => {
                    removeLoadingAnimation();
                    console.error('Error:', error);
                    let errorMsg = 'Terjadi kesalahan saat memproses QR Code: ' + error.message;
                    alert(errorMsg);
                    updateStatus('error', errorMsg);
                    setTimeout(() => startScanning(currentDeviceId), 2000);
                });
        }

        function addLoadingAnimation() {
            const loadingHTML = `
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm flex items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 shadow-xl max-w-sm w-full mx-4">
                    <div class="flex items-center justify-center mb-4">
                        <svg class="animate-spin h-8 w-8 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <p class="text-center text-gray-600">Memproses QR Code...</p>
                </div>
            </div>
        `;

            const loadingElement = document.createElement('div');
            loadingElement.id = 'loading-overlay';
            loadingElement.innerHTML = loadingHTML;
            document.body.appendChild(loadingElement);
        }

        function removeLoadingAnimation() {
            const loadingElement = document.getElementById('loading-overlay');
            if (loadingElement) {
                loadingElement.remove();
            }
        }

        async function initializeCameraSelection() {
            try {
                const devices = await navigator.mediaDevices.enumerateDevices();
                const videoDevices = devices.filter(device => device.kind === 'videoinput');

                if (videoDevices.length > 1) {
                    const selectorContainer = document.createElement('div');
                    selectorContainer.className = 'mt-4 px-4 py-2 bg-white/20 backdrop-blur rounded-xl';

                    const label = document.createElement('label');
                    label.className = 'block text-sm font-medium text-white mb-2';
                    label.textContent = 'Pilih Kamera';
                    selectorContainer.appendChild(label);

                    const select = document.createElement('select');
                    select.className =
                        'w-full px-3 py-2 rounded-lg bg-white/20 backdrop-blur text-white border border-white/20 focus:outline-none focus:ring-2 focus:ring-emerald-500';

                    let defaultSelected = false;

                    videoDevices.forEach((device) => {
                        const option = document.createElement('option');
                        option.value = device.deviceId;

                        // Try to detect camera type from label
                        let deviceLabel = device.label || `Camera ${videoDevices.indexOf(device) + 1}`;
                        if (deviceLabel.toLowerCase().includes('back')) {
                            deviceLabel += ' (Kamera Belakang)';
                            if (!defaultSelected) {
                                option.selected = true;
                                defaultSelected = true;
                                currentDeviceId = device.deviceId;
                            }
                        } else if (deviceLabel.toLowerCase().includes('front')) {
                            deviceLabel += ' (Kamera Depan)';
                        }

                        option.text = deviceLabel;
                        select.appendChild(option);
                    });

                    select.addEventListener('change', (e) => {
                        if (stream) {
                            stopScanning();
                        }
                        startScanning(e.target.value);
                    });

                    selectorContainer.appendChild(select);

                    // Add after video container
                    const videoContainer = document.getElementById('video-container');
                    if (videoContainer.nextSibling) {
                        videoContainer.parentNode.insertBefore(selectorContainer, videoContainer.nextSibling);
                    } else {
                        videoContainer.parentNode.appendChild(selectorContainer);
                    }

                    // Start with selected camera
                    if (currentDeviceId) {
                        startScanning(currentDeviceId);
                    }
                }
            } catch (error) {
                console.error('Error initializing camera selection:', error);
                startScanning();
            }
        }

        // Handle visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && stream) {
                stopScanning();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            stopButton.disabled = true;
            stopButton.classList.add('opacity-50', 'cursor-not-allowed');
            updateStatus('ready', 'Siap untuk memindai');

            // Check browser support
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                handleCameraError(new Error(
                    'Browser Anda tidak mendukung akses kamera. Mohon gunakan browser modern seperti Chrome atau Firefox terbaru.'
                ));
                return;
            }

            // Initialize camera selection
            initializeCameraSelection();
        });
    </script>
@endpush
