<?php
require_once 'koneksi.php';
session_start();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budidaya - DI-GADHU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'dark-green': '#1f3d2b',
                        'forest-green': '#2f5d3a',
                        'leaf-green': '#4f8a5b',
                        'light-green': '#8ccf9b',
                        'water-blue': '#4aa3c7',
                        'soil-brown': '#8b6b4c',
                        'soft-cream': '#f4f1e6',
                    },
                    fontFamily: {
                        'sans': ['Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif'],
                    },
                    animation: {
                        'slide-in': 'slideIn 0.6s ease forwards',
                    },
                    keyframes: {
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateX(-20px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .step-timeline::before {
                content: '';
                position: absolute;
                left: 28px;
                top: 0;
                bottom: 0;
                width: 3px;
                background: linear-gradient(180deg, #8ccf9b, #4aa3c7, #8b6b4c);
                border-radius: 2px;
            }
            @media (max-width: 768px) {
                .step-timeline::before {
                    left: 18px;
                }
            }
            .fade-up {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease;
            }
            .fade-up.visible {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-soft-cream text-dark-green font-sans leading-relaxed">

    <!-- Navbar -->
    <nav class="bg-forest-green px-4 md:px-8 flex items-center justify-between h-16 sticky top-0 z-50 shadow-lg transition-all duration-300" id="navbar">
        <a href="#" class="flex items-center gap-3 no-underline">
            <img src="../Resource/Logo2.png" alt="Logo" class="w-10 h-10 object-contain">
            <span class="text-light-green font-bold text-lg tracking-wide">DI-GADHU</span>
        </a>
        <button class="md:hidden bg-transparent border-none cursor-pointer p-2" onclick="toggleNav()" aria-label="Toggle navigation">
            <span class="block w-6 h-0.5 bg-light-green mb-1.5 rounded transition-all duration-300" id="bar1"></span>
            <span class="block w-6 h-0.5 bg-light-green mb-1.5 rounded transition-all duration-300" id="bar2"></span>
            <span class="block w-6 h-0.5 bg-light-green rounded transition-all duration-300" id="bar3"></span>
        </button>
        <ul class="hidden md:flex list-none gap-1" id="navMenu">
            <li><a href="mainMenu.php" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-2 rounded-lg text-[0.95rem] font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Beranda</a></li>
            <li><a href="budidaya.php" class="no-underline text-white bg-leaf-green font-bold px-5 py-2 rounded-lg text-[0.95rem] relative transition-all duration-300 hover:text-white hover:bg-leaf-green/90 active-nav">Budidaya
                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-5 h-0.5 bg-water-blue rounded-sm"></span>
            </a></li>
            <li><a href="../tanaman.HTML" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-2 rounded-lg text-[0.95rem] font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Jenis Tanaman</a></li>
            <li><a href="analisis.HTML" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-2 rounded-lg text-[0.95rem] font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Analisis</a></li>
        </ul>
    </nav>

    <!-- Mobile Nav Menu -->
    <ul class="hidden bg-forest-green flex-col p-4 gap-1 shadow-xl md:hidden fixed top-16 left-0 right-0 z-40" id="mobileMenu">
        <li><a href="mainMenu.php" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-3 rounded-lg text-base font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Beranda</a></li>
        <li><a href="budidaya.php" class="no-underline text-white bg-leaf-green font-bold px-5 py-3 rounded-lg text-base transition-all duration-300">Budidaya</a></li>
        <li><a href="../tanaman.HTML" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-3 rounded-lg text-base font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Jenis Tanaman</a></li>
        <li><a href="../analisis.HTML" class="no-underline text-[rgba(244,241,230,0.75)] px-5 py-3 rounded-lg text-base font-medium transition-all duration-300 hover:text-white hover:bg-white/10">Analisis</a></li>
    </ul>

    <!-- Hero Section -->
    <section class="relative h-[420px] overflow-hidden flex items-center justify-center">
        <img src="https://image.qwenlm.ai/public_source/165af3b6-030a-4e60-9d46-0716c0d865d5/1312f6203-a9a8-4140-a09a-4698626632f8.png" alt="Lahan Pertanian" class="absolute inset-0 w-full h-full object-cover brightness-50">
        <div class="absolute inset-0 bg-gradient-to-b from-dark-green/40 to-dark-green/80"></div>
        <div class="relative z-10 text-center text-white max-w-2xl px-8">
            <span class="inline-block bg-light-green/25 border border-light-green/50 text-light-green px-5 py-1.5 rounded-full text-sm font-semibold mb-4 tracking-wider uppercase"> Panduan Lengkap</span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 leading-tight drop-shadow-lg">Teknik Budidaya Sayuran</h1>
            <p class="text-base opacity-90 leading-relaxed max-w-lg mx-auto">Pelajari cara menanam, merawat, dan memanen sayuran dengan teknik budidaya yang tepat untuk hasil optimal dan berkelanjutan.</p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 md:px-8 py-12">

        <!-- Info Cards Section -->
        <section class="fade-up mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark-green mb-2">Informasi Budidaya</h2>
                <div class="w-[60px] h-1 mx-auto my-3 rounded-sm" style="background: linear-gradient(90deg, #4f8a5b, #4aa3c7);"></div>
                <p class="text-soil-brown text-sm max-w-md mx-auto">Faktor-faktor penting yang perlu diperhatikan dalam budidaya sayuran</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(31,61,43,0.08)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(31,61,43,0.15)]">
                    <img src="https://image.qwenlm.ai/public_source/165af3b6-030a-4e60-9d46-0716c0d865d5/1312f6203-a9a8-4140-a09a-4698626632f8.png" alt="Lahan Pertanian" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-dark-green mb-2">Persiapan Lahan</h3>
                        <p class="text-[#5a6b5e] text-[0.95rem] leading-relaxed">Pilih lahan yang mendapat sinar matahari minimal 6 jam sehari. Bersihkan gulma dan pastikan drainase baik. Lakukan pengolahan tanah dengan mencangkul sedalam 20-30 cm agar struktur tanah gembur dan subur.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(31,61,43,0.08)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(31,61,43,0.15)]">
                    <img src="https://image.qwenlm.ai/public_source/165af3b6-030a-4e60-9d46-0716c0d865d5/16530fae5-1303-42d2-9af4-8c41feb8ae47.png" alt="Pemupukan" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-dark-green mb-2">Pemupukan & Nutrisi</h3>
                        <p class="text-[#5a6b5e] text-[0.95rem] leading-relaxed">Berikan pupuk organik seperti kompos atau pupuk kandang 2 minggu sebelum tanam. Tambahkan pupuk NPK secara berkala sesuai fase pertumbuhan tanaman untuk memastikan nutrisi optimal.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white rounded-2xl overflow-hidden shadow-[0_4px_20px_rgba(31,61,43,0.08)] transition-all duration-300 hover:-translate-y-1.5 hover:shadow-[0_8px_30px_rgba(31,61,43,0.15)]">
                    <img src="https://image.qwenlm.ai/public_source/165af3b6-030a-4e60-9d46-0716c0d865d5/13cfd9d00-164a-4864-85a9-8de84acdedd1.png" alt="Irigasi" class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-dark-green mb-2">Irigasi & Penyiraman</h3>
                        <p class="text-[#5a6b5e] text-[0.95rem] leading-relaxed">Sayuran membutuhkan air yang cukup namun tidak berlebihan. Lakukan penyiraman pagi dan sore hari. Gunakan sistem irigasi tetes untuk efisiensi air dan menjaga kelembapan tanah tetap stabil.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cultivation Steps Section -->
        <section class="step-timeline bg-gradient-to-br from-dark-green to-forest-green rounded-3xl p-8 md:p-12 mb-16 text-white fade-up relative">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-2">Tahapan Menanam Sayuran</h2>
                <div class="w-[60px] h-1 mx-auto my-3 rounded-sm" style="background: linear-gradient(90deg, #8ccf9b, #4aa3c7);"></div>
                <p class="text-white/70 text-sm max-w-md mx-auto">Ikuti langkah-langkah berikut untuk budidaya sayuran yang sukses</p>
            </div>
            <div class="step-timeline relative max-w-3xl mx-auto">
                <!-- Step 1 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.1s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-light-green text-dark-green">1</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Pemilihan Bibit Berkualitas</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Pilih bibit unggul dari sumber terpercaya. Pastikan bibit bebas dari penyakit, memiliki daya kecambah tinggi, dan sesuai dengan iklim serta kondisi tanah di lokasi Anda. Bibit berkualitas adalah kunci keberhasilan budidaya.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.2s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-leaf-green text-white">2</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Pengolahan Tanah</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Cangkul tanah hingga kedalaman 20-30 cm, hancurkan gumpalan tanah besar, dan buat bedengan dengan tinggi 15-20 cm. Campurkan pupuk kandang atau kompos ke dalam tanah untuk meningkatkan kesuburan alami.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.3s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-water-blue text-white">3</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Penyemaian</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Taburkan bibit pada bedengan semai atau wadah semai. Tutup tipis dengan tanah halus dan jaga kelembapan dengan penyiraman lembut. Bibit siap dipindah setelah memiliki 3-4 daun sejati, biasanya 15-25 hari setelah semai.</p>
                    </div>
                </div>
                <!-- Step 4 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.4s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-soil-brown text-white">4</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Penanaman & Jarak Tanam</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Pindahkan bibit ke lahan utama dengan hati-hati. Perhatikan jarak tanam yang tepat untuk setiap jenis sayuran. Contoh: kangkung 15x15 cm, sawi 20x20 cm, cabai 50x70 cm. Jarak tanam yang tepat mencegah persaingan nutrisi.</p>
                    </div>
                </div>
                <!-- Step 5 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.5s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-light-green text-dark-green">5</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Penyiraman Rutin</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Sirami tanaman secara rutin 2 kali sehari (pagi dan sore). Hindari genangan air yang dapat menyebabkan busuk akar. Gunakan mulsa jerami atau plastik untuk menjaga kelembapan tanah dan mengurangi frekuensi penyiraman.</p>
                    </div>
                </div>
                <!-- Step 6 -->
                <div class="flex gap-6 md:gap-8 mb-10 animate-slide-in relative" style="animation-delay: 0.6s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-water-blue text-white">6</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Pengendalian Hama & Penyakit</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Monitor tanaman secara berkala untuk mendeteksi serangan hama dan penyakit lebih dini. Gunakan pestisida organik seperti pestisida nabati (ekstrak daun mimba, bawang putih) untuk menjaga keamanan dan keberlanjutan lingkungan.</p>
                    </div>
                </div>
                <!-- Step 7 -->
                <div class="flex gap-6 md:gap-8 animate-slide-in relative" style="animation-delay: 0.7s;">
                    <div class="w-14 h-14 md:w-[58px] md:h-[58px] min-w-[58px] rounded-full flex items-center justify-center text-xl md:text-[1.3rem] font-extrabold z-10 border-[3px] border-white/20 bg-leaf-green text-white">7</div>
                    <div class="bg-white/8 rounded-2xl p-5 md:p-6 flex-1 backdrop-blur-sm border border-white/10 transition-all duration-300 hover:bg-white/14">
                        <h3 class="text-lg md:text-[1.15rem] font-semibold mb-1 flex items-center gap-2">Panen & Pascapanen</h3>
                        <p class="text-sm md:text-[0.93rem] opacity-80 leading-relaxed">Panen sayuran saat mencapai umur optimal untuk kualitas terbaik. Lakukan panen pada pagi hari saat suhu masih sejuk. Setelah panen, cuci bersih, sortir, dan simpan di tempat sejuk untuk mempertahankan kesegaran dan nilai gizi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Tips Section -->
        <section class="fade-up mb-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-dark-green mb-2">Tips Sukses Budidaya</h2>
                <div class="w-[60px] h-1 mx-auto my-3 rounded-sm" style="background: linear-gradient(90deg, #4f8a5b, #4aa3c7);"></div>
                <p class="text-soil-brown text-sm max-w-md mx-auto">Saran praktis untuk memaksimalkan hasil panen sayuran Anda</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Tip 1 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4">☀️</div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Atur Sinar Matahari</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Pastikan tanaman mendapat sinar matahari 6-8 jam per hari. Gunakan naungan jaring jika intensitas matahari terlalu tinggi untuk sayuran berdaun lembut.</p>
                </div>
                <!-- Tip 2 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4">🌡️</div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Kontrol Suhu & Kelembapan</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Sayuran tropis tumbuh optimal pada suhu 20-30°C. Gunakan mulsa untuk menjaga kelembapan tanah dan hindari stres pada tanaman saat suhu ekstrem.</p>
                </div>
                <!-- Tip 3 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4">🔄</div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Rotasi Tanaman</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Lakukan rotasi tanaman setiap 2-3 musim tanam untuk mencegah penumpukan hama dan penyakit di tanah serta menjaga kesuburan tanah secara alami.</p>
                </div>
                <!-- Tip 4 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4">📝</div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Catat Aktivitas Budidaya</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Buat catatan harian tentang jadwal tanam, pemupukan, penyiraman, dan pengamatan hama. Data ini membantu evaluasi dan perbaikan teknik budidaya.</p>
                </div>
                <!-- Tip 5 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4">🐞</div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Pemanfaatan Musuh Alami</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Promosikan predator alami seperti kumbang koksi (ladybug) dan laba-laba untuk mengendalikan hama. Tanam bunga marigold sebagai trap crop.</p>
                </div>
                <!-- Tip 6 -->
                <div class="bg-white rounded-2xl p-8 border-l-[4px] border-l-leaf-green shadow-[0_2px_12px_rgba(31,61,43,0.06)] transition-all duration-300 hover:border-l-water-blue hover:-translate-y-1 hover:shadow-[0_6px_24px_rgba(31,61,43,0.1)]">
                    <div class="text-3xl mb-4"></div>
                    <h3 class="text-lg font-semibold text-dark-green mb-2">Budidaya Organik</h3>
                    <p class="text-sm text-[#5a6b5e] leading-relaxed">Utamakan penggunaan pupuk dan pestisida organik untuk menghasilkan sayuran yang sehat, aman dikonsumsi, dan ramah lingkungan tanpa bahan kimia sintetis.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative rounded-3xl p-8 md:p-12 text-center mb-16 overflow-hidden fade-up" style="background: linear-gradient(135deg, #8ccf9b, #4aa3c7);">
            <div class="absolute top-[-50%] right-[-20%] w-[400px] h-[400px] bg-white/10 rounded-full"></div>
            <h2 class="text-2xl md:text-[1.75rem] font-bold text-dark-green mb-3 relative">Mulai Budidaya Sayuran Anda Sekarang!</h2>
            <p class="text-forest-green mb-6 text-sm relative max-w-md mx-auto">Dengan teknik yang tepat, Anda bisa menghasilkan sayuran segar berkualitas dari kebun sendiri.</p>
            <a href="../tanaman.HTML" class="inline-flex items-center gap-2 bg-dark-green text-white px-8 py-3.5 rounded-full no-underline font-semibold text-base transition-all duration-300 hover:bg-forest-green hover:scale-105 hover:shadow-[0_4px_15px_rgba(31,61,43,0.3)] relative">
                Lihat Jenis Tanaman →
            </a>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-dark-green text-white/70 py-8 px-4 text-center">
        <div class="flex flex-wrap justify-center gap-8 mb-4">
            <a href="mainMenu.php" class="text-light-green no-underline text-sm transition-colors duration-300 hover:text-white">Beranda</a>
            <a href="budidaya.php" class="text-light-green no-underline text-sm transition-colors duration-300 hover:text-white">Budidaya</a>
            <a href="../tanaman.HTML" class="text-light-green no-underline text-sm transition-colors duration-300 hover:text-white">Jenis Tanaman</a>
            <a href="../analisis.HTML" class="text-light-green no-underline text-sm transition-colors duration-300 hover:text-white">Analisis</a>
        </div>
        <p class="text-sm">&copy; 2024 <a href="mainMenu.php" class="text-light-green no-underline">DI-GADHU</a> — Sistem Informasi Budidaya Pertanian</p>
    </footer>

    <script>
        function toggleNav() {
            var mobileMenu = document.getElementById('mobileMenu');
            var bar1 = document.getElementById('bar1');
            var bar2 = document.getElementById('bar2');
            var bar3 = document.getElementById('bar3');

            mobileMenu.classList.toggle('hidden');

            if (!mobileMenu.classList.contains('hidden')) {
                bar1.style.transform = 'rotate(45deg) translate(5px, 5px)';
                bar2.style.opacity = '0';
                bar3.style.transform = 'rotate(-45deg) translate(5px, -5px)';
            } else {
                bar1.style.transform = 'none';
                bar2.style.opacity = '1';
                bar3.style.transform = 'none';
            }
        }

        document.querySelectorAll('#mobileMenu a').forEach(function(link) {
            link.addEventListener('click', function() {
                var mobileMenu = document.getElementById('mobileMenu');
                mobileMenu.classList.add('hidden');
                var bar1 = document.getElementById('bar1');
                var bar2 = document.getElementById('bar2');
                var bar3 = document.getElementById('bar3');
                bar1.style.transform = 'none';
                bar2.style.opacity = '1';
                bar3.style.transform = 'none';
            });
        });

        // Scroll animation observer
        var observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-up').forEach(function(el) {
            observer.observe(el);
        });

        // Navbar scroll effect
        var lastScroll = 0;
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('navbar');
            var currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                navbar.style.boxShadow = '0 4px 20px rgba(31, 61, 43, 0.4)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(31, 61, 43, 0.3)';
            }

            lastScroll = currentScroll;
        });
    </script>

</body>
</html>

