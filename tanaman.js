let databaseTanaman = [];

fetch("dataTanaman.json")
.then(res => res.json())
.then(data => {

    databaseTanaman = data.tanaman;
    tampilkanTanaman();
});

fetch("dataTanaman.json")
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById("tanamanContainer");
        container.innerHTML = "";
        const tanamanList = data.tanaman;

        tanamanList.forEach((tanaman, index) => {
            const card = document.createElement("div");
            card.className = "bg-white rounded-xl overflow-hidden shadow-lg plant-card card-enter";
            card.style.animationDelay = `${index * 0.05}s`; // staggered animation

            // Short description (max 100 chars)
            const shortDesc = tanaman.deskripsi.length > 100 
                ? tanaman.deskripsi.substring(0, 100) + "..." 
                : tanaman.deskripsi;

            card.innerHTML = `
                <img src="${tanaman.gambar}" alt="${tanaman.nama}" class="w-full h-48 object-cover">
                <div class="p-5">
                    <h2 class="text-xl font-bold mb-2" style="color: var(--forest-green);">${tanaman.nama}</h2>
                    <p class="text-gray-600 text-sm mb-4">${shortDesc}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold" style="background: var(--light-green); color: var(--dark-green);">Lihat Detail →</span>
                </div>
            `;

            card.addEventListener("click", () => {
                // Navigasi ke halaman detail dengan id tanaman
                window.location.href = `detailTanaman.HTML?id=${tanaman.id}`;
            });

            container.appendChild(card);
        });
    })
    .catch(err => {
        console.error("Gagal memuat data tanaman:", err);
        document.getElementById("tanamanContainer").innerHTML = '<p class="text-red-500">Gagal memuat data tanaman.</p>';
    });