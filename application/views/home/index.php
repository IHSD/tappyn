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
                    <h1>COMPANIES ARE LOOKING FOR CREATIVE PEOPLE LIKE YOU.</h1>
                    <h2>JOIN OUR COMMUNITY <br> AND START EARNING MONEY TODAY! </h2>
                   	<a class='btn btn-banner' href="<?php echo base_url().'contests' ?>">TAPPYN</a>
                </div>
            </div>
        </div>
        <div class="overlay"></div>
    </section>
    <section class="contests">
        <div class="row">
            <h2 class="title">Past Winners</h2>
            <div class='medium-10 medium-offset-1 small-12'>
                <div class="contest-box">
                    <span class="price" style='right:5px;'>$50</span>
                    <h3 style='width:90%'><a href="#0">Take the road less traveled. We'll show you the way.</a> <span style='padding-left:85px;'>- Melanie B.</span></h3>
                    <div class="contest-content">
                        <span>Mr. Arlo is the best way to discover, collect and organize travel plans that fit your personality and budget</span>
                    </div>
                    <div class="contest-info">
                        <h5>Emory University</h5>
                        <h5>Mr. Arlo</h5>
                        <h5><span class='duration'>7 Days Ago</span>Atlanta, GA</h5>
                    </div>
                </div>
            </div>
            <div class='medium-10 medium-offset-1 small-12'>
                <div class="contest-box">
                    <span class="price" style='right:5px;'>$50</span>
                    <h3 style='width:90%'><a href="#0">We believe your morning brew should be as unique as you.</a> <span style='padding-left:85px;'>- Natalie E.</span></h3>
                    <div class="contest-content">
                        <span>VegaCoffee brings you coffee from farm to table.</span>
                    </div>
                    <div class="contest-info">
                        <h5>Electrical Engineer</h5>
                        <h5>VegaCoffee</h5>
                        <h5><span class='duration'>7 Days Ago</span> Atlanta, GA</h5>
                    </div>
                </div>
            </div>
            <div class='medium-10 medium-offset-1 small-12'>
             <div class="contest-box">
                <span class="price" style='right:5px;'>$50</span>
                <h3 style='width:90%'><a href="#0">Surf the web to spend more time suring the waves.</a> <span style='padding-left:85px;'>- Olivia S.</span></h3>
                <div class="contest-content">
                    <span>Find & sell your surf equipment on Surfer's List</span>
                </div>
                <div class="contest-info">
                    <h5>Brown University</h5>
                    <h5>Surfer's List</h5>
                    <h5><span class='duration'>7 Days Ago</span>Providence, RI</h5>
                </div>
             </div>
            </div>
            <div class='medium-10 medium-offset-1 small-12'>
             <div class="contest-box">
                <span class="price" style='right:5px;'>$50</span>
                <h3 style='width:90%'><a href="#0">Marketers on demand, to create demand.</a> <span style='padding-left:85px;'>- Phuong N.</span></h3>
                <div class="contest-content">
                    <span>Get your marketing done professionally with Minds for Hire.</span>
                </div>
                <div class="contest-info">
                    <h5>Creative Writer</h5>
                    <h5>Minds for Hire</h5>
                    <h5><span class='duration'>7 Days Ago</span>Atlanta, GA</h5>
                </div>
            </div>
            </div>
            <div class="contest-button">
               <a href="<?php echo base_url().'contests' ?>" class='btn btn-contest'>See Contests</a>
            </div>
        </div>
    </section>
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
                    <p>Only 50 submissions are allowed, so your odds of winning are great.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-contest.svg' ?>">
                    <h3>Keep killin' it</h3>
                    <p>If you like the taste of victory, keep submitting to contests.  </p>
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
              <li>$250 cash</li>
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
            		<p>Each submission is only a few sentences and has easy, simple instructions.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-submissions.svg' ?>">
                    <h3>Cap of 50<br>
           			on submissions</h3>
           			<p>We cap all contests at 50 submissions so you've got a great chance of winning.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-cash.svg' ?>">
                    <h3>Sizeable, immediate <br>
            		payouts</h3>
            		<p>$50 becomes $500 pretty quickly. As a winner, you'll be payed within 3 days.</p>
                </div>
                <div class="medium-3 column">
                    <img src="<?php echo base_url().'public/img/icon-school.svg' ?>">
                    <h3>Exclusive<br>
            		contests</h3>
            		<p>You have exclusive access to contests based on your school, job, or interests.</p>
                </div>
            </div>
            <div class="contest-button">
                <a href="<?php echo base_url().'signup'; ?>" class="btn btn-contest">Join Now!</a>
            </div>
        </div>
    </section>
    <!-- Raw Info -->
    <section class="raw-info">
    <!--    <div class="row">
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
                        <a href="<?php echo base_url().'contests/index'; ?>">WANT TO TAPPYN?</a>
                    </div>
                </div>
                <div class="medium-4 column">
                    <div class="box-width">
                        <img src="<?php echo base_url().'public/img/img-contest.png'?>" width='200' height='200'>
                        <h3>Our Companies</h3>
                        <p>More than 75 companies never look at advertising the same way again.</p>
                        <a href="<?php echo base_url().'contests/index'; ?>">WANT TO TAPPYN?</a>
                    </div>
                </div>
            </div>
        </div> -->
        <div class='row'>
            <h2 class='title'>Get personalized contests delivered
            <br><br>to your inbox weekly.</h2>
            <div class='medium-8 medium-offset-2 small-12'>
                <?php echo form_open("mailing_list");?>
                <div class='form-row large-8 small-12 columns'>
                   <?php echo form_input(array('name' => 'email','value' => '','placeholder' => 'Enter your email', 'type' => 'text'));?>
                </div>
                <?php echo form_submit('submit', 'Sign Up', array("class" => 'btn large-4 small-12 columns'));?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </section>
    <!-- Past Companies -->
    <!-- <section class="past-companies">
        <Div class="row">
            <h2 class="title">A Few of Our Past Companies</h2>
            <img src="<?php echo base_url().'public/img/img-companies.jpg'?>" width='2000' height='720'>
        </Div>
    </section> -->

<?php $this->load->view('templates/footer'); ?>
