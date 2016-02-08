<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class="innerpage">
<div class='medium-6 medium-offset-3 small-12'>
    <?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
</div>
<h2 class="text-center">Live Contests</h2>
    <div class="browse-contest">
        <div class="row padding">
            <div class="browse-contest-content">
                <div class="margin-minus">
                    <?php foreach($contests as $contest): ?>
                        <div class="medium-2 small-6 columns end">
                            <a href="<?php echo base_url().'contests/'.$contest->id ?>" style='cursor:pointer;text-decoration:none;'>
                            <div class="submission-box">
                                <div class="ad-company-info">
                                    <div>
                                        <h5 class='text-left'><?php echo $contest->company->name; ?></h5>
                                        <span class='contest-price'>
                                            $50
                                        </span>
                                    </div>
                                    <div class="company-img" style='height:100px'>
                                        <?php if($contest->company->logo_url) : ?>
                                            <img src="<?php echo base_url().'uploads/'.$contest->company->logo_url; ?>">
                                        <?php else: ?>
                                            <img src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
                                        <?php endif; ?>
                                    </div>

                                        <div><h5 class='text-center'>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h5></div>
                                        <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                                            <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                                        </div>
                                        <p class="progress-meter-text" style='text-decoration:none;'><?php echo $contest->submission_count; ?> of 50 submissions</p>

                                </div>
                                <?php switch($contest->platform):
                                    case 'facebook': echo "<img src='".base_url()."public/img/facebook-buttoon.png'>"; break;
                                    case 'google': echo "<img src='".base_url()."public/img/google-buttoon.png'>"; break;
                                    case 'general': echo "<h5>General</h5>"; break;
                                    case 'twitter': echo "<img src='".base_url()."public/img/twitter-buttoon.png'>"; break;
                                endswitch; ?>
                            </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
   <script>
 $(document).foundation();
 </script>


<?php $this->load->view('templates/footer'); ?>
