<?php
include('header.php');
?>

<!-- Main Content of the Home Page -->
  
  <!-- Hero Section -->
  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center">
            <h1 data-aos="fade-up">Unlock Insights From Your Product Reviews with Sentiment Analysis</h1>
            <p data-aos="fade-up" data-aos-delay="100">By automatically classifying opinions as positive and negative, this 
              analysis helps businesses understand customer sentiments, improve products, and enhance the overall shopping experience.
            </p>
            <div class="d-flex flex-column flex-md-row" data-aos="fade-up" data-aos-delay="200">
              <a href="#values" class="btn-get-started">Get Started <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out">
            <img src="assets/img/hero-img.png" class="img-fluid animated" alt="">
          </div>
        </div>
      </div>

    </section>

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container" >
        <div class="row gx-0">

          <div class="col-lg-6 d-flex flex-column justify-content-center">
            <div class="content">
              <h3>Who We Are</h3>
              <h2>We are a dedicated team of students from Holy Angel University, focused on improving the online shopping experience through sentiment analysis of e-commerce product reviews.</h2>
              <p>
                Using the XLM-RoBERTa algorithm, we analyze and classify customer feedback to provide valuable insights, helping selers make good business decisions. Our mission is to bring transparency and trust to the digital marketplace.
              </p>
            </div>
          </div>
          <div class="col-lg-6 d-flex align-items-center">
            <img src="assets/img/alt-features.png" class="img-fluid" alt="" style="padding-left: 50px; padding-top: 50px; padding-right: 50px;">
          </div>
        </div>
      </div>
    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="values" class="values section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Services</h2>
        <p>Check our Services<br></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-lg-6">
            <div class="card">
              <a href="single_input.php">
                <img src="assets/img/values-1.png" class="img-fluid" alt="" >
              </a>
              <h3>Single Input</h3>
              <p>Quickly analyze the sentiment of individual product reviews by typing or pasting the text. Get instant, accurate sentiment feedback with our advanced tool.</p>
            </div>
          </div><!-- End Card Item -->

          <div class="col-lg-6" >
            <div class="card">
              <a href="upload.php">
                <img src="assets/img/values-2.png" class="img-fluid" alt="">
              </a>
              <h3>CSV File</h3>
              <p>Upload a CSV file to analyze sentiment across multiple reviews at once. Ideal for bulk processing, this service provides sentiment results for each review efficiently.</p>
            </div>
          </div><!-- End Card Item -->
        </div>
      </div>

    </section><!-- /Services Section -->

    <!-- Features Section -->
    <section id="features" class="features section">

      <!-- Section Title -->
      <div class="container section-title">
        <h2>Features</h2>
        <p>Our Features<br></p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">

          <div class="col-xl-6" >
            <img src="assets/img/features.png" class="img-fluid" alt="">
          </div>

          <div class="col-xl-6 d-flex">
            <div class="row align-self-center gy-4">

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Both English and Filipino</h3>
                </div>
              </div><!-- End Feature Item -->

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Bulk Sentiment Analysis</h3>
                </div>
              </div><!-- End Feature Item -->

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Easy Navigation</h3>
                </div>
              </div><!-- End Feature Item -->

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>User-Friendly Interface</h3>
                </div>
              </div><!-- End Feature Item -->

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Results Summary</h3>
                </div>
              </div><!-- End Feature Item -->

              <div class="col-md-6" >
                <div class="feature-box d-flex align-items-center">
                  <i class="bi bi-check"></i>
                  <h3>Export Results</h3>
                </div>
              </div><!-- End Feature Item -->
            </div>
          </div>
        </div>
      </div>
    </section><!-- /Features Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section">

      <!-- Section Title -->
      <div class="container section-title" >
        <h2>F.A.Q</h2>
        <p>Frequently Asked Questions</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row">

          <div class="col-lg-6">

            <div class="faq-container">

              <div class="faq-item">
                <h3>What is the purpose of this website?</h3>
                <div class="faq-content">
                  <p>We provide sentiment analysis tools that analyze product reviews from e-commerce platforms. The analysis helps sellers understand customer opinions, improve their products, and enhance the overall shopping experience.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>How does the sentiment analysis work?</h3>
                <div class="faq-content">
                  <p>Our system uses the XLM-RoBERTa model to classify comments as positive or negative. It processes customer feedback in both English and Filipino to give you actionable insights.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>What types of comments can be analyzed?</h3>
                <div class="faq-content">
                  <p>You can analyze individual comments through our single input feature or upload a CSV file for bulk sentiment analysis of multiple reviews.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

          <div class="col-lg-6">

            <div class="faq-container">

              <div class="faq-item">
                <h3>What file format should I use for bulk analysis?</h3>
                <div class="faq-content">
                  <p>You should upload a CSV file when performing bulk sentiment analysis. This will provide visualizations or summaries to help you understand the overall sentiment trends in your data.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Why is sentiment analysis important for my business?</h3>
                <div class="faq-content">
                  <p>Understanding customer sentiment allows you to identify trends, improve products, and offer better service, ultimately helping you make more informed business decisions.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

              <div class="faq-item">
                <h3>Can I analyze reviews in different languages?</h3>
                <div class="faq-content">
                  <p>Yes, our tool supports both English and Filipino for sentiment analysis.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div><!-- End Faq item-->

            </div>

          </div><!-- End Faq Column-->

        </div>

      </div>

    </section><!-- /Faq Section -->


    <!-- Team Section -->
    <section id="team" class="team section">
      <div class="container section-title">
        <h2>Team</h2>
        <p>Our hard working team</p>
      </div>

      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/pat.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href="https://github.com/Encerwal" target="_blank" rel="noopener noreferrer"><i class="bi bi-github"></i></a>
                  <a href="https://www.linkedin.com/in/patrick-gomez-284218329/" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin" ></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Patrick Gomez</h4>
                <span>BS Computer Science</span>
                <p>Unlock the power of customer opinions and drive your business forward. Our sentiment analysis tools provide you with clear, actionable insights from product reviews, helping you make informed decisions and exceed customer expectations.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/sof.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href="https://github.com/SopyJoy" target="_blank" rel="noopener noreferrer"><i class="bi bi-github"></i></a>
                  <a href="https://www.linkedin.com/in/sofia-joy-yunun-7a7b38203/" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Sofia Yunun</h4>
                <span>BS Computer Science</span>
                <p>Don’t let valuable customer feedback go untapped. With our advanced sentiment analysis, turn reviews into strategic advantages. Identify trends, understand customer needs, and elevate your product offerings to new heights.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/rho.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href="https://github.com/Sariel15" target="_blank" rel="noopener noreferrer"><i class="bi bi-github"></i></a>
                  <a href="https://www.linkedin.com/in/rhonee-tolentino-93586827a/" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info">
                <h4>Rhonee Tolentino</h4>
                <span>BS Computer Science</span>
                <p>In the fast-paced e-commerce world, staying ahead is key. Our sentiment analysis services equip you with precise data to understand market sentiments, allowing you to adapt quickly and stay competitive in your industry.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/team/basti.jpg" class="img-fluid" alt="">
                <div class="social">
                  <a href="https://github.com/JSVisperas" target="_blank" rel="noopener noreferrer"><i class="bi bi-github"></i></a>
                  <a href="https://www.linkedin.com/in/jann-sebastian-visperas-b15086328/" target="_blank" rel="noopener noreferrer"><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
              <div class="member-info"> 
                <h4>Sebastian Visperas</h4>
                <span>BS Computer Science</span>
                <p>Embrace the full potential of customer reviews. Our platform transforms feedback into valuable insights, empowering you to refine your products, enhance customer satisfaction, and build a brand that resonates with your audience.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

<?php
include('footer.php');
?>