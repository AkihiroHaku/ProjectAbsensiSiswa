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

document.addEventListener("DOMContentLoaded", function () {
    const currentPage = window.location.pathname.split("/").pop();

    document.querySelectorAll(".menu a").forEach(link => {
        const page = link.getAttribute("href").split("/").pop();

        if (page === currentPage) {
            link.classList.add("active");
        }
    });
});


