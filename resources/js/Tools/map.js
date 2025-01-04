const transform = (a, r, tx, ty, o = 1) =>
    a.map(v => [(o * v[r] + tx) / 2, (o * v[1 - r] + ty) / 2]);

function hilbert(n) {
    if (n === 1)
        return transform(
            [
                [0, 0],
                [0, 1],
                [1, 1],
                [1, 0],
            ],
            0,
            0.5,
            0.5
        );
    const h = hilbert(n - 1);
    return [
        ...transform(h, 1, 0, 0),
        ...transform(h, 0, 0, 1),
        ...transform(h, 0, 1, 1),
        ...transform(h, 1, 2, 1, -1),
    ];
}

function genBorder(n, w, m) {
    w = 0.5 - w / 4;
    const mScaled = m * Math.pow(4, n);
    // console.time('hilbert');
    const points = hilbert(n);
    // console.timeEnd('hilbert');
    // Add points to fix end
    points.unshift(points[3]);
    points.push(points[points.length - 4]);
    // Calculate direction
    // console.time('dir');
    const nodes = [];
    for (let i = 0; i < points.length - 2; i++) {
        const p0 = points[i];
        const p1 = points[i + 1];
        const p2 = points[i + 2];
        const d1 = [p1[0] - p0[0], p1[1] - p0[1]];
        const d2 = [p2[0] - p1[0], p2[1] - p1[1]];
        nodes[i] = { p0, p1, p2, s: Math.sign(d1[0] * d2[1] - d1[1] * d2[0]) };
    }
    // console.timeEnd('dir');
    // Fix end
    const inverse = nodes
        .slice()
        .reverse()
        .map(({ p0, p1, p2, s }) => ({ p0: p2, p1, p2: p0, s: -s }));
    if (n % 2) nodes.splice(-3);
    else inverse.splice(0, 3);
    nodes.push(...inverse);
    // Remove walls
    const removeWall = r => {
        if (nodes[r + 1].s === -1 && nodes[r + 2].s === -1) {
            nodes[r].s--;
            nodes[r + 3].s--;
            nodes[r].p2 = nodes[r + 3].p1;
            nodes[r + 3].p0 = nodes[r].p1;
            nodes.splice(r + 1, 2);
        }
    };
    // Remove random walls
    // console.time('rnd wall');
    for (let i = 0; i < mScaled; i++) {
        let r = Math.floor(Math.random() * (nodes.length - 3));
        while (nodes[r + 1].s !== -1 || nodes[r + 2].s !== -1)
            r = (r + 1) % (nodes.length - 3);
        removeWall(r);
    }
    // console.timeEnd('rnd wall');
    // Remove bad looking walls
    // console.time('pretty wall');
    for (let i = 0; i < nodes.length - 3; i++) {
        if (nodes[i].s === 1 || nodes[i + 3].s === 1) {
            removeWall(i);
        }
    }
    // console.timeEnd('pretty wall');
    // Generate borders
    const path = [];
    // console.time('border');
    for (const { p0, p1, p2, s } of nodes) {
        if (s === 0) continue;
        const d1 = [(p1[0] - p0[0]) * w, (p1[1] - p0[1]) * w];
        const d2 = [(p2[0] - p1[0]) * w, (p2[1] - p1[1]) * w];
        path.push([p1[0] + s * (d1[0] - d2[0]), p1[1] + s * (d1[1] - d2[1])]);
    }
    // console.timeEnd('border');
    // Fix start
    if (n % 2) path[0] = path[path.length - 1];
    else path[path.length - 1] = path[0];
    return path;
}

/**
 * Calculates the total number of image placements in the exhibition rooms.
 * @param {number} n - Log2 of the grid size , map size.
 * @param {number} r - Size of the rooms.
 * @param {number} w - Wall thickness.
 * @param {number} m - Random wall removal proportion.
 * @param {number} h - Height of the walls.
 * @returns {number} The total number of image placements.
 */
export const countPlacements = async (n, r, w) => {

    const m = 0.4;

    const s = r * Math.pow(2, n);
    const border = genBorder(n + 1, w, m).map(v => [v[0] * s, v[1] * s]);
    let count = 0;

    for (let i = 0; i < border.length - 1; i++) {
        const p0 = border[i];
        const p1 = border[i + 1];
        const dx = p1[0] - p0[0];
        const dy = p1[1] - p0[1];
        const l = Math.hypot(dx, dy);
        const lParts = Math.ceil(l / 8 - 0.3);

        if (lParts <= 0) {
            if (l > 1) count++;
        } else {
            const partLength = l / lParts;
            if (partLength > 1) {
                count += lParts === 1 ? 1 : lParts - 1;
            }
        }
    }

    return count;
};

// Example usage:

// const input = {
//     mapSize: 1,
//     cellSize: 5,
//     wallThickness: 0.3,
//     wallRemoval: 0.5,
// };

// const totalPlacements = countPlacements(
//     input.mapSize,
//     input.cellSize,
//     input.wallThickness,
//     input.wallRemoval,
// );
// console.log(`Total image placements available : ${totalPlacements}`);
