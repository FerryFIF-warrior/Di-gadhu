// adminDash.js - Versi terbaru dengan debug
async function apiCall(action, method = 'GET', body = null) {
    const options = { method, headers: { 'Content-Type': 'application/json' }, credentials: 'include'};
    if (body) options.body = JSON.stringify(body);
    const response = await fetch(`/Di-gadhu/api/admin_api.php?action=${action}`, options);

    if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.error | 'Request gagal');
    }
    return response.json();
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Modal form (sama seperti sebelumnya)
function showModal(title, fields, onSave) {
    let modal = document.getElementById('dynamicModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'dynamicModal';
        modal.className = 'modal';
        document.body.appendChild(modal);
    }
    let html = `<div class="modal-content"><h3><i class="fas fa-edit"></i> ${title}</h3>`;
    fields.forEach(field => {
        html += `<label>${field.label}</label>`;
        if (field.type === 'select') {
            html += `<select id="modal_${field.name}">`;
            field.options.forEach(opt => {
                const selected = (opt.value === field.value) ? 'selected' : '';
                html += `<option value="${opt.value}" ${selected}>${opt.label}</option>`;
            });
            html += `</select>`;
        } else if (field.type === 'textarea') {
            html += `<textarea id="modal_${field.name}" rows="3">${field.value || ''}</textarea>`;
        } else {
            html += `<input type="${field.type || 'text'}" id="modal_${field.name}" value="${field.value || ''}" placeholder="${field.placeholder || ''}">`;
        }
    });
    html += `<div class="modal-buttons"><button class="admin-btn" id="modalSaveBtn">Simpan</button><button class="admin-btn admin-btn-danger" id="modalCancelBtn">Batal</button></div></div>`;
    modal.innerHTML = html;
    modal.style.display = 'flex';
    document.getElementById('modalSaveBtn').onclick = () => {
        const data = {};
        fields.forEach(field => data[field.name] = document.getElementById(`modal_${field.name}`).value);
        modal.style.display = 'none';
        onSave(data);
    };
    document.getElementById('modalCancelBtn').onclick = () => modal.style.display = 'none';
}

async function loadPage(page) {
    const content = document.getElementById("contentArea");
    if (page === "dashboard") {
        const [tanaman, users, cuaca] = await Promise.all([
            apiCall('tanaman'),
            apiCall('user'),
            apiCall('cuaca')
        ]);
        content.innerHTML = `<div class="admin-card"><h2><i class="fas fa-chalkboard-user"></i> Dashboard</h2>
            <div style="display:flex;gap:20px;margin-top:20px;">
                <div class="admin-card" style="flex:1;text-align:center;"><h3>${tanaman.length}</h3><p>Jenis Tanaman</p></div>
                <div class="admin-card" style="flex:1;text-align:center;"><h3>${users.length}</h3><p>Total Pengguna</p></div>
                <div class="admin-card" style="flex:1;text-align:center;"><h3>${cuaca.length}</h3><p>Data Cuaca</p></div>
            </div></div>`;
    } else if (page === "tanaman") await renderTanaman();
    else if (page === "cuaca") await renderCuaca();
    else if (page === "user") await renderUser();
    else if (page === "statistik") content.innerHTML = `<div class="admin-card"><h2><i class="fas fa-chart-line"></i> Statistik</h2><p>Total kunjungan: 1.234 (simulasi)</p></div>`;
}

async function renderTanaman() {
    try {
        const data = await apiCall('tanaman');
        let rows = '';
        data.forEach(t => {
            rows += `<tr><td>${t.id}</td><td>${escapeHtml(t.nama)}</td><td>${escapeHtml(t.musim)}</td><td>${escapeHtml(t.suhu)}</td>
            <td><button class="admin-btn" onclick="editTanaman(${t.id})">Edit</button> <button class="admin-btn admin-btn-danger" onclick="hapusTanaman(${t.id})">Hapus</button></td></tr>`;
        });
        document.getElementById("contentArea").innerHTML = `<div class="admin-card"><h2><i class="fas fa-seedling"></i> Data Tanaman</h2>
            <button class="admin-btn" onclick="tambahTanaman()">+ Tambah</button>
            <table class="admin-table"><thead><tr><th>ID</th><th>Nama</th><th>Musim</th><th>Suhu</th><th>Aksi</th></tr></thead><tbody>${rows}</tbody></table></div>`;
        } catch(err) {
            console.error('Gagal lload tanaman:', err);
            document.getElementById("contentArea").innerHTML = `<div class="admin-card">Error: ${err.message}</div>`;
        }
}

function tambahTanaman() {
    showModal('Tambah Tanaman', [
        { name: 'nama', label: 'Nama Tanaman', type: 'text' },
        { name: 'musim', label: 'Musim Tanam', type: 'text' },
        { name: 'suhu', label: 'Suhu Ideal', type: 'text' }
    ], async (data) => { await apiCall('tambah_tanaman', 'POST', data); renderTanaman(); });
}
async function editTanaman(id) {
    const all = await apiCall('tanaman');
    const t = all.find(i => i.id == id);
    showModal('Edit Tanaman', [
        { name: 'nama', label: 'Nama Tanaman', type: 'text', value: t.nama },
        { name: 'musim', label: 'Musim Tanam', type: 'text', value: t.musim },
        { name: 'suhu', label: 'Suhu Ideal', type: 'text', value: t.suhu }
    ], async (data) => { await apiCall('edit_tanaman', 'POST', { id, ...data }); renderTanaman(); });
}
async function hapusTanaman(id) { if (confirm('Hapus?')) { await apiCall('hapus_tanaman', 'POST', { id }); renderTanaman(); } }

// Cuaca 
async function renderCuaca() {
    try {
        const data = await apiCall('cuaca');
        let rows = '';
        data.forEach(c => {
            rows += `<tr><td>${c.id}</td><td>${escapeHtml(c.lokasi)}</td><td>${c.suhu}°C</td><td>${c.kelembaban}%</td><td>${c.curah_hujan}</td>
            <td><button class="admin-btn" onclick="editCuaca(${c.id})">Edit</button> <button class="admin-btn admin-btn-danger" onclick="hapusCuaca(${c.id})">Hapus</button></td></tr>`;
        });
        document.getElementById("contentArea").innerHTML = `<div class="admin-card"><h2><i class="fas fa-cloud-sun-rain"></i> Data Cuaca</h2>
            <button class="admin-btn" onclick="tambahCuaca()">+ Tambah</button>
            <table class="admin-table"><thead><tr><th>ID</th><th>Lokasi</th><th>Suhu</th><th>Kelembaban</th><th>Curah Hujan</th><th>Aksi</th></tr></thead><tbody>${rows}</tbody></table></div>`;
    } catch(err) {
        console.error('Gagal load Cuaca:', err);
        document.getElementById("contentArea").innerHTML = `<div class="admin-card">Error: ${err.message}</div>`; 
    }
}
function tambahCuaca() {
    showModal('Tambah Cuaca', [
        { name: 'lokasi', label: 'Lokasi', type: 'text' },
        { name: 'suhu', label: 'Suhu (°C)', type: 'number' },
        { name: 'kelembaban', label: 'Kelembaban (%)', type: 'number' },
        { name: 'curah_hujan', label: 'Curah Hujan', type: 'select', options: [{ value: 'rendah', label: 'Rendah' }, { value: 'sedang', label: 'Sedang' }, { value: 'tinggi', label: 'Tinggi' }] }
    ], async (data) => { await apiCall('tambah_cuaca', 'POST', data); renderCuaca(); });
}
async function editCuaca(id) {
    const all = await apiCall('cuaca');
    const c = all.find(i => i.id == id);
    showModal('Edit Cuaca', [
        { name: 'lokasi', label: 'Lokasi', type: 'text', value: c.lokasi },
        { name: 'suhu', label: 'Suhu (°C)', type: 'number', value: c.suhu },
        { name: 'kelembaban', label: 'Kelembaban (%)', type: 'number', value: c.kelembaban },
        { name: 'curah_hujan', label: 'Curah Hujan', type: 'select', options: [{ value: 'rendah', label: 'Rendah' }, { value: 'sedang', label: 'Sedang' }, { value: 'tinggi', label: 'Tinggi' }], value: c.curah_hujan }
    ], async (data) => { await apiCall('edit_cuaca', 'POST', { id, ...data }); renderCuaca(); });
}
async function hapusCuaca(id) { if (confirm('Hapus?')) { await apiCall('hapus_cuaca', 'POST', { id }); renderCuaca(); } }

// User
async function renderUser() {
    try {
        console.log("Memanggil API user...");
        const data = await apiCall('user');
        console.log("Data user dari API:", data);
        let rows = '';
        if (data.length === 0) {
            rows = '<tr><td colspan="5">Tidak ada user biasa.</td></tr>';
        } else {
            data.forEach(u => {
                rows += `<tr>
                    <td>${u.id}</td>
                    <td>${escapeHtml(u.username)}</td>
                    <td>${escapeHtml(u.email)}</td>
                    <td>${u.role}</td>
                    <td>
                        <button class="admin-btn" onclick="editUser(${u.id})">Edit</button>
                        <button class="admin-btn admin-btn-danger" onclick="hapusUser(${u.id})">Hapus</button>
                    </td>
                </tr>`;
            });
        }
        document.getElementById("contentArea").innerHTML = `
            <div class="admin-card">
                <h2><i class="fas fa-users"></i> Manajemen User</h2>
                <button class="admin-btn" onclick="tambahUser()">+ Tambah User</button>
                <table class="admin-table">
                    <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
                    <tbody>${rows}</tbody>
                </table>
            </div>
        `;
    } catch(err) {
        console.error('Gagal load data User:', err);
        document.getElementById("contentArea").innerHTML = `<div class="admin-card">Error: ${err.message}</div>`;
    }
}

function tambahUser() {
    showModal('Tambah User', [
        { name: 'username', label: 'Username', type: 'text' },
        { name: 'email', label: 'Email', type: 'email' },
        { name: 'role', label: 'Role', type: 'select', options: [{ value: 'user', label: 'User' }, { value: 'admin', label: 'Admin' }] }
    ], async (data) => {
        await apiCall('tambah_user', 'POST', data);
        renderUser();
    });
}

async function editUser(id) {
    const all = await apiCall('user');
    const u = all.find(i => i.id == id);
    if (!u) return;
    showModal('Edit User', [
        { name: 'username', label: 'Username', type: 'text', value: u.username },
        { name: 'email', label: 'Email', type: 'email', value: u.email },
        { name: 'role', label: 'Role', type: 'select', options: [{ value: 'user', label: 'User' }, { value: 'admin', label: 'Admin' }], value: u.role }
    ], async (data) => {
        await apiCall('edit_user', 'POST', { id, ...data });
        renderUser();
    });
}

async function hapusUser(id) {
    if (confirm('Hapus user ini?')) {
        const res = await fetch(`api/admin_api.php?action=hapus_user`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id }),
            credentials: 'include'
        });
        if (res.ok) renderUser();
        else alert('Gagal hapus user');
    }
}

document.addEventListener("DOMContentLoaded", () => loadPage('dashboard'));