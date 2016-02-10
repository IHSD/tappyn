<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <div class='large-10 large-offset-1 columns'>
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
                <th class='sort_header' id='first_name'>First Name<?php echo is_sorted('first_name'); ?></th>
                <th class='sort_header' id='last_name'>Last Name<?php echo is_sorted('last_name'); ?></th>
                <th class='sort_header' id='created_on'>Sign Up<?php echo is_sorted('created_on'); ?></th>
                <th class='sort_header' id='last_login'>Last Login<?php echo is_sorted('last_login'); ?></th>
                <th class='sort_header' id='email'>Email<?php echo is_sorted('email'); ?></th>
                <th>Groups</th>
                <th class='sort_header' id='age'>Age<?php echo is_sorted('age'); ?></th>
                <th class='sort_header' id='gender'>Gender<?php echo is_sorted('gender'); ?></th>
            </tr>
            <?php foreach($users as $user): ?>
                <tr>
                    <td>
                        <ul class="dropdown menu" data-dropdown-menu>
                        <li>
                          <a href="#">Actions</a>
                          <ul class="menu">
                            <li><a href="#">View</a></li>
                            <li><a href="#">Edit</a></li>
                            <li><a href="#">Deactivate</a></li>
                            <!-- ... -->
                          </ul>
                        </li>
                      </ul>
                    </td>
                    <td><?php echo $user->first_name; ?></td>
                    <td><?php echo $user->last_name; ?></td>
                    <td><?php echo date('D M d', $user->created_on); ?></td>
                    <td><?php echo date('D M d', $user->last_login); ?></td>
                    <td><?php echo $user->email; ?></td>
                    <td>
                        <?php foreach($user->groups as $group): ?>
                            <?php echo $group->name; ?>
                        <?php endforeach; ?>
                    </td>
                    <td><?php echo $user->age; ?></td>
                    <td><?php echo $user->gender; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $pagination_links; ?>
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
        document.location.href = "<?php echo base_url().'admin/users/index?'; ?>"+buildQuery(getVars);
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
