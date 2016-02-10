<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<section class='innerpage'>
    <div class='large-10 large-offset-1 columns'>
        <h3>Users</h3>
        <div class='medium-6 medium-offset-3 small-12'>
            <?php $this->load->view('templates/notification', array(
        'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
        'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
        )); ?>
        </div>

        <table class='large-12 columns'>
            <tr>
                <th>Actions</th>
                <th>Name</th>
                <th>Sign Up</th>
                <th>Last Login</th>
                <th>Email</th>
                <th>Groups</th>
                <th>Age</th>
                <th>Gender</th>
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
                    <td><?php echo $user->first_name.' '.$user->last_name; ?></td>
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
    </div>
</section>

<?php $this->load->view('templates/footer');
