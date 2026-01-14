document.addEventListener("DOMContentLoaded", function () {
    // Contoh data (nanti diganti dari database PHP)
    const totalSiswa = 30;
    const hadir = 24;
    const tidakHadir = totalSiswa - hadir;

    document.getElementById("totalSiswa").innerText = totalSiswa;
    document.getElementById("hadir").innerText = hadir;
    document.getElementById("tidakHadir").innerText = tidakHadir;
});

document.addEventListener("DOMContentLoaded", function () {
    const errorBox = document.getElementById("error-message");

    if (errorBox) {
        setTimeout(() => {
            errorBox.style.opacity = "0";
            errorBox.style.transition = "opacity 0.5s ease";
        }, 3000); // 3 detik

        setTimeout(() => {
            errorBox.style.display = "none";
        }, 3500);
    }
});
