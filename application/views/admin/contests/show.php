<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2><?php echo $contest->title; ?> <span class='a-label'>(<?php echo date('D M d', strtotime($contest->start_time)). ' -> ' .date('D M d', strtotime($contest->stop_time)); ?>)</span></h2>
                <hr>
                <div class='col-sm-12'>
                    <div class='col-sm-10'>
                       <ul class="nav nav-tabs">
                          <li role="presentation" class="nav-tab active"><a href="#">Contest</a></li>
                          <li role="presentation" class="nav-tab" id='submission-nav-tab'><a href="#">Submissions</a></li>
                          <li role="presentation" class="nav-tab"><a href="#">Winner</a></li>
                    </div>
                </div>
                <div class='inner-content'>
                    <?php echo json_encode($contest); ?>
                    <div id='contest_container'>
                        <div class='row'>
                            <div class='col-sm-8 text-center'>
                                <h4>Brief</h4>
                                <hr>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        Platform
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->platform; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        Objective
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->objective; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        Summary
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->summary; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        Different
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->different; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        Audience
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->audience; ?>
                                    </div>
                                </div>
                            </div>
                            <div class='col-sm-4'>
                                <h4>Details</h4>
                                <hr>
                                <table class='table table-condensed table-bordered'>
                                    <tr>
                                        <td>Owner</td>
                                        <td><?php echo $contest->company->name; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Created At</td>
                                        <td><?php echo date('D M d, H:i', strtotime($contest->created_at)); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Paid</td>
                                        <td>
                                            <?php if($contest->paid == 0): ?>
                                                <span style='color:red'><strong>Payment Due</strong></span>
                                            <?php else: ?>
                                                paid
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Submissions</td>
                                        <td><?php echo count($contest->submissions); ?></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id='submissions_container' style='display:none'>
                        <table class='table table-condensed table-bordered table-hover table-striped'>
                            <tr>
                                <th>Actions</th>
                                <th>Created At</th>
                                <th>Headline</th>
                                <th style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;'>Text</th>
                            </tr>
                            <?php if(count($contest->submissions) < 1): ?>
                                <div class='alert alert-warning'>This contest does not currently have any submissions</div>
                            <?php else: ?>
                                <?php foreach($contest->submissions as $submission): ?>
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                Actions
                                                <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="#" data-toggle='modal' data-target='#submission_edit_modal' data-id="<?php echo $submission->id; ?>">Edit</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a class='alert-danger' href="<?php echo base_url().'admin/submissions/delete/'.$submission->id; ?>">Delete</a></li>
                                              </ul>
                                            </div>
                                        </td>
                                        <td><?php echo date('D M d', strtotime($submission->created_at)); ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_headline'><?php echo $submission->headline; ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_text'><?php echo $submission->text; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<script>
$(document).ready(function(){
    $('.nav-tab a').click(function(){

    })
})
</script>
<?php $this->load->view('templates/footer'); ?>
