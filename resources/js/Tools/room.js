import { vec3, mat4 } from "gl-matrix";

/**
 * Global camera state object.
 * Tracks the rotation, height, and distance of the camera.
 */
const cameraState = {
    rotation: 0, // Current rotation angle of the camera (in radians)
    height: 0,   // Current height of the camera
    distance: 5, // Current distance of the camera from the center of the room
};

/**
 * Initializes and renders a 3D room scene.
 * @param {Object} regl - The regl instance for WebGL rendering.
 * @param {HTMLElement} container - The DOM element to render the WebGL canvas into.
 * @param {string} ceilingTexture - URL or path to the ceiling texture.
 * @param {string} wallTexture - URL or path to the wall texture.
 * @param {string} floorTexture - URL or path to the floor texture.
 * @param {Object} roomDimensions - Dimensions of the room (width, length, height).
 * @param {string} color - Hex color string to tint the room surfaces.
 * @param {Function} setCameraState - Callback to update the camera state in the Vue component.
 */
export const initScene = async (
    regl,
    container,
    ceilingTexture,
    wallTexture,
    floorTexture,
    roomDimensions,
    color,
    setCameraState
) => {
    /**
     * Loads an image from a URL and creates a WebGL texture.
     * @param {Object} texture - The regl.texture object to which the image will be applied.
     * @param {string} url - The URL of the image to load.
     */
    const loadTextureFromImage = async (texture, url) => {
        const result = await fetch(url);
        const blob = await result.blob();
        const data = await createImageBitmap(blob);
        texture({
            data,
            wrapS: "repeat",
            wrapT: "repeat",
            min: "mipmap", // Enable mipmapping for minification
            mag: "linear", // Use linear filtering for magnification
        });
    };

    // Create regl textures for the ceiling, walls, and floor
    const wallTextureLoader = regl.texture();
    const floorTextureLoader = regl.texture();
    const ceilingTextureLoader = regl.texture();

    // Load textures from the provided URLs
    await loadTextureFromImage(wallTextureLoader, wallTexture);
    await loadTextureFromImage(floorTextureLoader, floorTexture);
    await loadTextureFromImage(ceilingTextureLoader, ceilingTexture);

    // Camera variables
    const eye = vec3.create(); // Camera position
    const target = vec3.fromValues(0, 0, 0); // Camera target (center of the room)
    const up = vec3.fromValues(0, 1, 0); // Up vector for the camera

    // Matrices for camera and projection
    const viewMatrix = mat4.create();
    const projectionMatrix = mat4.create();

    /**
     * Generates the geometry (vertices and UV coordinates) for the room.
     * @param {number} width - Width of the room.
     * @param {number} length - Length of the room.
     * @param {number} height - Height of the room.
     * @returns {Object} - Room geometry including floor, walls, and ceiling.
     */
    function createRoom(width, length, height) {
        const w = width / 2;
        const l = length / 2;
        const h = height / 2;

        return {
            floor: {
                positions: [-w, -h, -l, w, -h, -l, w, -h, l, -w, -h, l],
                uvs: [0, 0, 1, 0, 1, 1, 0, 1],
            },
            walls: [
                {
                    id: "back",
                    positions: [-w, -h, -l, w, -h, -l, w, h, -l, -w, h, -l],
                    uvs: [0, 0, 1.5, 0, 1.5, 1.5, 0, 1.5], // Front/back walls (no change)
                },
                {
                    id: "left",
                    positions: [-w, -h, -l, -w, h, -l, -w, h, l, -w, -h, l],
                    uvs: [0, 0, 0, 1.5, 1.5, 1.5, 1.5, 0], // Left wall (corrected UVs)
                },
                {
                    id: "right",
                    positions: [w, -h, -l, w, h, -l, w, h, l, w, -h, l],
                    uvs: [2, 0, 2, 2, 0, 2, 0, 0], // Right wall (corrected UVs)
                },
                {
                    id: "front",
                    positions: [-w, -h, l, w, -h, l, w, h, l, -w, h, l],
                    uvs: [0, 0, 1.5, 0, 1.5, 1.5, 0, 1.5], // Front/back walls (no change)
                },
            ],
            ceiling: {
                positions: [-w, h, -l, w, h, -l, w, h, l, -w, h, l],
                uvs: [0, 0, 1, 0, 1, 1, 0, 1],
            },
        };
    }

    // Create the room geometry
    const room = createRoom(
        roomDimensions.width,
        roomDimensions.length,
        roomDimensions.height
    );

    /**
     * Regl command to draw a textured surface.
     */
    const drawTexturedSurface = regl({
        vert: `
            precision mediump float;
            attribute vec3 position;
            attribute vec2 uv;
            uniform mat4 projection, view;
            uniform vec3 tint;
            varying vec2 vUv;
            varying vec3 vTint;
            void main() {
                vUv = uv;
                vTint = tint;
                gl_Position = projection * view * vec4(position, 1);
            }
        `,
        frag: `
            precision highp float; // Use high precision
            uniform sampler2D texture;
            varying vec2 vUv;
            varying vec3 vTint;
            void main() {
                vec2 scaledUv = vUv * 4.0; // Reduce the scaling factor
                vec4 texColor = texture2D(texture, scaledUv);
                gl_FragColor = vec4(texColor.rgb * vTint, 1.0);
            }
        `,
        attributes: {
            position: regl.prop("positions"),
            uv: regl.prop("uvs"),
        },
        uniforms: {
            texture: regl.prop("texture"),
            tint: regl.prop("tint"),
            view: () => viewMatrix,
            projection: () => projectionMatrix,
        },
        primitive: "triangle fan",
        count: 4,
    });

    // Color state for the room surfaces
    let colors = {
        floor: hexToRgb(color),
        walls: hexToRgb(color),
        ceiling: hexToRgb(color),
    };

    // Clear any existing camera rotation interval
    if (window.cameraRotationInterval) clearInterval(window.cameraRotationInterval);

    // Start a new interval to update the camera rotation
    window.cameraRotationInterval = setInterval(() => {
        cameraState.rotation += 0.01; // Increment rotation
        setCameraState({ ...cameraState }); // Update camera state in Vue
    }, 1);

    /**
     * Converts a hex color string to an RGB array.
     * @param {string} hex - Hex color string (e.g., "#e8e8e8").
     * @returns {number[]} - RGB values normalized to [0, 1].
     */
    function hexToRgb(hex) {
        const r = parseInt(hex.slice(1, 3), 16) / 255;
        const g = parseInt(hex.slice(3, 5), 16) / 255;
        const b = parseInt(hex.slice(5, 7), 16) / 255;
        return [r, g, b];
    }

    /**
     * Updates the camera position based on the current camera state.
     */
    function updateCamera() {
        const rad = (cameraState.rotation * Math.PI) / 180;
        eye[0] = Math.sin(rad) * cameraState.distance;
        eye[1] = cameraState.height;
        eye[2] = Math.cos(rad) * cameraState.distance;
    }

    /**
     * Main rendering loop.
     */
    function render() {
        updateCamera();

        const canvas = container;
        const aspect = canvas.clientWidth / canvas.clientHeight;
        mat4.perspective(projectionMatrix, Math.PI / 3, aspect, 0.1, 100.0); // Increase FOV to 60 degrees
        mat4.lookAt(viewMatrix, eye, target, up);

        regl.clear({
            color: [0.9, 0.9, 0.9, 1],
            depth: 1,
        });

        // Draw floor
        drawTexturedSurface({
            positions: room.floor.positions,
            uvs: room.floor.uvs,
            texture: floorTextureLoader,
            tint: colors.floor,
        });

        // Draw walls
        room.walls.forEach((wall) => {
            drawTexturedSurface({
                positions: wall.positions,
                uvs: wall.uvs,
                texture: wallTextureLoader,
                tint: colors.walls,
            });
        });

        // Draw ceiling
        drawTexturedSurface({
            positions: room.ceiling.positions,
            uvs: room.ceiling.uvs,
            texture: ceilingTextureLoader,
            tint: colors.ceiling,
        });

        requestAnimationFrame(render);
    }

    // Handle window resize
    window.addEventListener("resize", () => {
        regl.poll();
    });

    // Start rendering
    render();
};
