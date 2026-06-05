<?php

require_once __DIR__ . '/../includes/functions.php';

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/style.css" />
    <title>Severos - Home</title>
  </head>
  <body>
    <?php include __DIR__ . '/../includes/navbar.php'; ?>
    <main>
      <div class="image-slider">
        <div class="slider-box">
          <img class="slide active" src="../assets/Home.jpeg" alt="Severos" />
          <img class="slide" src="../assets/Home.jpeg" alt="Severos" />
          <img class="slide" src="../assets/Home.jpeg" alt="Severos" />
          <div class="slider-dots">
            <span class="dot active" onclick="showSlide(0)"></span>
            <span class="dot" onclick="showSlide(1)"></span>
            <span class="dot" onclick="showSlide(2)"></span>
          </div>
        </div>
      </div>
      <div class="tagline">
        <p id="slide-text">
          "Severos Blackmarket is not just a store - it's a survival network."
        </p>
        <p>"Every weapon has a story. Every buyer, a purpose."</p>
      </div>
    </main>
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
      var slides = document.getElementsByClassName("slide");
      var dots = document.getElementsByClassName("dot");
      var slideText = document.getElementById("slide-text");
      var slideIndex = 0;
      var messages = [
        '"Severos Blackmarket is not just a store - it\'s a survival network."',
        '"Every weapon has a story. Every buyer, a purpose."',
        '"Only the strongest survive. Choose your weapons, enforce your dominance."',
      ];

      function showSlide(index) {
        if (index >= slides.length) {
          slideIndex = 0;
        } else if (index < 0) {
          slideIndex = slides.length - 1;
        } else {
          slideIndex = index;
        }

        for (var i = 0; i < slides.length; i++) {
          slides[i].className = "slide";
          dots[i].className = "dot";
        }
        slides[slideIndex].className = "slide active";
        dots[slideIndex].className = "dot active";
        slideText.innerHTML = messages[slideIndex];
      }

      function nextSlide() {
        showSlide(slideIndex + 1);
      }

      setInterval(nextSlide, 4000);
    </script>
  </body>
</html>
