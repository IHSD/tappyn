<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo $this->session->flashdata('error') ? $this->session->flashdata('error') : ''; ?>
<br>
<?php if(isset($contests)) { echo json_encode($contests); } ?>

<?php var_dump($pagination_links); ?>

<?php if(isset($pagination_links)) echo $pagination_links; ?>

<?php $this->load->view('templates/footer'); ?>
