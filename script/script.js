window.addEventListener("DOMContentLoaded", () => {
    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll("#nav-links a");

    navLinks.forEach(link => {
        if (link.getAttribute("href") === currentLocation.split("/").pop()) {
            link.classList.add("active");
        }
    });

    const CmdMenu = document.getElementById("CmdMenu");
    const navlinks = document.getElementById("nav-links");
    let isMenuOpen = false;

    CmdMenu.addEventListener('click', function() {
        if (navlinks.style.display === 'none' || navlinks.style.display === '') {
            navlinks.style.display = 'block';
            isMenuOpen = true;
        } else {
            navlinks.style.display = 'none';
            isMenuOpen = false;
        }
    });

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            navlinks.style.display = 'flex';
            isMenuOpen = false;
        } else {
            if (isMenuOpen) {
                navlinks.style.display = 'block';
            } else {
                navlinks.style.display = 'none';
            }
        }
    });


    window.addEventListener("scroll", () => {
        const scrollY = window.scrollY;
        const triggerHeight = 400;
        if (scrollY > triggerHeight) {
            document.querySelector(".gauche").style.transform = "translateX(-100%)";
            document.querySelector(".droite").style.transform = "translateX(100%)";
        } else {
            document.querySelector(".gauche").style.transform = "translateX(0)";
            document.querySelector(".droite").style.transform = "translateX(0)";
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const carrouselContainer = document.querySelector(".carrousel-container");
    const comments = document.querySelectorAll(".comment");
    const prevButton = document.querySelector(".prev");
    const nextButton = document.querySelector(".next");
    let currentIndex = 0;

    function updateCarrousel() {
        const offset = -currentIndex * 100;
        carrouselContainer.style.transform = `translateX(${offset}%)`;
    }

    prevButton.addEventListener("click", () => {
        currentIndex = (currentIndex - 1 + comments.length) % comments.length;
        updateCarrousel();
    });

    nextButton.addEventListener("click", () => {
        currentIndex = (currentIndex + 1) % comments.length;
        updateCarrousel();
    });
});

