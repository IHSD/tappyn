
        <!-- <div class='admin-navbar'>
	    <div class="row">
	        <div class="column medium-12">
	            <div class="columns small-3 medium-4">
                    Tappyn Admin
	            </div>
	            <div class="columns small-9 medium-8">
	                <div id="nav-icon4">
	                    <span></span>
	                    <span></span>
	                    <span></span>
	                </div>
	                <nav class='admin-nav'>
	                    <ul>
                            <li class="<?php echo is_active('users'); ?>"><a href="<?php echo base_url().'admin/users/index'; ?>">Users</a></li>
                            <li class="<?php echo is_active('contests'); ?>"><a href="<?php echo base_url().'admin/contests/index'; ?>">Contests</a></li>
                            <li class="<?php echo is_active('submissions'); ?>"><a href="<?php echo base_url().'admin/submissions/index'; ?>">Submissions</a></li>
                            <li class="<?php echo is_active('accounts'); ?>"><a href="<?php echo base_url().'admin/accounts/index'; ?>">Accounts</a></li>
                            <li class="<?php echo is_active('payments'); ?>"><a href="<?php echo base_url().'admin/payments/index'; ?>">Payments</a></li>
                            <li class="<?php echo is_active('contacts'); ?>"><a href="<?php echo base_url().'admin/contacts/index'; ?>">Contacts</a></li>
	                    </ul>
	                </nav>
	            </div>
	        </div>
	    </div>
        </div> -->
<?php
function is_active($key)
{
    if(get_instance()->uri->segment(2) == $key)
    {
        return 'active';
    }
    return '';
}
?>
