// Toggle Dark Mode
document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.createElement("button");
    darkModeToggle.textContent = "Dark Mode";
    darkModeToggle.classList.add("dark-mode-toggle");
    document.body.prepend(darkModeToggle);

    darkModeToggle.addEventListener("click", function () {
        document.body.classList.toggle("dark-mode");
        document.querySelectorAll('.navbar, .banner, .kartu-produk, .footer, .keranjang-table, .ringkasan-order').forEach((el) => {
            el.classList.toggle("dark-mode");
        });
    });
});

// Live Search
document.getElementById("input-pencarian").addEventListener("input", function () {
    const query = this.value.toLowerCase();
    const produk = document.querySelectorAll(".kartu-produk");

    produk.forEach(item => {
        // Ambil teks dari elemen <h3> yang menyimpan nama produk
        const namaProduk = item.querySelector("h3").textContent.toLowerCase();

        // Sesuaikan visibilitas item berdasarkan query pencarian
        if (namaProduk.includes(query)) {
            item.style.visibility = "visible";
            item.style.opacity = "1"; // Jika ada animasi transisi
        } else {
            item.style.visibility = "hidden";
            item.style.opacity = "0";
        }
    });
});



// Hamburger Menu Toggle
document.getElementById("hamburger-menu").addEventListener("click", function () {
    document.querySelector(".navbar .link-nav").classList.toggle("active");
});
