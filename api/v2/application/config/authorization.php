<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Denote which routes in the application require authorization
 *
 * Options are
 *
 *  FALSE  =>  Entire controller does not require authentication
 *  TRUE   =>  Entire controller requires authentication
 *  array()=>  Array of routes in controller that require authentication
 */
$config['contests'] = array(
    'create',
    'update',
    'delete'
);

$config['welcome'] = array(
    'test'
);
$config['votes'] = TRUE;
$config['errors'] = FALSE;
$config['accounts'] = TRUE;
