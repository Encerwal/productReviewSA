<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Sentiment Analysis</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
  
    <!-- Favicons -->
    <!-- WEBPAGE ICON -->
    <link href="assets/img/logo.png" rel="icon">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  
    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Vendor CSS icons Files -->
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">
  
  </head>
  
  
<body class="index-page">
  
    <header id="header" class="header d-flex align-items-center fixed-top">
      <div class="container-fluid container-xl position-relative d-flex align-items-center">
  
        <a href="index.php" class="logo d-flex align-items-center me-auto">
          <!-- Uncomment the line below if you also wish to use an image logo -->
          <img src="assets/img/logo.png" alt="">
          <h1 class="sitename">EmotiCart</h1>
        </a>
  
        <!-- Navigation Menu -->
        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="index.php#hero" class="active">Home<br></a></li>
            <li><a href="index.php#about">About</a></li>
            <li class="dropdown"><a href="index.php#values"><span>Services</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
              <ul>
                <li><a href="single_input.php">Single Input</a></li>
                <li><a href="upload.php">CSV File</a></li>
              </ul>
            </li>
            <li><a href="index.php#team">Team</a></li>
          </ul>
            <div id="menuToggle">
              <input type="checkbox" id="menuCheckbox" style="position:absolute;width:50px;height:50px;transform: translate(-50%, -50%);"/>
                <span></span>
                <span></span> 
                <span></span>
                
              <!-- Overlay -->
              <div id="overlay"></div>
              <!-- Navigation Menu (MOBILE) -->
              <ul id="menu">
                <li><a href="index.php#hero">Home</a></li>
                <li><a href="index.php#about">About</a></li>
                <li> <a href="" id="servicesToggle">Services  <i class="bi bi-chevron-down toggle-dropdown"></i></a></li>
                  <div id="servicesDropdown" style="display: none;">
                      <li><a href="single_input.php"><p style ="margin-bottom:-5px;">&nbsp;&nbsp;&nbsp;Single Input</p></a></li>
                      <li><a href="upload.php"><p style ="margin-bottom:-5px;">&nbsp;&nbsp;&nbsp;CSV File</p></a></li>
                  </div>
                
                <li><a href="index.php#team">Team</a></li>
              </ul>
          </div>
        </nav>
  
        <a class="btn-getstarted flex-md-shrink-0" href="index.php#values">Get Started</a>
  
      </div>
  
    </header>