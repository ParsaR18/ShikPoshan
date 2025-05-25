(function () {
  let slideIndex = 1;
  const slides = document.getElementsByClassName("mySlides");
  const dots = document.getElementsByClassName("dot");
  let autoSlideTimeout;

  function showSlides(n) {
    if (!slides || slides.length === 0 || !dots || dots.length === 0) {
      return;
    }

    let targetSlideIndex = n;
    if (targetSlideIndex > slides.length) {
      targetSlideIndex = 1;
    }
    if (targetSlideIndex < 1) {
      targetSlideIndex = slides.length;
    }
    slideIndex = targetSlideIndex;

    for (let i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    for (let i = 0; i < dots.length; i++) {
      dots[i].classList.remove("active");
    }

    if (slides[slideIndex - 1]) {
      slides[slideIndex - 1].style.display = "block";
    }
    if (dots[slideIndex - 1]) {
      dots[slideIndex - 1].classList.add("active");
    }

    resetAutoSlide();
  }

  function plusSlides(n) {
    showSlides(slideIndex + n);
  }

  function currentSlide(n) {
    showSlides(n);
  }

  function autoAdvance() {
    plusSlides(1);
  }

  function resetAutoSlide() {
    clearTimeout(autoSlideTimeout);
    autoSlideTimeout = setTimeout(autoAdvance, 5000);
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (!slides || slides.length === 0) {
      return;
    }
    showSlides(slideIndex);

    const prevButton = document.getElementById("slidePrevBtn");
    const nextButton = document.getElementById("slideNextBtn");

    if (prevButton) {
      prevButton.addEventListener("click", function () {
        plusSlides(-1);
      });
    }
    if (nextButton) {
      nextButton.addEventListener("click", function () {
        plusSlides(1);
      });
    }

    Array.from(dots).forEach((dot, index) => {
      dot.addEventListener("click", function () {
        currentSlide(index + 1);
      });
    });

    const slideshowContainer = document.querySelector(".slideshow-container");
    if (slideshowContainer) {
      slideshowContainer.addEventListener("mouseenter", function () {
        clearTimeout(autoSlideTimeout);
      });
      slideshowContainer.addEventListener("mouseleave", resetAutoSlide);
    }
  });
})();
