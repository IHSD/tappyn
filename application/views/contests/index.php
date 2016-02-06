<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php if(isset($pagination_links)) echo $pagination_links; ?>

<!--<div class="subpage">
    <div class="top-sideabar columns large-2 show-for-large">
        <div class="sub-list">
            <ul>
                <li>
                    <strong>$500</strong>
                    <span><img src="<?php echo base_url().'public/img/ico-cup.svg'; ?>" alt=""> Every Week</span>
                </li>
                <li>
                    <strong>75</strong>
                    <span><img src="<?php echo base_url().'public/img/ico-cap.svg'; ?>" alt=""> Winners</span>
                </li>
                <li>
                    <strong>50</strong>
                    <span><img src="<?php echo base_url().'public/img/ico-edit.svg'; ?>" alt=""> Entry Limit</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="columns large-10">
        <div class="margin-minus bg-subpage">
            <div class="overlay-subpage"></div>
            <div class="row medium-8 subpage-content">
                <div class="inner-table">
                    <h3>If there isn't a t-shirt for it, did it even happen?</h3>
                    <h4>Devin Roach - Georgia Tech - $50</h4>
                </div>
            </div>
        </div>
    </div>
</div> -->


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
                        <div class="medium-3 small-12 columns end">
                            <a href="<?php echo base_url().'contests/'.$contest->id ?>" style='cursor:pointer;text-decoration:none;'>
                            <div class="submission-box" style='min-height:290px;max-height:290px;'>
                                <div class="ad-company-info">
                                    <div style='margin-bottom:15px' >
                                        <h4 class='text-left'><?php echo $contest->company->name; ?></h4>
                                        <span class='contest-price'>
                                            $50
                                        </span>
                                    </div>
                                    <div class="company-img">
                                        <?php if($contest->company->logo_url) : ?>
                                            <img style='height:auto; width:100%' src="<?php echo base_url().'uploads/'.$contest->company->logo_url; ?>"> 
                                        <?php endif; ?>
                                    </div>
                                    <div style='width:100%'>
                                        <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                                            <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                                        </div>
                                        <p class="progress-meter-text" style='text-decoration:none;'><?php echo $contest->submission_count; ?> of 50 submissions</p>
                                    </div>
                                    <div><h5 class='text-center'>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?></h5></div>
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
