document.addEventListener("DOMContentLoaded", function() {
    const flashAlert = document.querySelector(".flash-alert");

    if (flashAlert.classList.contains("show-alert")) {
        setTimeout(() => {
            flashAlert.style.opacity = "1";
            flashAlert.style.transform = "translateY(0)";
        }, 500);
        setTimeout(() => {
            flashAlert.classList.remove("show-alert");
            flashAlert.style.opacity = "0";
            flashAlert.style.transform = "translateY(-20rem)";
        }, 6000);
    } 
});