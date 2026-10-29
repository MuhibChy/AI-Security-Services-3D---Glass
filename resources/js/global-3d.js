/**
 * global-3d.js — 3D IT Support Command Center
 *
 * New Art Direction: Solid-Color 3D Technology
 * Controlled Palette:
 * - Deep navy: #071A38
 * - Royal blue: #287BFF
 * - Electric cyan: #36D9FF
 * - Bright white: #F7FAFF
 * - Graphite: #0B2A55
 * - Cool silver: #C9D4E5
 * - Optional violet accent: #805CFF
 *
 * Architecture:
 * - A sculpted blue digital shield representing protection.
 * - A refined 3D workstation representing IT support & engineering.
 * - A floating diagnostic telemetry interface with system-health indicators.
 * - A compact network topology with connected nodes and traveling data packets.
 * - A dimensional cloud & server blade stack representing infrastructure support.
 * - Fine orbital rings and data paths connecting the elements.
 * - Art-directed to the right on desktop so text on the left stays completely unobstructed.
 */

import * as THREE from 'three';

const STATE_BIAS = {
    hero: 0.0,
    stats: 0.12,
    services: 0.26,
    features: 0.4,
    process: 0.54,
    testimonials: 0.68,
    portfolio: 0.78,
    cta: 0.9,
    contact: 0.95,
    footer: 1.0,
};

class Global3DScene {
    constructor(canvasId = 'global-3d-canvas') {
        this.canvasId = canvasId;
        this.canvas = null;
        this.canvasFg = null;
        this.rendererFg = null;
        this.sceneFg = null;
        this.renderer = null;
        this.scene = null;
        this.camera = null;
        this.frameId = null;
        this.clock = null;
        this.objects = {};
        this.nodes = [];
        this.packets = [];

        this.mouse = { x: 0, y: 0, tx: 0, ty: 0 };
        this.scroll = { progress: 0, target: 0, sectionBias: 0, sectionTarget: 0 };
        this.ticking = false;
        this.isDestroyed = false;
        this.isVisible = true;

        this.reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this.lowPower = (navigator.hardwareConcurrency || 8) <= 2;
        this.smallScreen = Math.min(window.innerWidth, window.innerHeight) < 500;
        this.subtle = document.body ? document.body.getAttribute('data-3d') === 'subtle' : false;

        this.gloveCtl = { mode: 'moving', x: null, y: null, positioning: false, dragging: false, tx: null, ty: null };
        this.readGlovePrefs();

        this.init();
    }

    init() {
        this.canvas = document.getElementById(this.canvasId);
        if (!this.canvas) return;
        if (this.canvas.dataset.init === '1' || window._global3D) return;
        this.canvas.dataset.init = '1';
        this.canvasFg = document.getElementById('global-3d-canvas-fg');

        if (!this.webglAvailable()) { this.fallback(); return; }

        this.setupRenderer();
        this.setupScene();
        this.setupCamera();
        this.setupLights();
        this.createAmbientDust();
        this.createCommandCenter();
        this.createShield();
        this.createGridFloor();
        this.bindEvents();
        this.updateScrollTarget(true);
        this.updateHudAnchor();

        this.t0 = performance.now();
        this.clock = new THREE.Clock();

        if (this.reduced || this.lowPower) {
            this.applyState(0, true);
            this.renderer.render(this.scene, this.camera);
            if (this.rendererFg && this.sceneFg) this.rendererFg.render(this.sceneFg, this.camera);
            return;
        }

        window._global3D = this;
        this.animate();
    }

    webglAvailable() {
        try {
            const c = document.createElement('canvas');
            return !!(window.WebGLRenderingContext && (c.getContext('webgl') || c.getContext('experimental-webgl')));
        } catch (e) { return false; }
    }

    fallback() {
        document.body.classList.add('global-3d-static');
    }

    setupRenderer() {
        const dpr = this.smallScreen ? 1 : Math.min(window.devicePixelRatio || 1, 1.5);
        this.renderer = new THREE.WebGLRenderer({
            canvas: this.canvas,
            alpha: true,
            antialias: !this.smallScreen,
            powerPreference: 'high-performance'
        });
        this.renderer.setPixelRatio(dpr);
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.renderer.setClearColor(0x000000, 0);
        this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
        this.renderer.toneMappingExposure = 1.3;

        if (this.canvasFg) {
            this.rendererFg = new THREE.WebGLRenderer({
                canvas: this.canvasFg,
                alpha: true,
                antialias: !this.smallScreen,
                powerPreference: 'high-performance'
            });
            this.rendererFg.setPixelRatio(dpr);
            this.rendererFg.setSize(window.innerWidth, window.innerHeight);
            this.rendererFg.setClearColor(0x000000, 0);
            this.rendererFg.toneMapping = THREE.ACESFilmicToneMapping;
            this.rendererFg.toneMappingExposure = 1.3;
        }
    }

    setupScene() {
        this.scene = new THREE.Scene();
        // Atmospheric deep navy fog that preserves rich solid colors
        this.scene.fog = new THREE.FogExp2(0x071A38, 0.035);
        if (this.canvasFg) this.sceneFg = new THREE.Scene();
    }

    setupCamera() {
        this.camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
        this.camera.position.set(0, 0, 9);
    }

    setupLights() {
        // Deep navy ambient foundation
        const ambient = new THREE.AmbientLight(0x071A38, 1.4);
        this.scene.add(ambient);

        // Key Studio Light (Crisp White / Silver)
        const key = new THREE.DirectionalLight(0xF7FAFF, 4.0);
        key.position.set(-6, 7, 8);
        this.scene.add(key);
        this.objects.key = key;

        // Rim Light (Electric Cyan #36D9FF) highlighting silhouettes & bevels
        const rim = new THREE.PointLight(0x36D9FF, 4.8, 30);
        rim.position.set(6, 3, -2);
        this.scene.add(rim);
        this.objects.rim = rim;

        // Fill Light (Royal Blue #287BFF) providing rich shadows
        const fill = new THREE.DirectionalLight(0x287BFF, 2.6);
        fill.position.set(5, -4, 4);
        this.scene.add(fill);
        this.objects.fill = fill;

        // Subtle Violet Accent (#805CFF)
        const crown = new THREE.PointLight(0x805CFF, 2.0, 25);
        crown.position.set(0, 5, -4);
        this.scene.add(crown);
        this.objects.crown = crown;

        // Foreground scene lights if separate layer is active
        if (this.sceneFg) {
            this.sceneFg.add(new THREE.AmbientLight(0x071A38, 1.4));
            const fgKey = new THREE.DirectionalLight(0xF7FAFF, 4.0);
            fgKey.position.set(-6, 7, 8);
            this.sceneFg.add(fgKey);
            const fgRim = new THREE.PointLight(0x36D9FF, 4.5, 24);
            fgRim.position.set(5, -2, 3);
            this.sceneFg.add(fgRim);
        }
    }

    makeGlowTexture(inner, outer) {
        const c = document.createElement('canvas');
        c.width = 64; c.height = 64;
        const ctx = c.getContext('2d');
        const g = ctx.createRadialGradient(32, 32, 0, 32, 32, 32);
        g.addColorStop(0, inner);
        g.addColorStop(0.45, outer);
        g.addColorStop(1, 'rgba(0,0,0,0)');
        ctx.fillStyle = g;
        ctx.fillRect(0, 0, 64, 64);
        const t = new THREE.CanvasTexture(c);
        t.needsUpdate = true;
        return t;
    }

    createAmbientDust() {
        const count = this.smallScreen ? 180 : 420;
        const pos = new Float32Array(count * 3);
        const col = new Float32Array(count * 3);
        const palette = [
            new THREE.Color(0xF7FAFF), // Bright white
            new THREE.Color(0x36D9FF), // Electric cyan
            new THREE.Color(0x287BFF), // Royal blue
            new THREE.Color(0xC9D4E5), // Cool silver
        ];
        for (let i = 0; i < count; i++) {
            const r = 18 + Math.random() * 55;
            const th = Math.random() * Math.PI * 2;
            const ph = Math.acos(Math.random() * 2 - 1);
            pos[i * 3] = r * Math.sin(ph) * Math.cos(th);
            pos[i * 3 + 1] = (r * Math.sin(ph) * Math.sin(th)) * 0.65;
            pos[i * 3 + 2] = r * Math.cos(ph);
            const cc = palette[Math.floor(Math.random() * palette.length)];
            const dim = 0.4 + Math.random() * 0.6;
            col[i * 3] = cc.r * dim;
            col[i * 3 + 1] = cc.g * dim;
            col[i * 3 + 2] = cc.b * dim;
        }
        const geo = new THREE.BufferGeometry();
        geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
        geo.setAttribute('color', new THREE.BufferAttribute(col, 3));
        const mat = new THREE.PointsMaterial({
            size: 0.5,
            map: this.makeGlowTexture('rgba(247,250,255,1)', 'rgba(39,217,242,0.3)'),
            vertexColors: true,
            transparent: true,
            opacity: 0.65,
            fog: false,
            blending: THREE.AdditiveBlending,
            depthWrite: false,
        });
        const dust = new THREE.Points(geo, mat);
        this.scene.add(dust);
        this.objects.stars = dust;
    }

    // ─── 3D IT SUPPORT COMMAND CENTER (MAIN HERO COMPOSITION) ───────────
    createCommandCenter() {
        const group = new THREE.Group();

        // 1. Refined 3D Computer Workstation (IT Support)
        const workstation = new THREE.Group();

        // Curved Ultrawide Display Chassis (Graphite #0B2A55)
        const monitorMat = new THREE.MeshStandardMaterial({
            color: 0x0B2A55,
            metalness: 0.7,
            roughness: 0.3,
        });
        const monitorFrame = new THREE.Mesh(
            new THREE.BoxGeometry(2.4, 1.35, 0.12),
            monitorMat
        );
        workstation.add(monitorFrame);

        // Screen Diagnostic Telemetry Display
        const screenMat = new THREE.MeshStandardMaterial({
            color: 0x071A38,
            emissive: 0x122748,
            emissiveIntensity: 0.8,
            roughness: 0.2,
            metalness: 0.8,
        });
        const screen = new THREE.Mesh(
            new THREE.BoxGeometry(2.3, 1.25, 0.02),
            screenMat
        );
        screen.position.z = 0.06;
        workstation.add(screen);

        // Stand (Cool Silver Metallic Aluminum #C9D4E5)
        const standMat = new THREE.MeshStandardMaterial({
            color: 0xC9D4E5,
            metalness: 0.9,
            roughness: 0.15,
        });
        const standStem = new THREE.Mesh(
            new THREE.CylinderGeometry(0.06, 0.06, 0.9, 16),
            standMat
        );
        standStem.position.set(0, -0.9, -0.1);
        workstation.add(standStem);

        const standBase = new THREE.Mesh(
            new THREE.CylinderGeometry(0.5, 0.55, 0.05, 32),
            standMat
        );
        standBase.position.set(0, -1.35, 0);
        workstation.add(standBase);

        // Keyboard & Precision Input Base in front
        const keyboard = new THREE.Mesh(
            new THREE.BoxGeometry(1.6, 0.04, 0.55),
            new THREE.MeshStandardMaterial({ color: 0x0B2A55, metalness: 0.6, roughness: 0.3 })
        );
        keyboard.position.set(0, -1.33, 0.45);
        workstation.add(keyboard);

        workstation.position.set(0, 0, 0);
        group.add(workstation);
        this.objects.workstation = workstation;

        // 2. Dimensional Cloud & Server Infrastructure Module (Left of Workstation)
        const infraGroup = new THREE.Group();

        // 3D Server Blades Stack (Graphite + Cyan LEDs + Silver Drive Bays)
        const bladeMat = new THREE.MeshStandardMaterial({
            color: 0x0B2A55,
            metalness: 0.7,
            roughness: 0.25,
        });
        const ledCyanMat = new THREE.MeshBasicMaterial({
            color: 0x36D9FF,
            blending: THREE.AdditiveBlending
        });

        for (let i = 0; i < 3; i++) {
            const blade = new THREE.Mesh(
                new THREE.BoxGeometry(0.9, 0.24, 0.8),
                bladeMat
            );
            blade.position.set(0, i * 0.3, 0);
            infraGroup.add(blade);

            // Cyan activity LED strip on front
            const led = new THREE.Mesh(
                new THREE.BoxGeometry(0.8, 0.02, 0.02),
                ledCyanMat
            );
            led.position.set(0, i * 0.3, 0.41);
            infraGroup.add(led);
        }

        // Dimensional Solid White-and-Blue Cloud hovering above Server Stack
        const cloudGroup = new THREE.Group();
        const cloudMat = new THREE.MeshStandardMaterial({
            color: 0xF7FAFF,
            metalness: 0.15,
            roughness: 0.2,
        });
        const cloudSpheres = [
            [-0.35, 0, 0, 0.38],
            [0, 0.15, 0, 0.48],
            [0.35, 0, 0, 0.38],
            [-0.18, -0.05, 0.12, 0.32],
            [0.18, -0.05, 0.12, 0.32],
        ];
        cloudSpheres.forEach(([cx, cy, cz, cr]) => {
            const cs = new THREE.Mesh(new THREE.SphereGeometry(cr, 24, 24), cloudMat);
            cs.position.set(cx, cy, cz);
            cloudGroup.add(cs);
        });
        cloudGroup.position.set(0, 1.25, 0);
        infraGroup.add(cloudGroup);
        this.objects.cloudObj = cloudGroup;

        // Uplink data connection beam from server to cloud
        const uplinkBeam = new THREE.Mesh(
            new THREE.CylinderGeometry(0.015, 0.015, 0.6, 12),
            new THREE.MeshBasicMaterial({ color: 0x36D9FF, transparent: true, opacity: 0.65, blending: THREE.AdditiveBlending })
        );
        uplinkBeam.position.set(0, 0.85, 0);
        infraGroup.add(uplinkBeam);

        infraGroup.position.set(-1.8, -0.3, 0.3);
        infraGroup.scale.set(0.85, 0.85, 0.85);
        group.add(infraGroup);
        this.objects.infra = infraGroup;

        // 3. Compact Network Topology (Connected Nodes & Data Paths)
        const networkGroup = new THREE.Group();
        const nodeMat = new THREE.MeshStandardMaterial({
            color: 0x287BFF,
            emissive: 0x36D9FF,
            emissiveIntensity: 0.5,
            metalness: 0.8,
            roughness: 0.2,
        });
        const nodePositions = [
            [-2.4, 1.4, -0.5],
            [-1.5, 2.1, 0.2],
            [1.6, 1.8, -0.3],
            [2.5, 0.8, 0.4],
            [2.1, -0.8, -0.2],
        ];
        this.nodes = [];
        nodePositions.forEach((pos, idx) => {
            const nodeMesh = new THREE.Mesh(
                new THREE.SphereGeometry(0.12, 20, 20),
                nodeMat.clone()
            );
            nodeMesh.position.set(...pos);
            nodeMesh.userData = { phase: idx * 1.2 };
            networkGroup.add(nodeMesh);
            this.nodes.push(nodeMesh);
        });

        // Glowing data path spline curves
        const pathMat = new THREE.LineBasicMaterial({
            color: 0x36D9FF,
            transparent: true,
            opacity: 0.5,
            blending: THREE.AdditiveBlending
        });
        for (let i = 0; i < nodePositions.length - 1; i++) {
            const p1 = new THREE.Vector3(...nodePositions[i]);
            const p2 = new THREE.Vector3(...nodePositions[i + 1]);
            const pathGeo = new THREE.BufferGeometry().setFromPoints([p1, p2]);
            const pathLine = new THREE.Line(pathGeo, pathMat);
            networkGroup.add(pathLine);
        }

        // Data packets traveling the links
        const packetGeo = new THREE.SphereGeometry(0.045, 12, 12);
        const packetMat = new THREE.MeshBasicMaterial({
            color: 0xF7FAFF,
            blending: THREE.AdditiveBlending
        });
        this.packets = [];
        for (let i = 0; i < 4; i++) {
            const pMesh = new THREE.Mesh(packetGeo, packetMat);
            networkGroup.add(pMesh);
            this.packets.push({
                mesh: pMesh,
                from: new THREE.Vector3(...nodePositions[i]),
                to: new THREE.Vector3(...nodePositions[i + 1]),
                t: i * 0.25,
                speed: 0.35 + i * 0.1
            });
        }
        group.add(networkGroup);
        this.objects.networkGroup = networkGroup;

        // 4. Fine Orbital Rings (Electric Cyan & Violet Accents)
        const ringGeo1 = new THREE.RingGeometry(2.8, 2.84, 96);
        ringGeo1.rotateX(Math.PI / 2.3);
        const ring1 = new THREE.Mesh(
            ringGeo1,
            new THREE.MeshBasicMaterial({
                color: 0x36D9FF,
                side: THREE.DoubleSide,
                transparent: true,
                opacity: 0.45,
                blending: THREE.AdditiveBlending
            })
        );
        group.add(ring1);
        this.objects.ring = ring1;

        const ringGeo2 = new THREE.RingGeometry(3.4, 3.43, 96);
        ringGeo2.rotateX(Math.PI / 1.7);
        ringGeo2.rotateZ(Math.PI / 4);
        const ring2 = new THREE.Mesh(
            ringGeo2,
            new THREE.MeshBasicMaterial({
                color: 0x805CFF,
                side: THREE.DoubleSide,
                transparent: true,
                opacity: 0.3,
                blending: THREE.AdditiveBlending
            })
        );
        group.add(ring2);
        this.objects.ring2 = ring2;

        // 5. Floating Diagnostic Health Interface (Right side of Workstation)
        const diagGroup = new THREE.Group();
        const diagGlassMat = new THREE.MeshStandardMaterial({
            color: 0x0B2A55,
            metalness: 0.2,
            roughness: 0.1,
            transparent: true,
            opacity: 0.75,
            side: THREE.DoubleSide
        });
        const diagPanel = new THREE.Mesh(
            new THREE.PlaneGeometry(1.4, 0.95),
            diagGlassMat
        );
        diagGroup.add(diagPanel);

        // Panel Border Highlight
        const borderGeo = new THREE.EdgesGeometry(new THREE.PlaneGeometry(1.4, 0.95));
        const borderMesh = new THREE.LineSegments(
            borderGeo,
            new THREE.LineBasicMaterial({ color: 0x36D9FF, transparent: true, opacity: 0.75 })
        );
        diagGroup.add(borderMesh);

        // Diagnostic telemetry bars on the panel
        for (let b = 0; b < 6; b++) {
            const bar = new THREE.Mesh(
                new THREE.PlaneGeometry(0.08, 0.2 + b * 0.08),
                new THREE.MeshBasicMaterial({
                    color: b >= 4 ? 0x36D9FF : 0x287BFF,
                    side: THREE.DoubleSide
                })
            );
            bar.position.set(-0.45 + b * 0.18, -0.15 + (0.2 + b * 0.08) / 2, 0.02);
            diagGroup.add(bar);
        }

        diagGroup.position.set(1.9, 0.6, 0.4);
        diagGroup.rotation.y = -0.35;
        group.add(diagGroup);
        this.objects.diagPanel = diagGroup;

        this.scene.add(group);
        this.objects.planetGroup = group; // Keep key reference for layout/waypoints compatibility
        this.layoutPlanet();
    }

    // ─── SCULPTED BLUE DIGITAL SHIELD ───────────────────────────────────
    createShield() {
        const parent = this.sceneFg || this.objects.planetGroup;
        if (!parent) return;

        const shieldGroup = new THREE.Group();

        // 3D Extruded Beveled Shield Geometry
        const shape = new THREE.Shape();
        shape.moveTo(0, 0.85);
        shape.lineTo(0.65, 0.55);
        shape.lineTo(0.65, -0.25);
        shape.lineTo(0, -0.9);
        shape.lineTo(-0.65, -0.25);
        shape.lineTo(-0.65, 0.55);
        shape.closePath();

        const extrudeSettings = {
            depth: 0.12,
            bevelEnabled: true,
            bevelSegments: 4,
            steps: 2,
            bevelSize: 0.06,
            bevelThickness: 0.06,
        };

        // Royal Blue Solid Metallic Finish (#287BFF)
        const shieldMat = new THREE.MeshStandardMaterial({
            color: 0x287BFF,
            metalness: 0.65,
            roughness: 0.25,
            emissive: 0x0F225C,
            emissiveIntensity: 0.4,
        });
        const shieldMesh = new THREE.Mesh(
            new THREE.ExtrudeGeometry(shape, extrudeSettings),
            shieldMat
        );
        shieldGroup.add(shieldMesh);
        this.objects.crestMesh = shieldMesh;

        // Inner Electric Cyan Protection Core (#36D9FF)
        const coreShape = new THREE.Shape();
        coreShape.moveTo(0, 0.65);
        coreShape.lineTo(0.48, 0.42);
        coreShape.lineTo(0.48, -0.18);
        coreShape.lineTo(0, -0.68);
        coreShape.lineTo(-0.48, -0.18);
        coreShape.lineTo(-0.48, 0.42);
        coreShape.closePath();

        const coreMesh = new THREE.Mesh(
            new THREE.ExtrudeGeometry(coreShape, { depth: 0.04, bevelEnabled: true, bevelSize: 0.02, bevelThickness: 0.02 }),
            new THREE.MeshStandardMaterial({
                color: 0x36D9FF,
                emissive: 0x36D9FF,
                emissiveIntensity: 0.65,
                metalness: 0.8,
                roughness: 0.15,
            })
        );
        coreMesh.position.z = 0.12;
        shieldGroup.add(coreMesh);
        this.objects.shieldCore = coreMesh;

        // Dimensional Lock Cylinder in Cool Silver (#C9D4E5)
        const lockBody = new THREE.Mesh(
            new THREE.BoxGeometry(0.24, 0.22, 0.08),
            new THREE.MeshStandardMaterial({ color: 0xF7FAFF, metalness: 0.85, roughness: 0.2 })
        );
        lockBody.position.set(0, -0.05, 0.2);
        shieldGroup.add(lockBody);

        const lockShackle = new THREE.Mesh(
            new THREE.TorusGeometry(0.08, 0.025, 12, 24, Math.PI),
            new THREE.MeshStandardMaterial({ color: 0xC9D4E5, metalness: 0.9, roughness: 0.15 })
        );
        lockShackle.position.set(0, 0.06, 0.2);
        shieldGroup.add(lockShackle);

        // Slender Protection Orbit Rings
        this.dataRings = [];
        const radii = [1.25, 1.55];
        radii.forEach((r, idx) => {
            const ring = new THREE.Mesh(
                new THREE.TorusGeometry(r, 0.012, 8, 64),
                new THREE.MeshBasicMaterial({
                    color: idx === 0 ? 0x36D9FF : 0x805CFF,
                    transparent: true,
                    opacity: 0.6 - idx * 0.15,
                    blending: THREE.AdditiveBlending
                })
            );
            ring.rotation.x = idx * 0.5 + Math.PI / 4;
            ring.rotation.y = idx * 0.4;
            shieldGroup.add(ring);
            this.dataRings.push(ring);
        });

        parent.add(shieldGroup);
        this.objects.shieldGroup = shieldGroup;
        this.layoutShield();
    }

    layoutPlanet() {
        const g = this.objects.planetGroup;
        if (!g) return;
        // Desktop: Positioned strictly on the right (x: 3.2) so text on the left stays clean
        if (window.innerWidth < 768) {
            g.position.set(0, 0.35, -2.4);
            g.scale.set(0.58, 0.58, 0.58);
        } else if (window.innerWidth < 1200) {
            g.position.set(2.4, 0.1, -1.0);
            g.scale.set(0.8, 0.8, 0.8);
        } else {
            g.position.set(3.2, 0.0, 0.0);
            g.scale.set(1.0, 1.0, 1.0);
        }
        this.basePlanetPos = g.position.clone();
    }

    layoutShield() {
        const s = this.objects.shieldGroup;
        if (!s) return;
        if (this.sceneFg) {
            if (window.innerWidth < 768) {
                s.position.set(0, 0.1, 0.2);
                s.scale.set(0.65, 0.65, 0.65);
            } else {
                s.position.set(3.2, 0.0, 0.5);
                s.scale.set(0.9, 0.9, 0.9);
            }
        } else {
            // Nested inside Command Center
            s.position.set(0, 0.1, 1.4);
            s.scale.set(0.75, 0.75, 0.75);
        }
        this.baseShieldPos = s.position.clone();
    }

    // Grid floor perspective lattice
    gridTexture() {
        const c = document.createElement('canvas');
        c.width = 128; c.height = 128;
        const ctx = c.getContext('2d');
        ctx.fillStyle = 'rgba(0,0,0,0)';
        ctx.fillRect(0, 0, 128, 128);
        ctx.strokeStyle = 'rgba(39, 217, 242, 0.3)';
        ctx.lineWidth = 1.5;
        ctx.strokeRect(1, 1, 126, 126);
        const t = new THREE.CanvasTexture(c);
        t.wrapS = THREE.RepeatWrapping;
        t.wrapT = THREE.RepeatWrapping;
        t.repeat.set(24, 24);
        return t;
    }

    createGridFloor() {
        const floor = new THREE.Mesh(
            new THREE.PlaneGeometry(80, 50),
            new THREE.MeshBasicMaterial({
                map: this.gridTexture(),
                transparent: true,
                opacity: 0.14,
                blending: THREE.AdditiveBlending,
                depthWrite: false
            })
        );
        floor.rotation.x = -Math.PI / 2;
        floor.position.y = -3.2;
        this.scene.add(floor);
        this.objects.gridFloor = floor;
    }

    updateHudAnchor() {
        try {
            const box = document.querySelector('#global-hud .global-hud-box');
            if (!box) { this.hudAnchor = null; return; }
            const r = box.getBoundingClientRect();
            if (r.width < 2 || r.height < 2) { this.hudAnchor = null; return; }
            this.hudAnchor = {
                x: (r.left + r.width / 2) / Math.max(1, window.innerWidth),
                y: (r.top + r.height / 2) / Math.max(1, window.innerHeight),
            };
        } catch (e) { this.hudAnchor = null; }
    }

    hudWorldTarget(nx, ny) {
        const g = this.objects.planetGroup;
        if (!g || !this.camera) return null;
        const depth = Math.max(4, this.camera.position.z - (this.basePlanetPos ? this.basePlanetPos.z : 0));
        const halfH = Math.tan(THREE.MathUtils.degToRad(this.camera.fov * 0.5)) * depth;
        const halfW = halfH * this.camera.aspect;
        const lx = (nx - 0.5) * 2 * halfW;
        const ly = (0.5 - ny) * 2 * halfH;
        const look = new THREE.Vector3(g.position.x * 0.35, 0, 0);
        const fwd = look.clone().sub(this.camera.position).normalize();
        const right = new THREE.Vector3().crossVectors(fwd, new THREE.Vector3(0, 1, 0)).normalize();
        const up = new THREE.Vector3().crossVectors(right, fwd).normalize();
        return this.camera.position.clone()
            .addScaledVector(fwd, depth)
            .addScaledVector(right, lx)
            .addScaledVector(up, ly);
    }

    static gloveClamp(v) {
        const n = Number(v);
        if (!Number.isFinite(n)) return null;
        return Math.min(0.92, Math.max(0.08, n));
    }

    readGlovePrefs() {
        try {
            const body = document.body;
            const mode = body ? body.getAttribute('data-glove-mode') : null;
            const bounds = this.gloveSafeBounds();
            let x = Global3DScene.gloveClamp(body ? body.getAttribute('data-glove-x') : null);
            let y = Global3DScene.gloveClamp(body ? body.getAttribute('data-glove-y') : null);
            // Saved positions predate safe bounds — nudge them into the
            // safe lane instead of parking the glove under navigation.
            if (x !== null) x = Math.min(0.92, Math.max(bounds.xMin, x));
            if (y !== null) y = Math.min(0.92, Math.max(bounds.yMin, y));
            if ((mode === 'fixed' || mode === 'moving') && (mode === 'moving' || (x !== null && y !== null))) {
                this.gloveCtl.mode = mode;
                this.gloveCtl.x = x;
                this.gloveCtl.y = y;
            }
        } catch (e) { /* fall back to moving */ }
    }

    // Content-aware safe bounds for a fixed glove: keep it clear of the
    // sidebar rail (desktop) and the top header bar. The canvas always
    // paints behind content (z-5 < z-10), so this governs visual crowding,
    // not occlusion — content can never be covered regardless.
    gloveSafeBounds() {
        try {
            const w = Math.max(1, window.innerWidth);
            const h = Math.max(1, window.innerHeight);
            let xMin = 0.08;
            let yMin = 0.08;
            if (w >= 1024) {
                const aside = document.querySelector('aside');
                if (aside) {
                    const r = aside.getBoundingClientRect();
                    if (r.width > 0 && r.width < w * 0.5) xMin = Math.min(0.9, (r.width + 24) / w);
                }
            }
            const header = document.querySelector('header');
            if (header) {
                const r = header.getBoundingClientRect();
                if (r.height > 0 && r.height < h * 0.5) yMin = Math.min(0.9, (r.height + 16) / h);
            }
            return { xMin, yMin };
        } catch (e) { return { xMin: 0.08, yMin: 0.08 }; }
    }

    setGloveMode(mode, x = null, y = null) {
        if (mode !== 'moving' && mode !== 'fixed') return false;
        const cx = x === null ? this.gloveCtl.x : Global3DScene.gloveClamp(x);
        const cy = y === null ? this.gloveCtl.y : Global3DScene.gloveClamp(y);
        if (mode === 'fixed' && (cx === null || cy === null)) return false;
        this.gloveCtl.mode = mode;
        this.gloveCtl.x = cx;
        this.gloveCtl.y = cy;
        this.gloveCtl.positioning = false;
        this.gloveCtl.dragging = false;
        if ((this.reduced || this.lowPower) && this.renderer) this.renderOnce();
        return true;
    }

    beginPositioning() {
        if (!this.objects.shieldGroup) return false;
        this.gloveCtl.positioning = true;
        this.gloveCtl.dragging = false;
        // Start preview from the current on-screen glove location.
        const cur = this.gloveScreenPos();
        this.gloveCtl.tx = cur ? cur.x : (this.gloveCtl.x ?? 0.8);
        this.gloveCtl.ty = cur ? cur.y : (this.gloveCtl.y ?? 0.5);
        return true;
    }

    cancelPositioning() {
        this.gloveCtl.positioning = false;
        this.gloveCtl.dragging = false;
        if ((this.reduced || this.lowPower) && this.renderer) this.renderOnce();
    }

    positioningTarget() {
        if (this.gloveCtl.tx === null || this.gloveCtl.ty === null) return null;
        const bounds = this.gloveSafeBounds();
        return {
            x: Math.min(0.92, Math.max(bounds.xMin, Global3DScene.gloveClamp(this.gloveCtl.tx))),
            y: Math.min(0.92, Math.max(bounds.yMin, Global3DScene.gloveClamp(this.gloveCtl.ty))),
        };
    }

    // Current glove location expressed in normalized viewport coords.
    gloveScreenPos() {
        const s = this.objects.shieldGroup;
        if (!s || !this.camera) return null;
        const v = s.position.clone().project(this.camera);
        return {
            x: Global3DScene.gloveClamp(v.x * 0.5 + 0.5),
            y: Global3DScene.gloveClamp(-v.y * 0.5 + 0.5),
        };
    }

    // Viewport-anchored world target for normalized coords (camera-relative,
    // so scroll and camera drift never move a fixed glove).
    gloveWorldTarget(nx, ny) {
        const s = this.objects.shieldGroup;
        if (!s || !this.camera) return null;
        const depth = Math.max(4, this.camera.position.z - (this.baseShieldPos ? this.baseShieldPos.z : 0.5));
        const halfH = Math.tan(THREE.MathUtils.degToRad(this.camera.fov * 0.5)) * depth;
        const halfW = halfH * this.camera.aspect;
        const lx = (nx - 0.5) * 2 * halfW * 0.78;
        const ly = (0.5 - ny) * 2 * halfH * 0.72;
        const g = this.objects.planetGroup;
        const look = new THREE.Vector3(g ? g.position.x * 0.35 : 0, 0, 0);
        const fwd = look.clone().sub(this.camera.position).normalize();
        const right = new THREE.Vector3().crossVectors(fwd, new THREE.Vector3(0, 1, 0)).normalize();
        const up = new THREE.Vector3().crossVectors(right, fwd).normalize();
        return this.camera.position.clone()
            .addScaledVector(fwd, depth)
            .addScaledVector(right, lx)
            .addScaledVector(up, ly);
    }

    bindEvents() {
        this.onScroll = () => {
            if (this.ticking) return;
            this.ticking = true;
            requestAnimationFrame(() => { this.updateScrollTarget(); this.ticking = false; });
        };
        this.onMouse = (e) => {
            this.mouse.tx = (e.clientX / window.innerWidth - 0.5) * 2;
            this.mouse.ty = (e.clientY / window.innerHeight - 0.5) * 2;
        };
        this.onResize = () => {
            if (!this.renderer || !this.camera) return;
            this.camera.aspect = window.innerWidth / window.innerHeight;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(window.innerWidth, window.innerHeight);
            if (this.rendererFg) this.rendererFg.setSize(window.innerWidth, window.innerHeight);
            this.smallScreen = Math.min(window.innerWidth, window.innerHeight) < 500;
            this.layoutPlanet();
            this.layoutShield();
            this.updateScrollTarget(true);
            this.updateHudAnchor();
        };
        this.onVisibility = () => {
            this.isVisible = !document.hidden;
            if (this.isVisible && !this.isDestroyed && !this.frameId && !this.reduced && !this.lowPower) this.animate();
        };
        window.addEventListener('scroll', this.onScroll, { passive: true });
        window.addEventListener('mousemove', this.onMouse, { passive: true });
        window.addEventListener('resize', this.onResize, { passive: true });
        document.addEventListener('visibilitychange', this.onVisibility);
        this.bindGloveDrag();
    }

    bindGloveDrag() {
        this.onPointerDown = (e) => {
            if (!this.gloveCtl.positioning || this.gloveCtl.dragging) return;
            if (e.pointerType === 'mouse' && e.button !== 0) return;
            // Never hijack real controls — drag starts only on neutral surface.
            if (e.target && e.target.closest && e.target.closest('a, button, input, select, textarea, [data-glove-ui]')) return;
            this.gloveCtl.dragging = true;
            this.gloveDragMove(e);
        };
        this.onPointerMove = (e) => {
            if (!this.gloveCtl.positioning || !this.gloveCtl.dragging) return;
            this.gloveDragMove(e);
        };
        this.onPointerUp = () => { this.gloveCtl.dragging = false; };
        this.onTouchMove = (e) => {
            // Pause page scroll ONLY while actively dragging in positioning
            // mode; normal scrolling is untouched everywhere else.
            if (this.gloveCtl.positioning && this.gloveCtl.dragging) {
                if (e.cancelable) e.preventDefault();
            }
        };
        window.addEventListener('pointerdown', this.onPointerDown, { passive: true });
        window.addEventListener('pointermove', this.onPointerMove, { passive: true });
        window.addEventListener('pointerup', this.onPointerUp, { passive: true });
        window.addEventListener('pointercancel', this.onPointerUp, { passive: true });
        window.addEventListener('touchmove', this.onTouchMove, { passive: false });
    }

    gloveDragMove(e) {
        const w = Math.max(1, window.innerWidth);
        const h = Math.max(1, window.innerHeight);
        this.gloveCtl.tx = Global3DScene.gloveClamp(e.clientX / w);
        this.gloveCtl.ty = Global3DScene.gloveClamp(e.clientY / h);
        if ((this.reduced || this.lowPower) && this.renderer) this.renderOnce();
    }

    pageProgress() {
        const max = document.documentElement.scrollHeight - window.innerHeight;
        if (max <= 0) return 0;
        return Math.min(1, Math.max(0, window.scrollY / max));
    }

    currentSectionBias() {
        const sections = document.querySelectorAll('[data-3d-state]');
        if (!sections.length) return null;
        const mid = window.innerHeight * 0.5;
        let best = null;
        let bestDist = Infinity;
        sections.forEach((el) => {
            const r = el.getBoundingClientRect();
            if (r.bottom < 0 || r.top > window.innerHeight) return;
            const d = Math.abs(r.top + r.height / 2 - mid);
            if (d < bestDist) { bestDist = d; best = el.dataset['3dState'] || el.getAttribute('data-3d-state'); }
        });
        if (!best) return null;
        return STATE_BIAS[best] ?? null;
    }

    updateScrollTarget(immediate = false) {
        this.scroll.target = this.pageProgress();
        const bias = this.currentSectionBias();
        this.scroll.sectionTarget = bias !== null ? bias : this.scroll.target;
        if (immediate) {
            this.scroll.progress = this.scroll.target;
            this.scroll.sectionBias = this.scroll.sectionTarget;
        }
    }

    journey(p) {
        const base = this.basePlanetPos || { x: 3.2, y: 0.0, z: 0.0 };
        const isMobile = window.innerWidth < 768;
        const amp = isMobile ? 0.2 : 0.8;
        return {
            x: base.x - p * 3.5 * amp,
            y: base.y + Math.sin(p * Math.PI * 2) * 0.35,
            z: base.z - Math.sin(p * Math.PI) * 0.8,
            rotY: p * Math.PI * 1.5,
            camX: (p * 0.8 - 0.4) * (isMobile ? 0.3 : 0.8),
            camY: -p * 0.3 + 0.15,
            light: 4.0 + Math.sin(p * Math.PI * 2) * 1.0,
        };
    }

    applyState(p, snap = false) {
        const g = this.objects.planetGroup;
        if (!g) return;
        const j = this.journey(p);
        const k = snap ? 1 : 0.06;

        let tx = j.x, ty = j.y;
        if (this.hudAnchor) {
            const anchor = this.hudWorldTarget(this.hudAnchor.x, this.hudAnchor.y);
            if (anchor) { tx = anchor.x; ty = anchor.y; }
        }
        g.position.x += (tx - g.position.x) * k;
        g.position.y += (ty - g.position.y) * k;
        g.position.z += (j.z - g.position.z) * k;

        if (this.objects.ring) this.objects.ring.rotation.z += 0.001;
        if (this.objects.ring2) this.objects.ring2.rotation.z -= 0.0008;

        // Foreground shield positioning
        const s = this.objects.shieldGroup;
        if (s && this.sceneFg && this.baseShieldPos) {
            // User override: fixed mode anchors to the saved viewport position
            // (scroll-proof); positioning mode previews the drag target live.
            const fixed = this.gloveCtl.mode === 'fixed' && this.gloveCtl.x !== null && this.gloveCtl.y !== null;
            const preview = this.gloveCtl.positioning ? this.positioningTarget() : null;
            const anchor = preview || (fixed ? { x: this.gloveCtl.x, y: this.gloveCtl.y } : null);
            if (anchor) {
                const target = this.gloveWorldTarget(anchor.x, anchor.y);
                if (target) {
                    const fk = snap ? 1 : 0.12;
                    s.position.x += (target.x - s.position.x) * fk;
                    s.position.y += (target.y - s.position.y) * fk;
                    s.position.z += ((this.baseShieldPos.z || 0.5) - s.position.z) * fk;
                }
            } else {
                const amp = window.innerWidth < 768 ? 0.2 : 0.8;
                const sx = this.baseShieldPos.x - p * 3.5 * amp;
                const sy = this.baseShieldPos.y + Math.sin(p * Math.PI * 2) * 0.35;
                s.position.x += (sx - s.position.x) * k;
                s.position.y += (sy - s.position.y) * k;
            }
        }

        // Pointer parallax (smooth lerped tilt)
        this.mouse.x += (this.mouse.tx - this.mouse.x) * 0.05;
        this.mouse.y += (this.mouse.ty - this.mouse.y) * 0.05;

        g.rotation.y = this.mouse.x * 0.18 + Math.sin(performance.now() * 0.0008) * 0.08;
        g.rotation.x = -this.mouse.y * 0.12;

        this.camera.position.x += ((j.camX + this.mouse.x * 0.3) - this.camera.position.x) * k;
        this.camera.position.y += ((j.camY - this.mouse.y * 0.2) - this.camera.position.y) * k;
        this.camera.lookAt(new THREE.Vector3(g.position.x * 0.3, 0, 0));

        if (this.objects.key) this.objects.key.intensity = j.light;
    }

    renderOnce() {
        if (!this.renderer || !this.scene || !this.camera) return;
        this.applyState(this.scroll.progress || 0, true);
        this.renderer.render(this.scene, this.camera);
        if (this.rendererFg && this.sceneFg) this.rendererFg.render(this.sceneFg, this.camera);
    }

    animate() {
        if (this.isDestroyed) return;
        if (this.isVisible === false) { this.frameId = null; return; }
        this.frameId = requestAnimationFrame(() => this.animate());

        const p = this.scroll.progress + (this.scroll.sectionTarget - this.scroll.progress) * 0.5;
        this.scroll.progress += (this.scroll.target - this.scroll.progress) * 0.07;
        this.scroll.sectionBias += (this.scroll.sectionTarget - this.scroll.sectionBias) * 0.06;
        const blended = (p + this.scroll.sectionBias) / 2;
        this.applyState(Math.min(1, Math.max(0, blended)));

        const tSec = (performance.now() - (this.t0 || performance.now())) / 1000;
        this.animateObjects(tSec);

        this.renderer.render(this.scene, this.camera);
        if (this.rendererFg && this.sceneFg) this.rendererFg.render(this.sceneFg, this.camera);
    }

    animateObjects(t) {
        // Subtle floating breath of workstation
        if (this.objects.workstation) {
            this.objects.workstation.position.y = Math.sin(t * 1.5) * 0.04;
        }

        // Floating cloud breathing
        if (this.objects.cloudObj) {
            this.objects.cloudObj.position.y = 1.25 + Math.sin(t * 1.8) * 0.06;
            this.objects.cloudObj.rotation.y = Math.sin(t * 0.6) * 0.08;
        }

        // Data packets riding network links
        if (this.packets && this.packets.length) {
            for (const p of this.packets) {
                p.t = (p.t + 0.008 * p.speed) % 1;
                p.mesh.position.lerpVectors(p.from, p.to, p.t);
            }
        }

        // Network nodes subtle pulsing
        if (this.nodes) {
            for (const n of this.nodes) {
                const s = 1 + Math.sin(t * 2.0 + n.userData.phase) * 0.12;
                n.scale.set(s, s, s);
            }
        }

        // Shield crest subtle bobbing
        if (this.objects.crestMesh) {
            this.objects.crestMesh.rotation.y = Math.sin(t * 1.2) * 0.1;
            this.objects.crestMesh.position.y = Math.sin(t * 1.2 + 0.4) * 0.03;
        }

        // Shield protection core: gentle spin + depth pulse keeps the mesh alive
        if (this.objects.shieldCore) {
            this.objects.shieldCore.rotation.y = Math.sin(t * 1.6 + 1.2) * 0.12;
            this.objects.shieldCore.position.z = 0.12 + Math.sin(t * 1.1) * 0.015;
        }

        // Data rings revolving around shield
        if (this.dataRings) {
            this.dataRings.forEach((ring, idx) => {
                ring.rotation.z = t * (0.2 + idx * 0.15) * (idx % 2 === 0 ? 1 : -1);
            });
        }

        // Grid floor slow drift
        if (this.objects.gridFloor && this.objects.gridFloor.material.map) {
            this.objects.gridFloor.material.map.offset.x = t * 0.003;
        }
    }

    destroy() {
        this.isDestroyed = true;
        if (this.frameId) cancelAnimationFrame(this.frameId);
        this.frameId = null;
        window.removeEventListener('scroll', this.onScroll);
        window.removeEventListener('mousemove', this.onMouse);
        window.removeEventListener('resize', this.onResize);
        document.removeEventListener('visibilitychange', this.onVisibility);
        window.removeEventListener('pointerdown', this.onPointerDown);
        window.removeEventListener('pointermove', this.onPointerMove);
        window.removeEventListener('pointerup', this.onPointerUp);
        window.removeEventListener('pointercancel', this.onPointerUp);
        window.removeEventListener('touchmove', this.onTouchMove);

        this.scene?.traverse((o) => {
            if (o.geometry) o.geometry.dispose();
            if (o.material) (Array.isArray(o.material) ? o.material : [o.material]).forEach((m) => { if (m.map) m.map.dispose(); m.dispose(); });
        });
        this.sceneFg?.traverse((o) => {
            if (o.geometry) o.geometry.dispose();
            if (o.material) (Array.isArray(o.material) ? o.material : [o.material]).forEach((m) => { if (m.map) m.map.dispose(); m.dispose(); });
        });
        this.renderer?.dispose();
        this.rendererFg?.dispose();
        this.rendererFg = null;
        this.sceneFg = null;
        if (this.canvas) delete this.canvas.dataset.init;
        if (window._global3D === this) window._global3D = null;
    }
}

export default Global3DScene;

