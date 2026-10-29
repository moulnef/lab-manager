@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    {{-- The Red Cyber Hero Box with 3D Gaming & Electronics Animation (Auto-Rotating 3D Modes) --}}
    <section id="heroBox" class="hero-cyber-box position-relative mb-4">
        <!-- Corner HUD Brackets -->
        <div class="hud-corner hud-tl"></div>
        <div class="hud-corner hud-tr"></div>
        <div class="hud-corner hud-bl"></div>
        <div class="hud-corner hud-br"></div>

        <!-- Cyber Grid Background & Scanline Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 cyber-grid-bg opacity-50" style="z-index: 1; pointer-events: none;"></div>
        <div class="scanline-overlay"></div>

        <!-- Animated SVG Electronic Circuit Traces in Background -->
        <svg class="position-absolute top-0 start-0 w-100 h-100 opacity-35" style="z-index: 2; pointer-events: none;" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="traceGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                    <stop offset="0%" stop-color="#ef4444" stop-opacity="0" />
                    <stop offset="50%" stop-color="#f87171" stop-opacity="1" />
                    <stop offset="100%" stop-color="#fbbf24" stop-opacity="0" />
                </linearGradient>
            </defs>
            <path d="M 0 60 H 180 L 220 100 H 450" stroke="url(#traceGrad)" stroke-width="2" fill="none" class="circuit-line" />
            <path d="M 0 280 H 140 L 180 240 H 380" stroke="url(#traceGrad)" stroke-width="1.5" fill="none" class="circuit-line" style="animation-delay: -1.2s;" />
            <path d="M 950 40 H 720 L 680 80 H 520" stroke="url(#traceGrad)" stroke-width="2" fill="none" class="circuit-line" style="animation-delay: -2.1s;" />
            <circle cx="220" cy="100" r="3.5" fill="#f87171" />
            <circle cx="180" cy="240" r="3" fill="#fbbf24" />
            <circle cx="680" cy="80" r="3.5" fill="#ef4444" />
        </svg>

        <!-- Three.js 3D WebGL Canvas Layer -->
        <canvas id="hero3dCanvas" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 3; cursor: grab;" title="Di chuột để xoay 3D • Click để kích xung điện"></canvas>

        <!-- Foreground Content Layer -->
        <div class="position-relative p-4 p-md-5" style="z-index: 4; pointer-events: none;">
            <div class="row align-items-center g-4">
                <!-- Left Column: Title, Subtitle, Search Bar -->
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(69, 10, 10, 0.85); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; font-size: 0.82rem;">
                        <span class="text-warning">⚡</span>
                        <span>Mượn nhanh – duyệt trong ngày</span>
                        <span class="ms-1 px-1.5 py-0.5 rounded font-mono-tech" style="font-size: 10px; background: rgba(239, 68, 68, 0.25); color: #fca5a5;">SYS.ONLINE</span>
                    </div>

                    <h1 class="fw-bold mb-3 text-white" style="text-shadow: 0 2px 18px rgba(239, 68, 68, 0.45); font-size: calc(1.5rem + 1.4vw);">
                        Mượn thiết bị phòng Lab<br class="d-none d-md-block">
                        <span style="background: linear-gradient(90deg, #ffffff, #fca5a5, #ef4444); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                            dễ dàng, minh bạch
                        </span>
                    </h1>

                    <p class="text-light opacity-80 mb-4 leading-relaxed" style="max-width: 540px; font-size: 0.96rem;">
                        Tra cứu tình trạng thiết bị theo thời gian thực, gửi yêu cầu mượn trực tuyến và theo dõi lịch sử mượn – trả của bạn trên nền tảng phần cứng trực quan 3D.
                    </p>

                    <form id="heroSearchForm" action="{{ route('devices.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2" style="max-width: 540px; pointer-events: auto;">
                        <div class="position-relative flex-grow-1">
                            <input id="searchInput" type="search" name="keyword" class="form-control form-control-lg hero-input" placeholder="Tìm laptop, máy chiếu, Arduino, Fortinet..." aria-label="Từ khóa">
                            <span class="position-absolute end-0 top-50 translate-middle-y me-3 font-mono-tech px-1.5 py-0.5 rounded" style="font-size: 10px; background: rgba(69, 10, 10, 0.7); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);">CTRL+K</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg fw-semibold px-4 text-nowrap d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-search"></i><span>Tìm kiếm</span>
                        </button>
                    </form>
                </div>

                <!-- Right Column: Telemetry & Glass Stat Cards -->
                <div class="col-lg-5" style="pointer-events: auto;">
                    <!-- HUD Telemetry Label with Auto-Cycling Indicators -->
                    <div class="d-flex justify-content-between align-items-center font-mono-tech mb-2 px-1" style="font-size: 11px; color: rgba(252, 165, 165, 0.85);">
                        <div class="d-flex align-items-center gap-2">
                            <span id="hudModelLabel">CORE: MCU-ARM-CORTEX // 3D PCB CHIP</span>
                            <div id="hudModeDots" class="d-inline-flex gap-1 align-items-center" title="Tự động luân phiên 3 chế độ 3D">
                                <span class="hud-dot" data-idx="0" style="width: 7px; height: 7px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 8px #ef4444; transition: all .3s ease;"></span>
                                <span class="hud-dot" data-idx="1" style="width: 7px; height: 7px; border-radius: 50%; background: rgba(255,255,255,0.25); transition: all .3s ease;"></span>
                                <span class="hud-dot" data-idx="2" style="width: 7px; height: 7px; border-radius: 50%; background: rgba(255,255,255,0.25); transition: all .3s ease;"></span>
                            </div>
                        </div>
                        <span id="hudFpsLabel" class="text-success">60 FPS • WEBGL</span>
                    </div>

                    <div class="row g-3 text-center mb-3">
                        <div class="col-6">
                            <div id="statCardTotal" class="glass-stat-card rounded-4 p-3 h-100 cursor-pointer" title="Click để kích xung nhịp">
                                <div class="display-6 fw-bold text-white font-mono-tech" id="totalCountDisplay">{{ $totalCount }}</div>
                                <div class="small text-light opacity-75 mt-1">Thiết bị trong Lab</div>
                                <div class="mt-2 rounded-pill overflow-hidden" style="height: 4px; background: #450a0a;">
                                    <div class="w-100 h-100 bg-danger"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div id="statCardReady" class="glass-stat-card rounded-4 p-3 h-100 cursor-pointer" title="Click để kích xung nhịp">
                                <div class="display-6 fw-bold text-danger font-mono-tech" id="readyCountDisplay">{{ $availableCount }}</div>
                                <div class="small text-light opacity-75 mt-1">Đang sẵn sàng</div>
                                <div class="mt-2 rounded-pill overflow-hidden" style="height: 4px; background: #450a0a;">
                                    <div class="h-100 bg-success" style="width: {{ $totalCount > 0 ? round(($availableCount / $totalCount) * 100) : 0 }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3-Step Pill Box -->
                    <div class="glass-stat-card rounded-4 p-3 small text-light d-flex flex-wrap align-items-center justify-content-around gap-1">
                        <span class="d-inline-flex align-items-center gap-1.5">
                            <span class="badge rounded-circle bg-danger-subtle text-danger font-mono-tech" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; border: 1px solid rgba(239, 68, 68, 0.4);">1</span>
                            Chọn thiết bị
                        </span>
                        <span class="text-danger opacity-50">&rarr;</span>
                        <span class="d-inline-flex align-items-center gap-1.5">
                            <span class="badge rounded-circle bg-danger-subtle text-danger font-mono-tech" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; border: 1px solid rgba(239, 68, 68, 0.4);">2</span>
                            Gửi yêu cầu
                        </span>
                        <span class="text-danger opacity-50">&rarr;</span>
                        <span class="d-inline-flex align-items-center gap-1.5">
                            <span class="badge rounded-circle bg-danger-subtle text-danger font-mono-tech" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; font-size: 10px; border: 1px solid rgba(239, 68, 68, 0.4);">3</span>
                            Nhận máy khi duyệt
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Danh mục --}}
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <h2 class="h5 fw-bold mb-0">Danh mục thiết bị</h2>
            <a href="{{ route('devices.index') }}" class="small text-decoration-none text-danger fw-semibold">Xem tất cả <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach ($categories as $category)
                <div class="col-6 col-md-4 col-xl-2">
                    <a href="{{ route('devices.index', ['category_id' => $category->id]) }}" class="category-tile">
                        <div class="card h-100 p-3">
                            <span class="category-icon mb-2"><i class="bi {{ $category->icon_class }}"></i></span>
                            <div class="fw-semibold small lh-sm">{{ $category->name }}</div>
                            <div class="text-muted small">{{ $category->devices_count }} thiết bị</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Thiết bị mới --}}
    <section>
        <div class="d-flex justify-content-between align-items-end mb-3">
            <h2 class="h5 fw-bold mb-0">Thiết bị mới cập nhật</h2>
            <a href="{{ route('devices.index') }}" class="small text-decoration-none text-danger fw-semibold">Xem danh sách <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            @foreach ($latestDevices as $device)
                <x-device-card :device="$device" />
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
<script src="{{ asset('vendor/three/three.min.js') }}"></script>
<script>
    if (typeof THREE === 'undefined') {
        document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"><\/script>');
    }
</script>
<script>
(() => {
    const MODES = [
        { id: 'chip', label: 'CORE: MCU-ARM-CORTEX // 3D PCB CHIP' },
        { id: 'core', label: 'CORE: QUANTUM GAMING REACTOR // OVERCLOCKED' },
        { id: 'drone', label: 'CORE: IOT OSCILLOSCOPE & NEON MATRIX 3D' }
    ];
    let currentModeIndex = 0;
    let currentMode = MODES[0].id;
    let cycleTimer = null;
    const CYCLE_INTERVAL_MS = 6500;

    let scene, camera, renderer, mainGroup, particlesMesh, ringGroup, waveMesh;
    let mouseX = 0, mouseY = 0;
    let targetRotationX = 0, targetRotationY = 0;
    let pulseBoost = 0;
    let clock = new THREE.Clock();
    let frameCount = 0, lastFpsUpdate = performance.now();

    function initHero3D() {
        const canvas = document.getElementById('hero3dCanvas');
        const container = document.getElementById('heroBox');
        if (!canvas || !container || typeof THREE === 'undefined') return;

        const width = container.clientWidth;
        const height = container.clientHeight || 460;

        scene = new THREE.Scene();
        camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
        camera.position.set(0, 0.6, 7.5);

        renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
        renderer.setSize(width, height);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));

        // Dramatic lighting (Crimson / Amber / Cyan Rim)
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.55);
        scene.add(ambientLight);

        const redPointLight = new THREE.PointLight(0xef4444, 3.2, 20);
        redPointLight.position.set(3, 3, 4);
        scene.add(redPointLight);

        const amberPointLight = new THREE.PointLight(0xf59e0b, 2.2, 20);
        amberPointLight.position.set(-3, -2, 3);
        scene.add(amberPointLight);

        const cyanRimLight = new THREE.PointLight(0x06b6d4, 1.5, 18);
        cyanRimLight.position.set(4, -3, -2);
        scene.add(cyanRimLight);

        mainGroup = new THREE.Group();
        mainGroup.position.x = width > 992 ? 1.05 : 0;
        scene.add(mainGroup);

        build3DMode(currentMode);
        createFloatingParticles();

        // Parallax hover & 3D tilt
        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            mouseX = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            mouseY = -((e.clientY - rect.top) / rect.height) * 2 + 1;
        });

        container.addEventListener('mouseleave', () => {
            mouseX = 0;
            mouseY = 0;
        });

        canvas.addEventListener('click', triggerPulseShockwave);
        window.addEventListener('resize', onResize);
        animate3D();
    }

    function clearGroup(group) {
        while (group.children.length > 0) {
            const child = group.children[0];
            if (child.geometry) child.geometry.dispose();
            if (child.material) {
                if (Array.isArray(child.material)) child.material.forEach(m => m.dispose());
                else child.material.dispose();
            }
            group.remove(child);
        }
    }

    function build3DMode(mode) {
        if (!mainGroup) return;
        clearGroup(mainGroup);
        ringGroup = new THREE.Group();
        mainGroup.add(ringGroup);

        const hudLabel = document.getElementById('hudModelLabel');

        if (mode === 'chip') {
            if (hudLabel) hudLabel.textContent = 'CORE: MCU-ARM-CORTEX // 3D PCB CHIP';

            // Base PCB Board
            const pcbGeo = new THREE.BoxGeometry(2.6, 0.16, 2.6);
            const pcbMat = new THREE.MeshStandardMaterial({
                color: 0x1a080a,
                metalness: 0.7,
                roughness: 0.25,
                emissive: 0x450a0a,
                emissiveIntensity: 0.4
            });
            mainGroup.add(new THREE.Mesh(pcbGeo, pcbMat));

            // Glowing Wireframe Cage
            const cageGeo = new THREE.BoxGeometry(2.64, 0.2, 2.64);
            const cageMat = new THREE.MeshBasicMaterial({ color: 0xef4444, wireframe: true, transparent: true, opacity: 0.45 });
            mainGroup.add(new THREE.Mesh(cageGeo, cageMat));

            // Central Silicon Die
            const dieGeo = new THREE.BoxGeometry(1.25, 0.28, 1.25);
            const dieMat = new THREE.MeshStandardMaterial({
                color: 0xef4444,
                metalness: 0.85,
                roughness: 0.15,
                emissive: 0xdc2626,
                emissiveIntensity: 0.65
            });
            const dieMesh = new THREE.Mesh(dieGeo, dieMat);
            dieMesh.position.y = 0.15;
            mainGroup.add(dieMesh);

            // IC Pins around all 4 sides
            const pinGeo = new THREE.BoxGeometry(0.08, 0.08, 0.35);
            const pinMat = new THREE.MeshStandardMaterial({
                color: 0xfbbf24,
                emissive: 0xf59e0b,
                emissiveIntensity: 0.4,
                metalness: 0.9,
                roughness: 0.2
            });

            for (let i = -4; i <= 4; i++) {
                const offset = i * 0.24;
                const p1 = new THREE.Mesh(pinGeo, pinMat);
                p1.position.set(offset, 0, 1.42);
                mainGroup.add(p1);

                const p2 = new THREE.Mesh(pinGeo, pinMat);
                p2.position.set(offset, 0, -1.42);
                mainGroup.add(p2);

                const p3 = new THREE.Mesh(pinGeo, pinMat);
                p3.rotation.y = Math.PI / 2;
                p3.position.set(1.42, 0, offset);
                mainGroup.add(p3);

                const p4 = new THREE.Mesh(pinGeo, pinMat);
                p4.rotation.y = Math.PI / 2;
                p4.position.set(-1.42, 0, offset);
                mainGroup.add(p4);
            }

            // Orbiting Holographic Energy Rings
            const torus1 = new THREE.Mesh(
                new THREE.TorusGeometry(2.15, 0.025, 16, 100),
                new THREE.MeshBasicMaterial({ color: 0xf87171, transparent: true, opacity: 0.75 })
            );
            torus1.rotation.x = Math.PI / 2.3;
            ringGroup.add(torus1);

            const torus2 = new THREE.Mesh(
                new THREE.TorusGeometry(2.55, 0.018, 16, 100),
                new THREE.MeshBasicMaterial({ color: 0xfbbf24, transparent: true, opacity: 0.45 })
            );
            torus2.rotation.y = Math.PI / 3;
            ringGroup.add(torus2);

            mainGroup.rotation.set(0.55, -0.45, 0.1);

        } else if (mode === 'core') {
            if (hudLabel) hudLabel.textContent = 'CORE: QUANTUM GAMING REACTOR // OVERCLOCKED';

            // Inner Glowing Icosahedron Core
            const coreGeo = new THREE.IcosahedronGeometry(1.15, 1);
            const coreMat = new THREE.MeshStandardMaterial({
                color: 0xef4444,
                emissive: 0xdc2626,
                emissiveIntensity: 0.8,
                flatShading: true,
                metalness: 0.6,
                roughness: 0.2
            });
            mainGroup.add(new THREE.Mesh(coreGeo, coreMat));

            // Outer Wireframe Shell
            const shellGeo = new THREE.IcosahedronGeometry(1.55, 1);
            const shellMat = new THREE.MeshBasicMaterial({
                color: 0xfbbf24,
                wireframe: true,
                transparent: true,
                opacity: 0.6
            });
            ringGroup.add(new THREE.Mesh(shellGeo, shellMat));

            // 3 Gyroscope Gaming Rings
            const colors = [0xef4444, 0xf97316, 0x38bdf8];
            for (let i = 0; i < 3; i++) {
                const ring = new THREE.Mesh(
                    new THREE.TorusGeometry(1.95 + i * 0.32, 0.03, 16, 90),
                    new THREE.MeshBasicMaterial({ color: colors[i], transparent: true, opacity: 0.75 })
                );
                ring.rotation.set(i * 1.1, i * 0.8, 0);
                ringGroup.add(ring);
            }

        } else if (mode === 'drone') {
            if (hudLabel) hudLabel.textContent = 'CORE: IOT OSCILLOSCOPE & NEON MATRIX 3D';

            // 3D Terrain / Oscilloscope Wave Grid
            const planeGeo = new THREE.PlaneGeometry(5.5, 3.8, 28, 20);
            const planeMat = new THREE.MeshBasicMaterial({
                color: 0xef4444,
                wireframe: true,
                transparent: true,
                opacity: 0.65
            });
            waveMesh = new THREE.Mesh(planeGeo, planeMat);
            waveMesh.rotation.x = -Math.PI / 2.6;
            mainGroup.add(waveMesh);

            // Floating Octahedron Satellites
            for (let i = 0; i < 5; i++) {
                const oct = new THREE.Mesh(
                    new THREE.OctahedronGeometry(0.28, 0),
                    new THREE.MeshStandardMaterial({
                        color: 0xfbbf24,
                        emissive: 0xef4444,
                        emissiveIntensity: 0.7,
                        wireframe: i % 2 === 1
                    })
                );
                oct.position.set((i - 2) * 1.05, 0.7 + (i % 2) * 0.35, 0);
                ringGroup.add(oct);
            }
        }
    }

    function createFloatingParticles() {
        const count = 140;
        const geo = new THREE.BufferGeometry();
        const positions = new Float32Array(count * 3);
        for (let i = 0; i < count * 3; i += 3) {
            positions[i] = (Math.random() - 0.5) * 16;
            positions[i + 1] = (Math.random() - 0.5) * 7;
            positions[i + 2] = (Math.random() - 0.5) * 8;
        }
        geo.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const mat = new THREE.PointsMaterial({
            color: 0xf87171,
            size: 0.07,
            transparent: true,
            opacity: 0.75
        });
        particlesMesh = new THREE.Points(geo, mat);
        scene.add(particlesMesh);
    }

    function triggerPulseShockwave() {
        pulseBoost = 1.0;
        const hero = document.getElementById('heroBox');
        if (hero) {
            hero.style.transform = 'scale(1.008)';
            setTimeout(() => { hero.style.transform = 'none'; }, 200);
        }
    }

    function onResize() {
        const container = document.getElementById('heroBox');
        if (!container || !renderer || !camera) return;
        const w = container.clientWidth;
        const h = container.clientHeight || 460;
        camera.aspect = w / h;
        camera.updateProjectionMatrix();
        renderer.setSize(w, h);
        if (mainGroup) {
            mainGroup.position.x = w > 992 ? 1.05 : 0;
        }
    }

    function animate3D() {
        requestAnimationFrame(animate3D);
        const elapsed = clock.getElapsedTime();

        // FPS meter
        frameCount++;
        const now = performance.now();
        if (now - lastFpsUpdate >= 1000) {
            const fps = Math.round((frameCount * 1000) / (now - lastFpsUpdate));
            const hudFps = document.getElementById('hudFpsLabel');
            if (hudFps) hudFps.textContent = `${fps} FPS • WEBGL`;
            frameCount = 0;
            lastFpsUpdate = now;
        }

        if (pulseBoost > 0.01) {
            pulseBoost *= 0.92;
        } else {
            pulseBoost = 0;
        }

        const speedMultiplier = 1 + pulseBoost * 4.5;

        if (mainGroup) {
            targetRotationY = mouseX * 0.65 + elapsed * 0.35 * speedMultiplier;
            targetRotationX = -mouseY * 0.4 + 0.35;

            mainGroup.rotation.y += (targetRotationY - mainGroup.rotation.y) * 0.08;
            mainGroup.rotation.x += (targetRotationX - mainGroup.rotation.x) * 0.08;
            mainGroup.position.y = Math.sin(elapsed * 2.2) * 0.14;

            const s = 1 + pulseBoost * 0.22;
            mainGroup.scale.set(s, s, s);
        }

        if (ringGroup) {
            ringGroup.rotation.z = elapsed * 0.8 * speedMultiplier;
            ringGroup.rotation.x = Math.sin(elapsed * 0.7) * 0.5;
        }

        if (currentMode === 'drone' && waveMesh) {
            const pos = waveMesh.geometry.attributes.position;
            for (let i = 0; i < pos.count; i++) {
                const x = pos.getX(i);
                const y = pos.getY(i);
                const z = Math.sin(x * 1.5 + elapsed * 3.0) * 0.28 + Math.cos(y * 1.5 + elapsed * 2.2) * 0.22;
                pos.setZ(i, z * (1 + pulseBoost));
            }
            pos.needsUpdate = true;
        }

        if (particlesMesh) {
            particlesMesh.rotation.y = -elapsed * 0.06;
            particlesMesh.position.y = Math.sin(elapsed * 0.8) * 0.15;
        }

        renderer.render(scene, camera);
    }

    function updateModeDots(idx) {
        document.querySelectorAll('#hudModeDots .hud-dot').forEach((dot, i) => {
            if (i === idx) {
                dot.style.background = '#ef4444';
                dot.style.boxShadow = '0 0 10px #ef4444';
                dot.style.transform = 'scale(1.35)';
            } else {
                dot.style.background = 'rgba(255,255,255,0.25)';
                dot.style.boxShadow = 'none';
                dot.style.transform = 'scale(1)';
            }
        });
    }

    function switchToMode(index) {
        currentModeIndex = (index + MODES.length) % MODES.length;
        currentMode = MODES[currentModeIndex].id;
        build3DMode(currentMode);
        triggerPulseShockwave();
        updateModeDots(currentModeIndex);
    }

    function startAutoCycle() {
        stopAutoCycle();
        cycleTimer = setInterval(() => {
            switchToMode(currentModeIndex + 1);
        }, CYCLE_INTERVAL_MS);
    }

    function stopAutoCycle() {
        if (cycleTimer) {
            clearInterval(cycleTimer);
            cycleTimer = null;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initHero3D();
        updateModeDots(0);
        startAutoCycle();

        // Click vào các chấm nhỏ để nhảy trực tiếp tới chế độ tương ứng
        document.querySelectorAll('#hudModeDots .hud-dot').forEach((dot) => {
            dot.style.cursor = 'pointer';
            dot.addEventListener('click', (e) => {
                const idx = parseInt(e.currentTarget.getAttribute('data-idx') || '0', 10);
                switchToMode(idx);
                startAutoCycle();
            });
        });

        // Click thẻ thống kê hoặc canvas để kích xung Overclock và reset chu kỳ
        document.getElementById('statCardTotal')?.addEventListener('click', () => {
            triggerPulseShockwave();
            startAutoCycle();
        });
        document.getElementById('statCardReady')?.addEventListener('click', () => {
            triggerPulseShockwave();
            startAutoCycle();
        });

        // Ctrl+K to focus search input
        window.addEventListener('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                const search = document.getElementById('searchInput');
                if (search) {
                    search.focus();
                    search.select();
                }
            }
        });
    });
})();
</script>
@endpush
