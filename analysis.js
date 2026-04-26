let databasesTanaman = [];

// Ambil data tanaman
fetch("dataTanaman.json")
  .then((response) => response.json())
  .then((data) => {
    databasesTanaman = data.tanaman;
    isiDropdownTanaman();
  })
  .catch((error) => {
    console.error("Gagal memuat data tanaman: ", error);
    alert("Gagal memuat data tanaman. Silakan coba lagi.");
  });

function loadProvinces() {
  console.log("Memuat provinsi...");
  fetch("api/get_api.php?level=prov")
    .then((response) => response.json())
    .then((data) => {
      const selectProv = document.getElementById("provinsi");
      selectProv.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach((prov) => {
          const option = document.createElement("option");
          option.value = prov.id;
          option.textContent = prov.name;
          selectProv.appendChild(option);
        });
        selectProv.disabled = false;
      } else {
        console.error("Data provinsi kosong atau tidak valid:", data);
        selectProv.innerHTML = '<option value="">Gagal memuat provinsi</option>';
        selectProv.disabled = true;
      }
    })
    .catch((err) => {
      console.error("Error load provinsi:", err);
      document.getElementById("provinsi").innerHTML = '<option value="">Error memuat provinsi</option>';
      document.getElementById("provinsi").disabled = true;
    });
}

document.getElementById("provinsi").addEventListener("change", function () {
  const kodeProv = this.value;
  const selectKab = document.getElementById("kabupaten");
  const selectKec = document.getElementById("kecamatan");

  if (!kodeProv) {
    selectKab.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
    selectKab.disabled = true;
    selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    selectKec.disabled = true;
    return;
  }

  selectKab.innerHTML = '<option value="">Memuat kabupaten...</option>';
  selectKab.disabled = true;
  selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
  selectKec.disabled = true;

  fetch(`api/get_api.php?level=kab&kode=${kodeProv}`)
    .then((res) => res.json())
    .then((data) => {
      selectKab.innerHTML = '<option value="">-- Pilih Kabupaten --</option>';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach((kab) => {
          const option = document.createElement("option");
          option.value = kab.id;
          option.textContent = kab.name;
          selectKab.appendChild(option);
        });
        selectKab.disabled = false;
      } else {
        selectKab.innerHTML = '<option value="">Tidak ada kabupaten</option>';
        selectKab.disabled = true;
      }
    })
    .catch((err) => {
      console.error("Error fetch kabupaten:", err);
      selectKab.innerHTML = '<option value="">Error memuat kabupaten</option>';
      selectKab.disabled = true;
    });
});

document.getElementById("kabupaten").addEventListener("change", function () {
  const kodeKab = this.value;
  const selectKec = document.getElementById("kecamatan");

  if (!kodeKab) {
    selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
    selectKec.disabled = true;
    return;
  }

  selectKec.innerHTML = '<option value="">Memuat kecamatan...</option>';
  selectKec.disabled = true;

  fetch(`api/get_api.php?level=kec&kode=${kodeKab}`)
    .then((res) => res.json())
    .then((data) => {
      selectKec.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
      if (Array.isArray(data) && data.length > 0) {
        data.forEach((kec) => {
          const option = document.createElement("option");
          option.value = kec.id;
          option.textContent = kec.name;
          selectKec.appendChild(option);
        });
        selectKec.disabled = false;
      } else {
        selectKec.innerHTML = '<option value="">Tidak ada kecamatan</option>';
        selectKec.disabled = true;
      }
    })
    .catch((err) => {
      console.error("Error fetch kecamatan:", err);
      selectKec.innerHTML = '<option value="">Error memuat kecamatan</option>';
      selectKec.disabled = true;
    });
});

function isiDropdownTanaman() {
  const select = document.getElementById("tanamanSelect");
  if (!select) return;
  select.innerHTML = `<option value="">--- Pilih Tanaman ---</option>`;
  databasesTanaman.forEach((tanaman) => {
    const option = document.createElement("option");
    option.value = tanaman.id;
    option.textContent = tanaman.nama;
    select.appendChild(option);
  });
}

loadProvinces();

async function jalankanAnalisis() {
  const selectTanaman = document.getElementById("tanamanSelect");
  const hasilDiv = document.getElementById("hasilAnalisisDetail");
  const provinsi = document.getElementById("provinsi");
  const kabupaten = document.getElementById("kabupaten");
  const kecamatan = document.getElementById("kecamatan");

  const tanamanId = selectTanaman.value;
  const namaProv = provinsi.options[provinsi.selectedIndex]?.text || "";
  const namaKab = kabupaten.options[kabupaten.selectedIndex]?.text || "";
  const namaKec = kecamatan.options[kecamatan.selectedIndex]?.text || "";

  if (!tanamanId) {
    hasilDiv.innerHTML = `<p class="text-gray-500">Silakan pilih tanaman terlebih dahulu</p>`;
    return;
  }
  if (!namaProv || !namaKab || !namaKec) {
    hasilDiv.innerHTML = `<p class="text-red-500">Harap lengkapi Provinsi, Kabupaten, dan Kecamatan</p>`;
    return;
  }

  const tanaman = databasesTanaman.find((t) => t.id === tanamanId);
  if (!tanaman) {
    hasilDiv.innerHTML = `<p class="text-red-500">Detail tanaman tidak ditemukan</p>`;
    return;
  }

  const cuaca = "hujan"; 
  let status = (cuaca === "hujan") ? tanaman.statusCuacaHujan : tanaman.statusCuacaPanas;

  hasilDiv.innerHTML = `
    <div class="space-y-4">
      <h2 class="text-2xl font-bold" style="color: var(--forest-green);">${tanaman.nama}</h2>
      <p><strong>Lokasi:</strong> ${namaKec}, ${namaKab}, ${namaProv}</p>
      <p>${tanaman.deskripsi}</p>
      <div class="grid grid-cols-2 gap-4">
        <div class="p-3 rounded" style="background: var(--water-blue); color: white;">
          <p class="text-sm">Suhu Ideal</p>
          <p class="font-semibold">${tanaman.suhuIdeal}</p>
        </div>
        <div class="p-3 rounded" style="background: var(--water-blue); color: white;">
          <p class="text-sm">Kelembaban Ideal</p>
          <p class="font-semibold">${tanaman.kelembapanIdeal}</p>
        </div>
      </div>
      <div class="p-4 rounded font-semibold" style="background: var(--light-green);">Status Tanam: ${status}</div>
    </div>
  `;
}