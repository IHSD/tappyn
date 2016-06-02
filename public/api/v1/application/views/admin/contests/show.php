<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2><?php echo $contest->title; ?> <span class='a-label'>(<?php echo date('D M d', strtotime($contest->start_time)). ' -> ' .date('D M d', strtotime($contest->stop_time)); ?>)</span></h2>
                <hr>
                <div class='row'>
                <div class='col-sm-6 col-sm-offset-3'>
                    <?php $this->load->view('templates/notification', array(
                        'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                        'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                    )); ?>
                </div>
                <div class='col-sm-12'>
                    <div class='col-sm-10'>
                       <ul class="nav nav-tabs">
                          <li role="presentation" class="nav-tab active" id='contest'><a href="#">Contest</a></li>
                          <li role="presentation" class="nav-tab" id='submission'><a href="#">Submissions</a></li>
                          <li role="presentation" class="nav-tab" id='winner'><a href="#">Winner</a></li>
                    </div>
                </div>
                <div class='inner-content'>
                    <div id='contest_container'>
                        <div class='row'>
                            <div class='col-sm-8 text-center'>
                                <h4>Brief</h4>
                                <hr>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <h5>Platform</h5>
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->platform; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <h5>Objective</h5>
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->objective; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <h5>Summary</h5>
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->summary; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <h5>Different</h5>
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->different; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <h5>Audience</h5>
                                    </div>
                                    <div class='col-sm-8'>
                                        <?php echo $contest->audience; ?>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4 col-sm-offset-4'>
                                        <button type='button' class='btn btn-primary' data-toggle='modal' data-target="#subEditModal">Edit Brief</button>
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
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id='submissions_container' style='display:none'>
                        <table class='table table-condensed table-bordered table-hover table-striped'>
                            <tr>
                                <th>Actions</th>
                                <th>Created At</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Headline</th>
                                <th style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;'>Text</th>
                                <th>Votes</th>
                            </tr>
                            <?php if(count($contest->submissions) < 1): ?>
                                <div class='alert alert-warning'>This campaign does not currently have any ads submitted yet.</div>
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
                                        <td><?php echo $submission->owner->first_name.' '.$submission->owner->last_name; ?></td>
                                        <td><?php echo $submission->owner->email; ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_headline'><?php echo $submission->headline; ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_text'><?php echo $submission->text; ?></td>
                                        <td><?php echo $submission->votes; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<div class="modal fade" id="subEditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class='form-horizontal' action="<?php echo base_url().'admin/contests/update/'.$contest->id; ?>" method="POST">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
          <div class='row'>
              <div class='col-sm-12'>
                    <div class='form-group col-sm-6'>
                        <label>Platform</label>
                        <select id='platform_selection' class='form-control' name='platform'>
                            <option value='google' <?php if($contest->platform == 'google') echo 'selected' ?>>Google</option>
                            <option value='facebook' <?php if($contest->platform == 'facebook') echo 'selected' ?>>Facebook</option>
                            <option value='twitter' <?php if($contest->platform == 'twitter') echo 'selected' ?>>Twitter</option>
                        </select>
                    </div>
                    <div class='form-group col-sm-6'>
                        <label>Objective</label>
                        <select id='objective_selection' class='form-control' name='objective'>
                            <option value='website_clicks' <?php if($contest->platform == 'website_clicks') echo 'selected' ?>>Website Clicks</option>
                            <option value='post_engagement' <?php if($contest->platform == 'post_engagement') echo 'selected' ?>>Post Engagement</option>
                            <option value='brand_positioning' <?php if($contest->platform == 'brand_positioning') echo 'selected' ?>>Brand Positioning</option>
                            <option value='app_installs' <?php if($contest->platform == 'app_installs') echo 'selected' ?>>App Installs</option>
                        </select>
                    </div>
                    <div class='form-group col-sm-12'>
                        <label>Summary</label>
                        <textarea class='form-control' rows='2' name='summary'><?php echo $contest->summary; ?></textarea>
                    </div>
                    <div class='form-group col-sm-12'>
                        <label>Different</label>
                        <textarea class='form-control' rows='6' name='different'><?php echo $contest->different; ?></textarea>
                    </div>
                    <div class='form-group col-sm-12'>
                        <label>Audience</label>
                        <textarea class='form-control' rows='6' name='audience'><?php echo $contest->audience; ?></textarea>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
  </form>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
    $('.nav-tab').click(function()
    {
        var id = $(this).attr('id');
        if(id == 'submission')
        {
            $('#submissions_container').show();
            $('#contest_container').hide();
        } else {
            $('#contest_container').show();
            $('#submissions_container').hide();
        }
    })
})
</script>
<?php $this->load->view('templates/footer'); ?>
