const params = new URLSearchParams(window.location.search);
const id = params.get("id");

fetch("dataTanaman.json")
    .then(res => res.json())
    .then(data => {
        const tanaman = data.tanaman.find(t => t.id === id);
        if (!tanaman) {
            document.getElementById("plantName").innerText = "Tanaman tidak ditemukan";
            return;
        }
        tampilkanDetail(tanaman);
    })
    .catch(err => {
        console.error(err);
        document.getElementById("plantName").innerText = "Gagal memuat data";
    });

function tampilkanDetail(tanaman) {
    // Set fixed background image
    const bgDiv = document.getElementById("bgImage");
    bgDiv.style.backgroundImage = `url(${tanaman.gambar})`;
    bgDiv.style.backgroundSize = "cover";
    bgDiv.style.backgroundPosition = "center";
    bgDiv.style.backgroundRepeat = "no-repeat";

    // Set plant name on hero
    document.getElementById("plantName").innerText = tanaman.nama;

    // Fill glass section
    document.getElementById("fullDesc").innerHTML = tanaman.deskripsi;
    document.getElementById("suhu").innerHTML = tanaman.suhuIdeal;
    document.getElementById("kelembaban").innerHTML = tanaman.kelembapanIdeal;
    document.getElementById("tanah").innerHTML = tanaman.tanah;
    document.getElementById("musim").innerHTML = tanaman.musim;
    document.getElementById("keunggulan").innerHTML = tanaman.keunggulan;
}