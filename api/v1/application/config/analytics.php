<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Attribution window
 *
 * How long we should store a particular analytics session on the user
 *
 * Set to FALSE to disable
 */
$config['attribution_window'] = 86400;

/**
 * Name of our session hash variable
 */
$config['session_hash_name'] = 'tappyn_session_hash';
$config['expiration_var_name'] = 'tappyn_session_exp';
