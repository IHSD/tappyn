<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2><?php echo $contest->title; ?></h2>
                <hr>
                <div class='col-sm-12'>
                    <div class='col-sm-10'>
                       <ul class="nav nav-tabs">
                          <li role="presentation" class="nav-tab active"><a href="<?php echo base_url().'admin/contests/show/'.$contest->id; ?>">Contest</a></li>
                          <li role="presentation" class="nav-tab" id='submission-nav-tab'><a href="#">Submissions</a></li>
                          <li role="presentation" class="nav-tab"><a href="#">Winner</a></li>
                    </div>
                </div>
                <div class='inner-content'>
                    <div id='submissions_container'>
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
                    <div id='contest-container'>

                    </div>
                </div>
            </div>
        </div>
<script>
$(document).ready(function(){
    $('.nav-tab a').click(function(){
        alert('nav clicked');
    })
})
</script>
<?php $this->load->view('templates/footer'); ?>
