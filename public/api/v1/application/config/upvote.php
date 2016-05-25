<?php defined("BASEPATH") or exit('No direct script access allowed');

/**
 * Number of times a user can submit to a contest
 * Set to FALSE to disable
 */
$config['achievements'] = array(
    'new_user' => 0,
    'bronze' => 200,
    'silver' => 500,
    'gold' => 1000,
    'platinum' => 2000,
    'double_platinum' => 3500,
    'triple_platinum' => 5000
);
$config['upvotes_per_contest'] = 5;
$config['leaderboard_limit'] = 25;
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
/**
 * Default payout for contests
 *
 * Specified as float
 */
$config['default_payout_per_contest'] = 85.00;
