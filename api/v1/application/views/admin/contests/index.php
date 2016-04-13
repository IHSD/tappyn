<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>
        <div class='row'>
            <div class='col-sm-10 col-sm-offset-1 content'>
                <h2>Contests</h2>
                <span style='float:right'>

                </span>
                <hr>
                <div class='col-sm-6 col-sm-offset-3'>
                    <?php $this->load->view('templates/notification', array(
                'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
                'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
                )); ?>
                </div>
                <div class='col-sm-12'>
                    <!-- <div class='col-sm-3'>
                        <form class='form-horizontal' action="<?php echo base_url().'admin/contests/search'; ?>" method="POST">
                            <label>Search by Email or UID</label>
                            <input type='text' name='user' class='form-control'>
                        </form>
                    </div> -->
                    <div class='paging-container' style='float:right'>
                        <?php echo $pagination_links; ?>
                    </div>
                    <!-- <?php echo json_encode($contests); ?> -->
                    <table class='table table-condensed table-bordered table-hover table-striped'>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Owner</th>
                            <th>Title</th>
                            <th>Start Time</th>
                            <th>Stop Time</th>
                            <th>Objective</th>
                            <th>Platform</th>
                        </tr>
                        <?php if(!empty($contests)): ?>
                            <?php foreach($contests as $contest): ?>
                                <tr>
                                    <td>
                                        <div class="dropdown">
                                          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            Actions
                                            <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li><a href="<?php echo base_url().'admin/contests/show/'.$contest->id; ?>">View</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="#">Deactivate</a></li>
                                          </ul>
                                        </div>
                                    </td>
                                    <td><?php echo $contest->id; ?></td>
                                    <td><?php echo $contest->company->name; ?></td>
                                    <td><?php echo $contest->title; ?></td>
                                    <td><?php echo date('D M d', strtotime($contest->start_time)); ?></td>
                                    <td><?php echo date('D M d', strtotime($contest->stop_time)); ?></td>
                                    <td><?php echo ucfirst($contest->objective); ?></td>
                                    <td><?php echo ucfirst($contest->platform); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class='alert alert-warning col-sm-6 col-sm-offset-3'>
                                There are no contests to show
                            </div>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
<script>
$(document).ready(function(e) {
    var getVars = getUrlVars();
    console.log(getVars);
    $('.sort_header').click(function(f) {
        getVars.sort_by = $(this).attr('id');
        if(getVars.sort_dir && getVars.sort_dir == 'desc')
        {
            getVars.sort_dir = 'asc';
        }
        else
        {
            getVars.sort_dir = 'desc';
        }

        getVars.per_page = 1;
        document.location.href = "<?php echo base_url().'admin/contests/index?'; ?>"+buildQuery(getVars);
    });

    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function(m,key,value) {
          vars[key] = value;
        });
        return vars;
    }

    function buildQuery(vars)
    {
        var params = [];
        for(var d in vars)
        {
            params.push(encodeURIComponent(d)+"="+encodeURIComponent(vars[d]));
        }
        return params.join('&');
    }
})
</script>
<?php $this->load->view('templates/footer'); ?>
