<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo $this->session->flashdata('error') ? $this->session->flashdata('error') : ''; ?>
<h2>
	<?php if(isset($contest)) : ?>
		<?php echo $contest->title; ?> by <?php echo $contest->company_name; ?>
	<?php endif; ?>
</h2>
<section class="innerpage">
    <div class="browse-contest">
        <div class="row padding">
            <div class="browse-contest-content">

            </div>
        </div>
    </div>
</section>


<?php $this->load->view('templates/footer'); ?>