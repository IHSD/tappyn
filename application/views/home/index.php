<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
<?php $this->load->view('templates/notification', array(
    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
<!-- Hero Banner -->
    <section class="hero-banner">
        <div class="row">
            <div class="medium-12">
                <div class="hero-content">
                    <h3>COMPANIES ARE LOOKING FOR PEOPLE LIKE YOU.</h3>
                    <h1>SEE WHAT PEOPLE AROUND <br> YOU ARE CREATING. </h1>
                   	<a class='btn btn-banner' href="<?php echo base_url().'contests' ?>">Current Contests</a>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
    </section>
    <!-- Contests
    <section class="contests">
        <div class="row">
            <div class="contest-box">
                <span class="price">$30</span>
                <h3><a href="#0">If there isn't a t-shirt for it, did it even happen?</a></h3>
                <h6 class="duration">Posted 36 Hours Ago - Remaining: 12 Hours</h6>
                <div class="contest-content">
                    <p>Sed accumsan vulputate viverra. Etiam eu ultrices mauris. Suspendisse consequat et diam ac facilisis. </p>
                </div>
                <div class="contest-info">
                    <em class="submissions">12 submissions</em>
                    <h5>University: <span>Emory University</span>  -  Company: <span>BidPress</span>  -  <span>Google</span></h5>
                </div>
            </div>
            <div class="contest-button">
               <a href="<?php echo base_url().'contests' ?>" class='btn btn-contest'>See Contests</a>
            </div>
        </div>
    </section> -->
    <!-- How it works -->
    <section class="how-it-works">
        <div class="row">
            <h2 class="title">How It Works</h2>
            <div class="features-box">
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/ico-find-contest.svg' ?>">
                    <h3>Find a contest</h3>
                    <p>Find a contest that you like. We've had over 75 contests launched. </p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-ad.svg' ?>">
                    <h3>Submit an ad</h3>
                    <p>Write an ad based on the type of contest. It only takes 90 seconds.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-money.svg' ?>">
                    <h3>Win &amp; make money</h3>
                    <p>Only 50 submissions are allowed, so you're odds of winning are great.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-contest.svg' ?>">
                    <h3>Find a new contest</h3>
                    <p>The more you submit, the greater chance you have of winning.  </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonials -->
    <section class="testimonials">
      <div class="row">
        <div class="columns medium-4">
          <div class="testimonial-img div-center medium-10">
            <img src="<?php echo base_url().'public/img/img-testi.jpg' ?>">
          </div>
        </div>
        <div class="columns medium-8">
          <div class="testi-content">
            <div class="testi-cell">
            <h4>"Because of you I'm now going into marketing as a career.<br>Yesterday I saw my ad on my own Facebook feed!"</h4>
            <ul>
              <li>5 Contests</li>
              <li> Worked 12 minutes</li>
              <li>$190 cash</li>
            </ul>
            <h5>Chris Bissel, Georgia Tech Class of 2016</h5>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Love Raw -->
    <section class="love-raw">
        <div class="row">
            <h2 class="title">Why you’ll love tappyn</h2>
            <div class="features-box">
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-second.svg' ?>">
                    <h3>90 second<br>
            		submissions</h3>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-submissions.svg' ?>">
                    <h3>Cap of 50<br>
           			on submissions</h3>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-cash.svg' ?>">
                    <h3>Sizeable, immediate <br>
            		payouts</h3>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-school.svg' ?>">
                    <h3>School specific<br>
            		contests</h3>
                </div>
            </div>
            <div class="contest-button">
                <a href="#0" class="btn btn-contest">Join Now!</a>
            </div>
        </div>
    </section>
    <!-- Raw Info -->
    <section class="raw-info">
        <div class="row">
            <h2>We connect innovative companies with the world’s best creatives.</h2>
            <div class="completions">
                <div class="column medium-4">
                    <h3>
              More than
              <strong>15</strong>
              companies starts contests every week
            </h3>
                </div>
                <div class="column medium-4">
                    <h3>
             TAPPYN has launched over
             <strong>75</strong>
             statisfied companies
            </h3>
                </div>
                <div class="column medium-4">
                    <h3>
             Students just like you have won
             <strong>$1000</strong>
             in total just last week
            </h3>
                </div>
            </div>
            <div class="payout">
                <h3>
            Total Payout From Contests:
            <strong>$2,550</strong>
          </h3>
            </div>
            <div class="boxes">
                <div class="medium-4 column">
                    <div class="box-width">
                        <img src="<?php echo base_url().'public/img/img-creative.png'?>" width='200' height='200'>
                        <h3>Our Creatvies</h3>
                        <p>The minds in our network would make even the A-Team jealous (and we love Mr.T)</p>
                        <a href="#">WANT TO TAPPYN?</a>
                    </div>
                </div>
                <div class="medium-4 column">
                    <div class="box-width">
                        <img src="<?php echo base_url().'public/img/img-contest.png'?>" width='200' height='200'>
                        <h3>Our Companies</h3>
                        <p>More than 75 companies never look at advertising the same way again.</p>
                        <a href="#">WANT TO TAPPYN?</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Past Companies -->
    <section class="past-companies">
        <Div class="row">
            <h2 class="title">A Few of Our Past Companies</h2>
            <img src="<?php echo base_url().'public/img/img-companies.jpg'?>" width='2000' height='720'>
        </Div>
    </section>

<?php $this->load->view('templates/footer'); ?>
