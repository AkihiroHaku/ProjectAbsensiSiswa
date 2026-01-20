<?php
session_start();
require_once "../config/database.php";

$active_tab = 'absensi';
include "layout_header.php";

// --- LOGIC 1: PROSES SIMPAN DATA (Jika Tombol Simpan Ditekan) ---
if (isset($_POST['simpan_absen'])) {
    $tgl_absen = $_POST['tanggal'];
    $id_kelas  = $_POST['kelas_id_hidden']; // Ambil ID kelas dari input hidden
    
    // Loop data yang dikirim
    if (isset($_POST['status']) && count($_POST['status']) > 0) {
        $jumlah_sukses = 0;
        
        foreach ($_POST['status'] as $nis_siswa => $nilai_status) {
            // Kita butuh data nama & jurusan siswa tersebut agar tersimpan lengkap
            // Ambil dari input hidden yang kita selipkan di tabel nanti
            $nama_siswa = $_POST['nama_siswa'][$nis_siswa];
            $jurusan    = $_POST['jurusan'][$nis_siswa];
            $tingkat    = $_POST['tingkat'][$nis_siswa];

            // Cek dulu: Apakah siswa ini SUDAH diabsen pada tanggal & kelas tersebut?
            $cek = mysqli_query($conn, "SELECT id_absensi FROM absensi WHERE nis='$nis_siswa' AND tanggal='$tgl_absen'");
            
            if (mysqli_num_rows($cek) > 0) {
                // Jika sudah ada, UPDATE statusnya
                $query_simpan = "UPDATE absensi SET status='$nilai_status' WHERE nis='$nis_siswa' AND tanggal='$tgl_absen'";
            } else {
                // Jika belum ada, INSERT baru
                $query_simpan = "INSERT INTO absensi (nis, nama, id_kelas, jurusan, tingkat, tanggal, status) 
                                 VALUES ('$nis_siswa', '$nama_siswa', '$id_kelas', '$jurusan', '$tingkat', '$tgl_absen', '$nilai_status')";
            }
            
            if (mysqli_query($conn, $query_simpan)) {
                $jumlah_sukses++;
            }
        }
        
        // Refresh halaman dengan pesan sukses
        echo "<script>
            alert('Berhasil menyimpan data absensi untuk $jumlah_sukses siswa!');
            window.location.href='absensi.php?kelas_id=$id_kelas';
        </script>";
    }
}

// --- LOGIC 2: PERSIAPAN TAMPILAN ---
$kelas_id = isset($_GET['kelas_id']) ? $_GET['kelas_id'] : '';
$tgl_hari_ini = date('Y-m-d');
?>

<div class="card">
    <h2 class="card-title"><i class="fas fa-calendar-day"></i> Absensi Harian</h2>
    
    <form method="GET" action="" style="margin-bottom: 20px; background: #f9f9f9; padding: 15px; border-radius: 8px;">
        <label style="font-weight:bold;">Pilih Kelas:</label>
        <select name="kelas_id" onchange="this.form.submit()" style="padding:8px; width: 200px; margin-left: 10px;">
            <option value="">-- Pilih Kelas --</option>
            <?php
            $q_kelas = mysqli_query($conn, "SELECT * FROM kelas ORDER BY tingkat ASC, jurusan ASC");
            while ($k = mysqli_fetch_assoc($q_kelas)) {
                $selected = ($kelas_id == $k['id_kelas']) ? 'selected' : '';
                echo "<option value='{$k['id_kelas']}' $selected>{$k['nama_kelas']}</option>";
            }
            ?>
        </select>
    </form>

    <?php if ($kelas_id != ''): ?>
        <form method="POST" action="">
            <input type="hidden" name="kelas_id_hidden" value="<?= $kelas_id; ?>">
            
            <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <label style="font-weight: bold;">Tanggal Absen:</label>
                <input type="date" name="tanggal" value="<?= $tgl_hari_ini; ?>" required style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Siswa</th>
                            <th width="40%" style="text-align: center;">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil Detail Kelas untuk tahu Jurusan & Tingkat
                        $cek_k = mysqli_query($conn, "SELECT tingkat, jurusan FROM kelas WHERE id_kelas = '$kelas_id'");
                        $data_k = mysqli_fetch_assoc($cek_k);
                        
                        if ($data_k) {
                            $tingkat = $data_k['tingkat'];
                            $jurusan = $data_k['jurusan'];
                            $tabel_target = "siswa_kelas" . $tingkat;
                            
                            // Query Siswa
                            $q_siswa = mysqli_query($conn, "SELECT * FROM $tabel_target WHERE jurusan = '$jurusan' ORDER BY nama ASC");
                            
                            if (mysqli_num_rows($q_siswa) > 0) {
                                $no = 1;
                                while ($s = mysqli_fetch_assoc($q_siswa)) {
                                    $nis = $s['nis'];
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= $nis; ?></td>
                                        <td>
                                            <?= $s['nama']; ?>
                                            <input type="hidden" name="nama_siswa[<?= $nis; ?>]" value="<?= $s['nama']; ?>">
                                            <input type="hidden" name="jurusan[<?= $nis; ?>]" value="<?= $jurusan; ?>">
                                            <input type="hidden" name="tingkat[<?= $nis; ?>]" value="<?= $tingkat; ?>">
                                        </td>
                                        <td style="text-align: center;">
                                            <div class="attendance-options">
                                                <label style="margin-right: 10px; cursor: pointer;">
                                                    <input type="radio" name="status[<?= $nis; ?>]" value="H" checked> 
                                                    <span class="badge bg-green">Hadir</span>
                                                </label>
                                                <label style="margin-right: 10px; cursor: pointer;">
                                                    <input type="radio" name="status[<?= $nis; ?>]" value="S"> 
                                                    <span class="badge bg-yellow">Sakit</span>
                                                </label>
                                                <label style="margin-right: 10px; cursor: pointer;">
                                                    <input type="radio" name="status[<?= $nis; ?>]" value="I"> 
                                                    <span class="badge bg-blue">Izin</span>
                                                </label>
                                                <label style="cursor: pointer;">
                                                    <input type="radio" name="status[<?= $nis; ?>]" value="A"> 
                                                    <span class="badge bg-red">Alpha</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align:center; padding:20px;'>Tidak ada siswa ditemukan.</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 20px; text-align: right;">
                <button type="submit" name="simpan_absen" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Absensi
                </button>
            </div>
        </form>

    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-chalkboard-teacher" style="font-size: 3rem; color: #ddd; margin-bottom: 10px;"></i>
            <p>Silakan pilih <b>Kelas</b> terlebih dahulu untuk mulai mengabsen.</p>
        </div>
    <?php endif; ?>
</div>

<?php include "layout_footer.php"; ?>