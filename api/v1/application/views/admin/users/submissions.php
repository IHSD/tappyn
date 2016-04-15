<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2>Submissions</h2>
                <hr>
                <div class='inner-content'>
                    <div class='col-sm-6 col-sm-offset-3'>
                        <?php $this->load->view('templates/notification', array(
                    'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                    'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                    )); ?>
                    </div>
                    <div class='col-sm-12'>
                        <div class='col-sm-3'>

                        </div>
                        <div class='paging-container' style='float:right'>
                            <?php echo $pagination_links; ?>
                        </div>
                        <table class='table table-condensed table-bordered table-hover table-striped'>
                            <tr>
                                <th>Actions</th>
                                <th>ID</th>
                                <th>Created At</th>
                                <th>Headline</th>
                                <th style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;'>Text</th>
                                <th>Contest Onwer</th>
                                <th>Contest Title</th>
                                <th>Contest Ends</th>
                                <th>Payout</th>
                            </tr>
                            <?php if(count($submissions) < 1): ?>
                                <div class='alert alert-warning'>This user does not currently have any submissions</div>
                            <?php else: ?>
                                <?php foreach($submissions as $submission): ?>
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
                                        <td><?php echo $submission->id; ?></td>
                                        <td><?php echo date('D M d', strtotime($submission->created_at)); ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_headline'><?php echo $submission->headline; ?></td>
                                        <td style='max-width:300px;word-wrap:break-word;text-overflow:ellipsis;' class='submission_text'><?php echo $submission->text; ?></td>
                                        <td><?php echo $submission->contest->company->name;?></td>
                                        <td><?php echo $submission->contest->title; ?></td>
                                        <td><?php echo date('D M d',strtotime($submission->contest->stop_time)); ?></td>
                                        <td>
                                            <?php if($submission->payout): ?>
                                                <?php echo $submission->payout->claimed == 0 ? "Pending" : "Claimed"; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </table>
                    </div>

                </div>
            </div>
        </div>
<div class="modal fade" id="submission_edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Edit Submission</h4>
      </div>
      <form action="<?php echo base_url().'admin/submissions/edit' ?>" method='post'>
          <div class="modal-body">
              <div class="form-group">
                <label for="recipient-name" class="control-label">Headline</label>
                <input type="text" class="form-control" id="headline" name='headline'>
              </div>
              <div class="form-group">
                <label for="text" class="control-label">Text</label>
                <textarea class="form-control" id="text" name='text'></textarea>
              </div>
              <input type='hidden' name='submission_id' id='submission_id'>
              <input type='hidden' name='user_id' value="<?php echo $user->id; ?>">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Submission</button>
          </div>
      </form>
    </div>
  </div>
</div>

<script>
$('#submission_edit_modal').on('show.bs.modal', function(event) {
    var button = $(event.relatedTarget);
    var submission = button.data('id');
    var headline = button.closest('tr').find('.submission_headline').text();
    var text = button.closest('tr').find('.submission_text').text();
    var modal = $(this);
    modal.find('#submission_id').val(submission);
    modal.find('.modal-title').text('New message to ');
    modal.find('#headline').val(headline);
    modal.find('#text').val(text);
})
</script>
<?php $this->load->view('templates/footer'); ?>
