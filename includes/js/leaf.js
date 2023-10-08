const numLeaves = 10; // Số lượng lá

for (let i = 0; i < numLeaves; i++) {
    createLeaf();
}

function createLeaf() {
    const leaf = document.createElement('div');
    leaf.className = 'leaf';
    document.body.appendChild(leaf);

    // Vị trí và độ trễ ngẫu nhiên
    const x = Math.random() * window.innerWidth;
    const delay = Math.random() * 5000;

    leaf.style.left = `${x}px`;
    leaf.style.animationDelay = `${delay}ms`;
}