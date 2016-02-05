<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php if(isset($pagination_links)) echo $pagination_links; ?>

<div class="subpage">
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
</div>
<div class='row padding' style='margin-top:5px'><h3 class="text-center">Currently <?php echo count($contests) > 1 ? count($contests). ' active contests' : count($contests) . ' active contest'; ?></h3></div>
<section class="innerpage">
    <div class="browse-contest">
        <div class="row padding">
            <div class="browse-contest-content">
                <div class="margin-minus">
                    <?php foreach($contests as $contest): ?>
                        <div class="medium-3 small-12 columns end">
                            <div class="submission-box">
                                <div class="ad-company-info">
                                    <a href="#"><?php echo $contest->company_name; ?></a>
                                    <br>
                                    <a href="#"><?php echo $contest->title; ?></a>
                                    <div class="company-img">

                                    </div>
                                    <h4>$50</h4>
                                    <div class="progress-box2 fb-progress">
                                        <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                                            <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                                        </div>
                                        <p class="progress-meter-text"><?php echo $contest->submission_count; ?> of 50 submissions</p>
                                    </div>
                                    <h5>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?> </h5>
                                </div>
                            </div>
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
