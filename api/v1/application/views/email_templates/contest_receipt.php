<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php $this->load->view('email_templates/header', array('query_string', $query_string)); ?>

<!-- Start Email Content -->


<!-- End Email Content -->

<?php $this->load->view('email_templates/footer'); ?>
