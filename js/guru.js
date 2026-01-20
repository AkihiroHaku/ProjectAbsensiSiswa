
// Data aplikasi
let students = JSON.parse(localStorage.getItem('students')) || [];
let attendance = JSON.parse(localStorage.getItem('attendance')) || [];

// Inisialisasi data contoh jika kosong
function initSampleData() {
    if (students.length === 0) {
        students = [
            { id: 1, nis: "2023001", name: "Ahmad Fauzi", class: "10", gender: "Laki-laki" },
            { id: 2, nis: "2023002", name: "Siti Nurhaliza", class: "10", gender: "Perempuan" },
            { id: 3, nis: "2023003", name: "Budi Santoso", class: "11", gender: "Laki-laki" },
            { id: 4, nis: "2023004", name: "Maya Indah", class: "11", gender: "Perempuan" },
            { id: 5, nis: "2023005", name: "Rizki Ramadhan", class: "12", gender: "Laki-laki" },
            { id: 6, nis: "2023006", name: "Dewi Lestari", class: "12", gender: "Perempuan" }
        ];
        localStorage.setItem('students', JSON.stringify(students));
    }

    if (attendance.length === 0) {
        const today = new Date().toISOString().split('T')[0];
        // Beri contoh data absensi untuk hari ini
        attendance = [
            { id: 1, studentId: 1, date: today, status: "hadir", time: "07:15" },
            { id: 2, studentId: 2, date: today, status: "izin", time: "08:30" },
            { id: 3, studentId: 3, date: today, status: "hadir", time: "07:20" },
        ];
        localStorage.setItem('attendance', JSON.stringify(attendance));
    }
}

// Fungsi untuk mendapatkan ID berikutnya
function getNextId(array) {
    return array.length > 0 ? Math.max(...array.map(item => item.id)) + 1 : 1;
}

// Format tanggal
function formatDate(dateString) {
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Format waktu
function formatTime(dateString) {
    return new Date(dateString).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

// Tampilkan tanggal saat ini
function displayCurrentDate() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('id-ID', options);
    document.getElementById('currentDate').textContent = formattedDate;
}

// Fungsi tab
function setupTabs() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const tabId = tab.getAttribute('data-tab');

            // Hapus kelas active dari semua tab dan konten
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Tambah kelas active ke tab yang diklik dan kontennya
            tab.classList.add('active');
            document.getElementById(tabId).classList.add('active');

            // Refresh data pada tab yang aktif
            if (tabId === 'dashboard') {
                displayDashboard();
            } else if (tabId === 'attendance') {
                displayAttendanceTable();
            } else if (tabId === 'students') {
                displayStudentsTable();
            } else if (tabId === 'reports') {
                setupReportFilters();
            }
        });
    });
}

// Dashboard
function displayDashboard() {
    const today = new Date().toISOString().split('T')[0];
    const todayAttendance = attendance.filter(a => a.date === today);

    // Hitung statistik
    const totalStudents = students.length;
    const hadirCount = todayAttendance.filter(a => a.status === 'hadir').length;
    const sakitCount = todayAttendance.filter(a => a.status === 'sakit').length;
    const izinCount = todayAttendance.filter(a => a.status === 'izin').length;
    const alphaCount = totalStudents - (hadirCount + sakitCount + izinCount);

    // Tampilkan statistik
    const statsContainer = document.getElementById('statsContainer');
    statsContainer.innerHTML = `
                <div class="stat-card hadir">
                    <div class="stat-value">${hadirCount}</div>
                    <div class="stat-label">Hadir</div>
                    <div style="font-size: 0.8rem; margin-top: 5px;">${totalStudents > 0 ? Math.round((hadirCount / totalStudents) * 100) : 0}%</div>
                </div>
                <div class="stat-card sakit">
                    <div class="stat-value">${sakitCount}</div>
                    <div class="stat-label">Sakit</div>
                    <div style="font-size: 0.8rem; margin-top: 5px;">${totalStudents > 0 ? Math.round((sakitCount / totalStudents) * 100) : 0}%</div>
                </div>
                <div class="stat-card izin">
                    <div class="stat-value">${izinCount}</div>
                    <div class="stat-label">Izin</div>
                    <div style="font-size: 0.8rem; margin-top: 5px;">${totalStudents > 0 ? Math.round((izinCount / totalStudents) * 100) : 0}%</div>
                </div>
                <div class="stat-card alpha">
                    <div class="stat-value">${alphaCount}</div>
                    <div class="stat-label">Alpha</div>
                    <div style="font-size: 0.8rem; margin-top: 5px;">${totalStudents > 0 ? Math.round((alphaCount / totalStudents) * 100) : 0}%</div>
                </div>
            `;

    // Tampilkan absensi terbaru
    const recentAttendanceTable = document.querySelector('#recentAttendance tbody');
    const noRecentData = document.getElementById('noRecentData');

    if (todayAttendance.length > 0) {
        noRecentData.style.display = 'none';
        recentAttendanceTable.innerHTML = '';

        todayAttendance.slice(0, 5).forEach(record => {
            const student = students.find(s => s.id === record.studentId);
            if (student) {
                const row = document.createElement('tr');
                row.innerHTML = `
                            <td>${student.name}</td>
                            <td>Kelas ${student.class}</td>
                            <td>${record.time}</td>
                            <td><span class="status-badge status-${record.status}">${record.status.toUpperCase()}</span></td>
                        `;
                recentAttendanceTable.appendChild(row);
            }
        });
    } else {
        noRecentData.style.display = 'block';
        recentAttendanceTable.innerHTML = '';
    }
}

// Tabel absensi harian
function displayAttendanceTable() {
    const today = new Date().toISOString().split('T')[0];
    const filterClass = document.getElementById('filterClass').value;
    const tableBody = document.getElementById('attendanceTableBody');
    const noStudents = document.getElementById('noStudents');

    // Filter siswa berdasarkan kelas
    let filteredStudents = students;
    if (filterClass !== 'all') {
        filteredStudents = students.filter(s => s.class === filterClass);
    }

    if (filteredStudents.length > 0) {
        noStudents.style.display = 'none';
        tableBody.innerHTML = '';

        filteredStudents.forEach((student, index) => {
            const todayRecord = attendance.find(a => a.studentId === student.id && a.date === today);

            const row = document.createElement('tr');
            row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${student.name}</td>
                        <td>Kelas ${student.class}</td>
                        <td>
                            <div class="attendance-buttons">
                                <button class="attendance-btn hadir ${todayRecord?.status === 'hadir' ? 'active' : ''}" 
                                        data-student-id="${student.id}" data-status="hadir">
                                    Hadir
                                </button>
                                <button class="attendance-btn sakit ${todayRecord?.status === 'sakit' ? 'active' : ''}" 
                                        data-student-id="${student.id}" data-status="sakit">
                                    Sakit
                                </button>
                                <button class="attendance-btn izin ${todayRecord?.status === 'izin' ? 'active' : ''}" 
                                        data-student-id="${student.id}" data-status="izin">
                                    Izin
                                </button>
                                <button class="attendance-btn alpha ${todayRecord?.status === 'alpha' ? 'active' : ''}" 
                                        data-student-id="${student.id}" data-status="alpha">
                                    Alpha
                                </button>
                            </div>
                        </td>
                    `;
            tableBody.appendChild(row);
        });

        // Tambah event listener untuk tombol absensi
        document.querySelectorAll('.attendance-btn').forEach(button => {
            button.addEventListener('click', function () {
                const studentId = parseInt(this.getAttribute('data-student-id'));
                const status = this.getAttribute('data-status');
                const now = new Date();
                const time = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

                // Hapus status active dari semua tombol di baris ini
                const parentRow = this.closest('tr');
                parentRow.querySelectorAll('.attendance-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Tambah status active ke tombol yang diklik
                this.classList.add('active');

                // Simpan atau update absensi
                const existingIndex = attendance.findIndex(a => a.studentId === studentId && a.date === today);

                if (existingIndex !== -1) {
                    attendance[existingIndex].status = status;
                    attendance[existingIndex].time = time;
                } else {
                    const newRecord = {
                        id: getNextId(attendance),
                        studentId: studentId,
                        date: today,
                        status: status,
                        time: time
                    };
                    attendance.push(newRecord);
                }

                localStorage.setItem('attendance', JSON.stringify(attendance));

                // Tampilkan notifikasi sukses
                showNotification(`Absensi berhasil dicatat: ${status.toUpperCase()}`, 'success');
            });
        });
    } else {
        noStudents.style.display = 'block';
        tableBody.innerHTML = '';
    }
}

// Tabel data siswa
function displayStudentsTable() {
    const tableBody = document.getElementById('studentsTableBody');
    const noStudentData = document.getElementById('noStudentData');

    if (students.length > 0) {
        noStudentData.style.display = 'none';
        tableBody.innerHTML = '';

        students.forEach((student, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${student.nis}</td>
                        <td>${student.name}</td>
                        <td>Kelas ${student.class}</td>
                        <td>${student.gender}</td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn edit" data-id="${student.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="action-btn delete" data-id="${student.id}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    `;
            tableBody.appendChild(row);
        });

        // Tambah event listener untuk tombol edit dan hapus
        document.querySelectorAll('.action-btn.edit').forEach(button => {
            button.addEventListener('click', function () {
                const studentId = parseInt(this.getAttribute('data-id'));
                editStudent(studentId);
            });
        });

        document.querySelectorAll('.action-btn.delete').forEach(button => {
            button.addEventListener('click', function () {
                const studentId = parseInt(this.getAttribute('data-id'));
                deleteStudent(studentId);
            });
        });
    } else {
        noStudentData.style.display = 'block';
        tableBody.innerHTML = '';
    }
}

// Form tambah siswa
function setupAddStudentForm() {
    const form = document.getElementById('addStudentForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = document.getElementById('studentName').value;
        const nis = document.getElementById('studentNis').value;
        const studentClass = document.getElementById('studentClass').value;
        const gender = document.getElementById('studentGender').value;

        // Validasi NIS unik
        if (students.some(s => s.nis === nis)) {
            showNotification('NIS sudah terdaftar. Gunakan NIS yang berbeda.', 'error');
            return;
        }

        const newStudent = {
            id: getNextId(students),
            nis: nis,
            name: name,
            class: studentClass,
            gender: gender
        };

        students.push(newStudent);
        localStorage.setItem('students', JSON.stringify(students));

        // Reset form
        form.reset();

        // Refresh tabel siswa
        displayStudentsTable();

        // Tampilkan notifikasi
        showNotification(`Siswa ${name} berhasil ditambahkan`, 'success');
    });
}

// Edit siswa
function editStudent(id) {
    const student = students.find(s => s.id === id);
    if (!student) return;

    // Isi form edit
    document.getElementById('editStudentId').value = student.id;
    document.getElementById('editStudentName').value = student.name;
    document.getElementById('editStudentNis').value = student.nis;
    document.getElementById('editStudentClass').value = student.class;
    document.getElementById('editStudentGender').value = student.gender;

    // Tampilkan modal
    document.getElementById('editStudentModal').classList.add('active');
}

// Hapus siswa
function deleteStudent(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data siswa ini?')) {
        // Hapus data siswa
        students = students.filter(s => s.id !== id);

        // Hapus data absensi terkait
        attendance = attendance.filter(a => a.studentId !== id);

        // Simpan ke localStorage
        localStorage.setItem('students', JSON.stringify(students));
        localStorage.setItem('attendance', JSON.stringify(attendance));

        // Refresh tampilan
        displayStudentsTable();
        displayDashboard();

        // Tampilkan notifikasi
        showNotification('Data siswa berhasil dihapus', 'success');
    }
}

// Setup modal edit siswa
function setupEditStudentModal() {
    const modal = document.getElementById('editStudentModal');
    const closeBtn = document.getElementById('closeEditModal');
    const cancelBtn = document.getElementById('cancelEdit');
    const form = document.getElementById('editStudentForm');

    // Tutup modal
    closeBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    cancelBtn.addEventListener('click', () => {
        modal.classList.remove('active');
    });

    // Tutup modal saat klik di luar konten
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // Submit form edit
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const id = parseInt(document.getElementById('editStudentId').value);
        const name = document.getElementById('editStudentName').value;
        const nis = document.getElementById('editStudentNis').value;
        const studentClass = document.getElementById('editStudentClass').value;
        const gender = document.getElementById('editStudentGender').value;

        // Validasi NIS unik (kecuali untuk siswa yang sama)
        if (students.some(s => s.nis === nis && s.id !== id)) {
            showNotification('NIS sudah terdaftar. Gunakan NIS yang berbeda.', 'error');
            return;
        }

        // Update data siswa
        const index = students.findIndex(s => s.id === id);
        if (index !== -1) {
            students[index] = {
                id: id,
                nis: nis,
                name: name,
                class: studentClass,
                gender: gender
            };

            localStorage.setItem('students', JSON.stringify(students));

            // Tutup modal
            modal.classList.remove('active');

            // Refresh tampilan
            displayStudentsTable();
            displayDashboard();

            // Tampilkan notifikasi
            showNotification(`Data siswa ${name} berhasil diperbarui`, 'success');
        }
    });
}

// Setup filter laporan
function setupReportFilters() {
    const monthSelect = document.getElementById('reportMonth');

    // Isi opsi bulan (12 bulan terakhir)
    const now = new Date();
    const currentYear = now.getFullYear();

    for (let i = 0; i < 12; i++) {
        const date = new Date(currentYear, now.getMonth() - i, 1);
        const monthName = date.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        const value = `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}`;

        const option = document.createElement('option');
        option.value = value;
        option.textContent = monthName;

        if (i === 0) {
            option.selected = true;
        }

        monthSelect.appendChild(option);
    }

    // Event listener untuk tombol generate laporan
    document.getElementById('generateReport').addEventListener('click', generateReport);
}

// Generate laporan
function generateReport() {
    const selectedClass = document.getElementById('reportClass').value;
    const selectedMonth = document.getElementById('reportMonth').value;
    const selectedStatus = document.getElementById('reportStatus').value;

    // Filter siswa berdasarkan kelas
    let filteredStudents = students;
    if (selectedClass !== 'all') {
        filteredStudents = students.filter(s => s.class === selectedClass);
    }

    // Filter absensi berdasarkan bulan
    const [year, month] = selectedMonth.split('-');
    const filteredAttendance = attendance.filter(a => {
        const attendanceDate = new Date(a.date);
        return attendanceDate.getFullYear() === parseInt(year) &&
            (attendanceDate.getMonth() + 1) === parseInt(month);
    });

    // Filter berdasarkan status jika dipilih
    let statusFilteredAttendance = filteredAttendance;
    if (selectedStatus !== 'all') {
        statusFilteredAttendance = filteredAttendance.filter(a => a.status === selectedStatus);
    }

    // Hitung statistik
    const totalStudents = filteredStudents.length;
    const hadirCount = filteredAttendance.filter(a => a.status === 'hadir').length;
    const sakitCount = filteredAttendance.filter(a => a.status === 'sakit').length;
    const izinCount = filteredAttendance.filter(a => a.status === 'izin').length;
    const alphaCount = filteredAttendance.filter(a => a.status === 'alpha').length;

    // Tampilkan hasil
    const reportResults = document.getElementById('reportResults');
    const noReportData = document.getElementById('noReportData');

    if (filteredAttendance.length > 0 || selectedStatus === 'all') {
        noReportData.style.display = 'none';

        reportResults.innerHTML = `
                    <h3 style="margin-bottom: 20px; color: var(--primary);">Laporan Absensi Bulan ${new Date(parseInt(year), parseInt(month) - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' })}</h3>
                    
                    <div class="stats">
                        <div class="stat-card hadir">
                            <div class="stat-value">${hadirCount}</div>
                            <div class="stat-label">Hadir</div>
                        </div>
                        <div class="stat-card sakit">
                            <div class="stat-value">${sakitCount}</div>
                            <div class="stat-label">Sakit</div>
                        </div>
                        <div class="stat-card izin">
                            <div class="stat-value">${izinCount}</div>
                            <div class="stat-label">Izin</div>
                        </div>
                        <div class="stat-card alpha">
                            <div class="stat-value">${alphaCount}</div>
                            <div class="stat-label">Alpha</div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px;">
                        <h4 style="margin-bottom: 15px; color: var(--dark);">Detail Absensi</h4>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${statusFilteredAttendance.slice(0, 20).map(record => {
            const student = students.find(s => s.id === record.studentId);
            if (!student) return '';
            return `
                                            <tr>
                                                <td>${formatDate(record.date)}</td>
                                                <td>${student.name}</td>
                                                <td>Kelas ${student.class}</td>
                                                <td><span class="status-badge status-${record.status}">${record.status.toUpperCase()}</span></td>
                                                <td>${record.time}</td>
                                            </tr>
                                        `;
        }).join('')}
                                </tbody>
                            </table>
                        </div>
                        ${statusFilteredAttendance.length > 20 ? `<p style="margin-top: 10px; color: var(--gray); font-size: 0.9rem;">Menampilkan 20 dari ${statusFilteredAttendance.length} data absensi</p>` : ''}
                    </div>
                `;
    } else {
        noReportData.style.display = 'block';
        reportResults.innerHTML = '';
    }
}

// Filter absensi berdasarkan kelas
function setupAttendanceFilter() {
    const filterSelect = document.getElementById('filterClass');

    filterSelect.addEventListener('change', () => {
        displayAttendanceTable();
    });
}

// Tampilkan notifikasi
function showNotification(message, type) {
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 1000;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                animation: slideIn 0.3s ease;
            `;

    if (type === 'success') {
        notification.style.backgroundColor = '#4cc9f0';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#f72585';
    } else {
        notification.style.backgroundColor = '#4361ee';
    }

    notification.textContent = message;

    document.body.appendChild(notification);

    // Hapus notifikasi setelah 3 detik
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);

    // Tambah style animasi
    const style = document.createElement('style');
    style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
    document.head.appendChild(style);
}

// Inisialisasi aplikasi
function initApp() {
    initSampleData();
    displayCurrentDate();
    setupTabs();
    setupAddStudentForm();
    setupEditStudentModal();
    setupAttendanceFilter();
    setupReportFilters();

    // Tampilkan dashboard pertama kali
    displayDashboard();

    // Event listener untuk filter kelas di tab absensi
    document.getElementById('filterClass').addEventListener('change', displayAttendanceTable);
}

// Saat halaman dimuat, cek apakah ada parameter 'kelas' di URL
// Jika ada, berarti user baru saja memfilter absensi, maka paksa buka tab 'attendance'
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('kelas')) {
    // Nonaktifkan tab default (dashboard)
    document.querySelector('.tab[data-tab="dashboard"]').classList.remove('active');
    document.getElementById('dashboard').classList.remove('active');

    // Aktifkan tab attendance
    document.querySelector('.tab[data-tab="attendance"]').classList.add('active');
    document.getElementById('attendance').classList.add('active');
}

// Jalankan aplikasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', initApp);