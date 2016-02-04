<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<!-- User is a company -->
<?php if($this->ion_auth->in_group(3)): ?>
    <section class="innerpage">
        <!-- Your Work -->
        <div class="your-work">
            <div class="row padding">
                <h2 class="inner-title">&lt;Company Name&gt;</h2>
                <div class="tabs-box">
                    <ul class="tabs" data-tabs id="example-tabs">
                        <li class="tabs-title is-active"><a href="<?php echo base_url().'users/dashboard'; ?>" aria-selected="true">All</a></li>
                        <li class="tabs-title"><a href="<?php echo base_url().'users/in_progress'; ?>">In Progress</a></li>
                        <li class="tabs-title"><a href="<?php echo base_url().'users/completed'; ?>">Winning</a></li>
                    </ul>
                    <div class="tabs-content" data-tabs-content="example-tabs">
                        <div class="tabs-panel is-active" id="panel1">
                            <div class="margin-minus">
                                <?php foreach($contests as $contest): ?>
                                    <div class="medium-12 columns end">
                                        <div class="company-post">
                                            <div class="ad-company-info">
                                                <h5><?php echo $contest->platform; ?></h5>
                                                <h4>$50</h4>
                                                <h5>Ends <?php echo date('D, M d', strtotime($contest->stop_time));?> </h5>
                                                <div class="progress-box2 fb-progress">
                                                    <div class="progress" role="progressbar" tabindex="0" aria-valuenow="20" aria-valuemin="0" aria-valuetext="25 percent" aria-valuemax="100">
                                                        <span class="progress-meter" style="width:<?php echo $contest->submission_count; ?>%"></span>
                                                    </div>
                                                    <p class="progress-meter-text"><?php echo $contest->submission_count; ?> of 50 submissions</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!-- User is a submitter -->
<?php else: ?>

<?php endif; ?>
