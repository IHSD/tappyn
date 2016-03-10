<?php defined("BASEPATH") or exit('No direct script access allowed');

$level = 0;

function _display($result)
{
    global $level;
        echo "<div class='col-sm-12 interest-card'  style='text-indent:".($level *  5).'em;'.($level > 1 ? 'display:none' : NULL)."' data-left='".$result->lft."' data-right=".$result->rgt."'>";
            echo "<h4><span class='glyphicon glyphicon-chevron-up click_to_show' data-left='".$result->lft."' data-right='".$result->rgt."' ></span>&nbsp;&nbsp;".$result->display_name."<a class='pull-right remove-link' id='".$result->id."'>Remove</a></h4>";
            echo "<div style='margin-left:".($level * 5)."em'><a data-toggle='modal' data-target='#createInterestModal' data-parent_id='".$result->id."' data-parent_name='".$result->display_name."'>Add Interest</a></div>";
            echo "<hr>";
        echo "</div>";
    if(isset($result->children) && !empty($result->children))
    {
        $level++;
        foreach($result->children as $child)
        {
            _display($child);
        }
        $level--;
    }
}

?>

<div class='col-sm-10 col-sm-offset-1 content'>
    <?php $this->load->view('templates/notification', array(
        'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
        'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
    )); ?>
    <div class='custom-table-wrapper'>
        <?php _display($interests); ?>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id='createInterestModal'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <form class='form-horizontal' action="<?php echo base_url().'admin/interests/create'; ?>" method="POST">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create New Interest for <span id='interest_name'></span></h4>
      </div>
      <div class="modal-body">
         <div class='row'>
             <div class='col-sm-6 col-sm-offset-3'>
                <div class='form-group'>
                    <label>Name</label>
                    <input type='text' name='name' class='form-control'>
                </div>
                <div class='form-group'>
                    <label>Display Name</label>
                    <input type='text' name='display_name' class='form-control'>
                </div>
                <input type='hidden' id="parent_id" name='parent_id' value="">
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(document).ready(function() {
    $('.click_to_show').click(function(e) {
        var rgt = $(this).data('right');
        var lft = $(this).data('left');

        for(var i = (lft + 1); i < rgt; i++)
        {
            console.log("Showing divs with data-left="+i);
            $('[data-left="'+i+'"]').toggle();
            $('[data-left="'+i+'"] span').show();
        }

        $(this).siblings(".add-button").show();
    })

    $('#createInterestModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var recipient = button.data('parent_id');
        var pid = button.data('parent_name');
        var modal = $(this);
        console.log(recipient);
        console.log(pid);
        modal.find('#interest_name').text(pid);
        modal.find('#parent_id').val(recipient);
    })

    $('.remove-link').click(function(e) {
        var id = $(this).attr('id');
        $.ajax({
            url : "<?php echo base_url().'admin/interests/delete/' ?>"+id,
            method: "delete",
            success : function(response)
            {
                window.location = "<?php echo base_url().'admin/interests/index'; ?>";
            }
        });

    })
})
</script>
