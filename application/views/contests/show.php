<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php echo $this->session->flashdata('error') ? $this->session->flashdata('error') : ''; ?>

<?php echo json_encode($contest); ?>
