<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Number of times a user can submit to a contest
 * Set to FALSE to disable
 */
$config['upvotes_per_contest'] = '3';

/*====================================
  Configuration for Points system
====================================*/

/**
 * Amount of points you start with
 */
$config['starting_points'] = 100;
/**
 * Points per upvote
 */
$config['points_per_upvote'] = 1;
/**
 * Points per contest submission
 */
$config['points_per_submission'] = 2;
/**
 * Points per upvote on winning submission
 */
$config['points_per_upvote_winning'] = 5;
/**
 * Points for your submission winning
 */
$config['points_per_winning_submission'] = 10;
