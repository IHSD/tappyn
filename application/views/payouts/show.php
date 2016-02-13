<?php defined("BASEPATH") or exit('No direct script access allowed'); ?>

<?php $this->load->view('templates/notification', array(
'error' => ($this->session->flashdata('error') ? $this->session->flashdata('error') : (isset($error) ? $error : false )),
'message' => ($this->session->flashdata('message') ? $this->session->flashdata('message') : (isset($message) ? $message : false ))
)); ?>
