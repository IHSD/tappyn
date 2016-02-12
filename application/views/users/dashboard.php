<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<!-- User is a company -->
    <section class="innerpage">
        <!-- Your Work -->
        <div class="your-work">
            <div class="row padding">
                <!-- Generate the company dashboard -->
                <?php if($this->ion_auth->in_group(3)): ?>
                    <h2 class="inner-title"><?php echo $this->ion_auth->profile("name"); ?></h2>
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
                                        <div class="medium-3 columns end">
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
                <!-- Generate the user dashboard -->
                <?php elseif($this->ion_auth->in_group(2)): ?>
                    <h2 class="inner-title"><?php echo $this->ion_auth->user()->row()->first_name.' '.$this->ion_auth->user()->row()->last_name; ?></h2>
                    <div class="tabs-box">
                        <ul class="tabs" data-tabs id="example-tabs">
                            <li class="tabs-title"><a href="<?php echo base_url().'users/dashboard'; ?>" <?php if($status == 'all') echo 'aria-selected=true' ?>>All</a></li>
                            <li class="tabs-title"><a href="<?php echo base_url().'users/in_progress'; ?>" <?php if($status == 'active') echo 'aria-selected=true' ?>>In Progress</a></li>
                            <li class="tabs-title"><a href="<?php echo base_url().'users/completed'; ?>" <?php if($status == 'winning') echo 'aria-selected=true' ?>>Winning</a></li>
                        </ul>
                        <div class="tabs-content" data-tabs-content="example-tabs">
                            <div class="tabs-panel is-active" id="panel1">
                                <div class="margin-minus small-12 columns">
                                    <?php foreach($submissions as $submission): ?>
                                        <div class="medium-4 columns end contest-box min-height">
                                                <div class="medium-12 ad-company-info">
                                                    <div>
                                                        <h5 class='text-left'><?php echo $submission->contest->title; ?> by <?php echo $submission->company->name; ?></h5>
                                                        <span class='contest-price'>
                                                            $50
                                                        </span>
                                                    </div>
                                                    <h5>
                                                        <?php echo (new DateTime() < new DateTime($submission->contest->stop_time)) ? "Ends " : "Ended " ?>
                                                        <?php echo date('D M d, ha', strtotime($submission->contest->stop_time)); ?>
                                                    </h5>
                                                </div>
                                                    <?php switch($submission->contest->platform):
                       				                case 'facebook': $this->load->view('submissions/thumbnails/facebook', array('submission' => $submission)); break;
                       					            case 'google': $this->load->view('submissions/thumbnails/google', array('submission' => $submission)); break;
                       					            case 'trending': $this->load->view('submissions/thumbnails/trending', array('submission' => $submission)); break;
                       					            case 'tagline': $this->load->view('submissions/thumbnails/tagline', array('submission' => $submission)); break;
                       					            case 'general': $this->load->view('submissions/thumbnails/general', array('submission' => $submission)); break;
                       					            case 'twitter': $this->load->view('submissions/thumbnails/twitter', array('submission' => $submission)); break;
                       				            endswitch; ?>
                                                <div class='medium-12 large-12 ad-company-info' style='float:bottom'>
                                                    <div class='text-center'><a href="<?php echo base_url().'contests/'.$submission->contest_id ?>" style='cursor:pointer;text-decoration:none;' class='btn tiny'>View Contest</a></div>
                                                </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php $this->load->view('templates/footer'); ?>
