document.addEventListener("DOMContentLoaded", function () {

    const btn = document.getElementById("fp-show-more");
    if (!btn) return;

    btn.addEventListener("click", function () {

        const hidden = document.querySelectorAll(".fp-hidden");

        hidden.forEach((el, index) => {
            if (index < 3) {
                el.classList.remove("fp-hidden");
            }
        });

        // If no more hidden → hide button
        if (document.querySelectorAll(".fp-hidden").length === 0) {
            btn.style.display = "none";
        }
    });

});