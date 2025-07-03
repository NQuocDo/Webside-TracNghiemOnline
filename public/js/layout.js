function anHienMenu() {
    const menu = document.getElementById("navMenu");
    const headerMobile = document.querySelector(".header_mobile");
    const headerOverlay= document.querySelector('.header_overlay')
    if (headerMobile.style.display === "none") {
        headerMobile.style.display = "block";
        headerOverlay.style.display="block";
    } else {
        headerMobile.style.display = "none";
        headerOverlay.style.display="none";
    }
}
