<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class="innerpage">
<div class='medium-6 medium-offset-3 small-12'>
    <?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
</div>
<?php function plur($count, $text){ return $count.(( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ));} ?>
<h2 class="text-center">Live Contests</h2>
    <div class="browse-contest">
        <div class="row padding">
            <div class="browse-contest-content">
                <div class="margin-minus">
                    <?php foreach($contests as $contest): ?>
                        <div class="medium-3 large-2 small-12 columns end">
                            <a href="<?php echo base_url().'contests/'.$contest->id ?>" style='cursor:pointer;text-decoration:none;'>
                            <div class="submission-box">
                                <div class="ad-company-info">
                                    <div>
                                        <h5 class='text-left' style='text-align:left;line-height:10px;padding-bottom:10px;' ><?php echo $contest->company->name; ?></h5>
                                        <span class='contest-price'>
                                            $50
                                        </span>
                                    </div>
                                    <div class="company-img" style='margin-top:10px;height:100px'>
                                        <?php if($contest->company->logo_url) : ?>
                                            <img src="<?php echo base_url().'uploads/'.$contest->company->logo_url; ?>">
                                        <?php else: ?>
                                            <img src="<?php echo base_url().'public/img/TappynLogo2.png'; ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div style='text-align:center;text-overflow:ellipsis;color:#333;max-height:150px;'>
                                        <p><?php echo $contest->audience ?></p>
                                    </div>
                                    <?php switch($contest->platform):
                                        case 'facebook': echo "<img src='".base_url()."public/img/OrangeFacebookIcon.png' width='20px' height='20px'> <h5>Facebook</h5>"; break;
                                        case 'google': echo "<img src='".base_url()."public/img/OrangeGoogleIcon.png' width='20px' height='20px'> <h5>Google</h5>"; break;
                                        case 'general': echo "<h5>General</h5>"; break;
                                        case 'twitter': echo "<img src='".base_url()."public/img/OrangeTwitterIcon.png' width='20px' height='20px'> <h5>Twitter</h5>"; break;
                                    endswitch; ?>
                                    <?php  $stop = new DateTime($contest->stop_time, new DateTimeZone('America/New_York')); $now = new DateTime('now', new DateTimeZone('America/New_York')); $difference = $stop->diff($now);?>
                                    <div><h5 class='text-center'>Ends in
                                        <?php
                                            if($difference->d > 0) echo plur($difference->d, 'day');
                                            elseif($difference->h > 0) echo plur($difference->h, 'hour');
                                            else echo plur($difference->i, 'minute');                                        
                                        ?>
                                    </h5></div>
                                    <div style='width:100%'>
                                        <div style='width:75%;float:left;'>
                                            <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                                                <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                                            </div>
                                        </div>
                                        <div style='width:10%;float:right;margin-right:15px;'><p class="progress-meter-text" style='margin:0px;'><?php echo $contest->submission_count; ?>/50</p></div>
                                    </div>
                                </div>
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
