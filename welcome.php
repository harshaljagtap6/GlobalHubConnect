<?php $username = $_GET['username'];?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>GHC -Index</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,600;1,700&family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
<style>
.container_acc{
    margin-top: 20vh;
    width:90vw;
    display:flex;
    justify-content: center;
    gap:40px;
    flex-wrap: wrap;
}
.card_acc:hover{
     transform: scale(1.02);
}
.welcome_dp{
    display: flex;
    width:100vw;
    height: 100vh;
    align-items: center;
    justify-content: center;
}
.greet-text{
    font-size: 35px;
    display: block;
    position: absolute;
    margin-top: 80vh;
    margin-left:38.5vw;
}
</style>

</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="header d-flex align-items-center fixed-top" style="background-color: rgba(14, 29, 52, 0.9);">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

      <a href="index.html" class="logo d-flex align-items-center">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <img src="assets/img/logo.png" alt="">
        <h1><span style="color:green;">G</span>lobal<span style="color:brown;">H</span>ub<span
            style="color:blue;">C</span>onnect</h1>
      </a>

      <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
      <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a href="index.html" class="active">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="services.html">Services</a></li>
          <li><a href="pricing.html">Pricing</a></li>
          <li><a href="contact.html">Contact</a></li>
          <li><a class="get-a-quote" href="signup.html">SignUp</a></li>
          <li><a class="get-a-quote" href="login.html">Login</a></li>
        </ul>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->
  <!-- End Header -->
  <div class="greet-text">
  <?php echo "Welcome, ", $username, "!!"; ?>
      </div>
  <div class="welcome_dp">
  <?php  
    echo '<img src="assets/img/team/dhanesh-P.jpeg" style="width:415px; height:400px; object-fit: cover; object-position: top center;" class="img-fluid" alt="">';
  ?>
  </div>

  <!-- container containing accomodation cards -->
  
  
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top: 15px;" >
    
  <div class="container" >
      <div class="row gy-4">
        <div class="col-lg-5 col-md-12 footer-info">
          <a href="index.html" class="logo d-flex align-items-center">
            <span>Socials</span>
          </a>
          <div class="social-links d-flex mt-4">
            <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
            <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
            <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
  
        <div class="col-lg-3 col-6 footer-links">
          <h4>Useful Links</h4>
          <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="about.html">About us</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="#">Terms of service</a></li>
            <li><a href="#">Privacy policy</a></li>
          </ul>
        </div>
  
        <div class="col-lg-4 col-6 footer-links">
          <h4>Our Services</h4>
          <ul>
            <li><a href="#">Accomodation</a></li>
            <li><a href="#">Jobs @ Abroad</a></li>
            <li><a href="#">Community Search</a></li>
            <li><a href="#">Community Events</a></li>
            <li><a href="#">Community Pages</a></li>
            <li><a href="#">Recommendations</a></li>
          </ul>
        </div>
      </div>
    </div>
  
    <div class="container mt-4">
      <div class="copyright">
        &copy; Copyright <strong><span>GlobalHubConnect</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <strong>GlobalHubConnect</strong> team
      </div>
    </div>
  
  </footer><!-- End Footer -->
  <!-- End Footer -->
  
 
  
  </body>
  </html>


<?php ?>