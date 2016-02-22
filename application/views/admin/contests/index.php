<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <div class='row'>
        <div class='large-12 columns'>
            <h2>Users</h2>
            <div class='medium-6 medium-offset-3 small-12'>
                <?php $this->load->view('templates/notification', array(
            'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
            'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
            )); ?>
            </div>

            <table class='large-12 columns'>
                <tr>
                    <th>Actions</th>
                    <th id='company'>Company</th>
                    <th class='sort_header' id='title'>Title<?php echo is_sorted('title'); ?></th>
                    <th class='sort_header' id='created_at'>Created At<?php echo is_sorted('created_at'); ?></th>
                    <th class='sort_header' id='start_time'>Start Time<?php echo is_sorted('start_time'); ?></th>
                    <th class='sort_header' id='stop_time'>Stop Time<?php echo is_sorted('stop_time'); ?></th>
                    <th class='sort_header' id='prize'>Prize<?php echo is_sorted('prize'); ?></th>
                    <th>Submissions</th>
                </tr>
                <?php foreach($contests as $contest): ?>
                    <tr>
                        <td>
                            <ul class="dropdown menu" data-dropdown-menu>
                            <li>
                              <a href="#">Actions</a>
                              <ul class="menu">
                                  <li><a href="<?php echo base_url().'#/contest/'.$contest->id; ?>" target="_blank">View</a></li>
                                  <li><a href="<?php echo base_url().'admin/contests/edit/'.$contest->id; ?>">Edit</a></li>
                                  <li><a href="<?php echo base_url().'admin/users/show/'.$contest->owner; ?>">View Owner</a></li>
                                <!-- ... -->
                              </ul>
                            </li>
                          </ul>
                        </td>
                        <td><?php echo $contest->company->name; ?></td>
                        <td><?php echo $contest->title; ?></td>
                        <td><?php echo date('D M d', strtotime($contest->created_at)); ?></td>
                        <td><?php echo date('D M d', strtotime($contest->start_time)); ?></td>
                        <td><?php echo date('D M d', strtotime($contest->stop_time)); ?></td>
                        <td><?php echo $contest->prize; ?></td>
                        <td><?php echo $contest->submission_count; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class='pagination-container'>
                <?php echo $pagination_links; ?>
            </div>
        </div>
    </div>
</section>
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
