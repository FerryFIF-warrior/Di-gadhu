/*Scrool animasi ringan*/
const fadeSections = document.querySelectorAll(".fade-section");

function reveal() {
    const trigger = window.innerHeight * 0.9;

    fadeSections.forEach(section => {
        const top = section.getBoundingClientRect().top;

        if(top < trigger) {
            section.classList.add("show")
        }
    });
}

/*workflow*/
const steps = document.querySelectorAll(".section-step");

function revealSteps(){
    const track = window.innerHeight * 0.8;
    steps.forEach(step => {
        const stepTop = step.getBoundingClientRect().top;

        if(stepTop < track) {
            step.classList.add("show")
        }
    });
}
window.addEventListener("scroll", revealSteps);
window.addEventListener("load", revealSteps);

const reveals = document.querySelectorAll(".reveal");
window.addEventListener("scroll", () => {
    const windowHeight = window.innerHeight;
    reveals.forEach(reveal => {
        const elementTop = reveal.getBoundingClientRect().top;
        const revealPoint = 100;

        if(elementTop < windowHeight - revealPoint){
            reveal.classList.add("active")
        }
    });
});

window.addEventListener("scroll", reveal);
window.addEventListener("load", reveal);

document.querySelector('.bAnalis').addEventListener('click', function() {
    const target = document.getElementById('analysis-section');
    if (target) {
        target.scrollIntoView({behavior: 'smooth'});
    }
});

function analisisCuaca() {
    // Ambil elemen-elemen form
    const desa = document.getElementById('desa');
    const kab = document.getElementById('kabupaten');
    const prov = document.getElementById('provinsi');
    const tanaman = document.getElementById('tanaman');
    const hasil = document.getElementById('hasilAnalisis');


    if (!desa || !kab || !prov || !tanaman || !hasil) {
        alert('Maaf permintaan anda tidak dapat di proses untuk saat ini');
        return;
    }

    const desaVal = desa.value.trim().toLowerCase();
    const kabVal = kab.value.trim().toLowerCase();
    const provVal = prov.value.trim().toLowerCase();
    const tanamanVal = tanaman.value;

    const inputKey = `${desaVal}, ${kabVal}, ${provVal}`;
    const targetKey = "mlarak, ponorogo, jawa timur";

    if (inputKey!== targetKey) {
        hasil.innerHTML = '<p style="color:red">Maaf, saat ini hanya lokasi tertentu yang sudah bisa dilakukan analisis.</p>';
        return;
    }

    const cuaca = "Curah hujan tinggi(85%), suhu 26°C, kelembaban 85%";
    const rekomendasiUmum = "padi, jagung";

    const detailTanaman = {
        padi: "Sangat cocok - musim hujan mendukung pertumbuhan padi.",
        jagung: "Cocok - namun pastikan drainase baik.",
        cabai: "Kurang cocok - kelembaban tinggi dapat memicu penyakit tanaman cabai.",
        terong: "Cukup cocok - tanam ketika awal musim kemarau.",
        kacang: "Tidak cocok - terlalu lembab."
    };

    let output = `<div style="background:#e0f2e0; padding:15px; border-radius:8px;">`;
    output += `<h3 style="color:#2f5d3a;">Analisis di Mlarak, Ponorogo</h3>`;
    output += `<p><strong>Cuaca:</strong> ${cuaca}</p>`;

    if (tanamanVal) {
          const tanamanLower = tanamanVal.toLowerCase();
          if (detailTanaman[tanamanLower]) {
            output += `<p><strong>Tanaman ${tanamanVal}:</strong> ${detailTanaman[tanamanLower]}</p>`;
          } else {
            output += `<p>Tanaman ${tanamanVal} tidak ada dalam data, Rekomendasi umum: ${rekomendasiUmum}</p>`;
          }
    } else {
        //jika tidak memilih
        output += `<p><strong>Rekomendasi tanaman:</strong> ${rekomendasiUmum}</p>`;
    }
     output += `</div>`;

     hasil.innerHTML = output;
}

/* Login System */
const validEmail = "admin@example.com";
const validPassword = "password123";

window.addEventListener('DOMContentLoaded', () => {
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    if (isLoggedIn === 'True') {
        window.location.href = "index.HTML";
    }
});

document.getElementById('loginform').addEventListener('submit', function(e) {

    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const remember = document.getElementById('remember').checked;
    const messageDiv = document.getElementById('LoginMessage');

    if (email === "" || password === "") {
        messageDiv.innerHTML = '<span class="text-red-600">Email dan Password harus diisi</span>';
        return;
    }

    if (email === validEmail && password === validPassword) {

        messageDiv.innerHTML = '<span class="text-green-600">Login berhasil! Mengarahkan...</span>';

        if (remember) {
            localStorage.setItem('isLoggedIn', 'true');
            localStorage.setItem('userEmail', email);
        } else {
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('userEmail', email);
        }

        setTimeout(() => {
            window.location.href = "index.HTML";
        }, 1000);
    } else {

        messageDiv.innerHTML = '<span class="text-red-600">Enal Atau password salah!</span>';
    }
});