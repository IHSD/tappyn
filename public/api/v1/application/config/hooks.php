<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
|
|   pre_system                  => Called very early during system execution. Only benchmarking and hooks have been loaded
|   pre_controller              => Called immeditaely prior to any controllers being called.
|   post_controller_constructor => Called immediately after controller is instantiated
|   post_controller             => Called immediately after controller has finished executing
|   display_override            => Overrides the _display() method, to send finalized page to web browser
|   cache_override              => Enables you to call your own metho instead of the _display_cache() method
|   post_system                 => Called after the final rendered page has been sent to the browser
*/

$hooks['pre_system']                    = array();
$hooks['pre_controller']                = array();
$hooks['post_controller_constructor']   = array();
$hooks['post_controller']               = array();
$hooks['display_override']              = array();
$hooks['cache_override']                = array();
$hooks['post_system']                   = array();

/*
 | -----------------------------------------------------------------------
 | Controller Hooks
 |
 */
