<?php defined("BASEPATH") or exit('No direct script access allowed');

$config = array(
    'submission_create' => array(
        //------------------------
        // Facebook
        // ----------------------
        'facebook'  => array(
                'clicks_to_website'     => array(
                    array(
                        'field' => 'text',
                        'label' => "Text",
                        'rules' => 'required|min_length[10]|max_length[90]'
                    ),
                    array(
                        'field' => 'headline',
                        'label' => "Headline",
                        'rules' => 'required|max_length[35]'
                    ),
                    array(
                        'field' => "link_explanation",
                        'Label' => "Link Desription",
                        'rules' => 'required|max_length[250]|min_length[10]'
                    ),
                ),
                'conversions'           => array(
                    array(
                        'field' => 'text',
                        'label' => "Text",
                        'rules' => 'required|min_length[10]|max_length[90]'
                    ),
                    array(
                        'field' => 'headline',
                        'label' => "Headline",
                        'rules' => 'required|max_length[35]'
                    ),
                    array(
                        'field' => "link_explanation",
                        'label' => "Link Desription",
                        'rules' => 'required|max_length[250]|min_length[10]'
                    ),
                ),
                'engagement'            => array(
                    array(
                        'field' => 'text',
                        'label' => "Text",
                        'rules' => 'required|min_length[10]|max_length[90]'
                    ),
                )
        ),
        //-----------------------------
        // Google
        // ----------------------------
        'google'    => array(
            'awareness'                 => array(
                array(
                    'field' => 'text',
                    'label' => "Text",
                    'rules' => "required|max_length[25]"
                ),
                array(
                    'field' => "headline",
                    'label' => "Description Line 1",
                    'rules' => 'required|max_length[35]'
                ),
                array(
                    'field' => "link_explanation",
                    'label' => "Description Line 2",
                    'rules' => 'required|max_length[35]'
                )
            ),
            'consideration'             => array(
                array(
                    'field' => 'text',
                    'label' => "Text",
                    'rules' => "required|max_length[25]"
                ),
                array(
                    'field' => "headline",
                    'label' => "Description Line 1",
                    'rules' => 'required|max_length[35]'
                ),
                array(
                    'field' => "link_explanation",
                    'label' => "Description Line 2",
                    'rules' => 'required|max_length[35]'
                )
            ),
            'drive_action'              => array(
                array(
                    'field' => 'text',
                    'label' => "Text",
                    'rules' => "required|max_length[25]"
                ),
                array(
                    'field' => "headline",
                    'label' => "Description Line 1",
                    'rules' => 'required|max_length[35]'
                ),
                array(
                    'field' => "link_explanation",
                    'label' => "Description Line 2",
                    'rules' => 'required|max_length[35]'
                )
            ),
            'search_presence'           => array(
                array(
                    'field' => 'text',
                    'label' => "Text",
                    'rules' => "required|max_length[25]"
                ),
                array(
                    'field' => "headline",
                    'label' => "Description Line 1",
                    'rules' => 'required|max_length[35]'
                ),
                array(
                    'field' => "link_explanation",
                    'label' => "Description Line 2",
                    'rules' => 'required|max_length[35]'
                )
            )
        ),
        //-----------------------------
        // Twitter
        // ----------------------------
        'twitter'   => array(
            'site_clicks_conversions'   => array(
                array(
                    'field' => 'text',
                    'label' => "Tweet",
                    'rules' => 'required|max_length[116]'
                ),
                array(
                    'field' => 'headline',
                    'label' => "Headline",
                    'rules' => 'max_length[70]'
                )
            ),
            'followers'                 => array(
                array(
                    'field' => 'text',
                    'label' => "Tweet",
                    'rules' => 'required|max_length[140]'
                ),
            ),
            'engagement'                => array(
                array(
                    'field' => 'text',
                    'label' => "Tweet",
                    'rules' => 'required|max_length[140]'
                )
            )
        ),
        //-----------------------------
        // General
        // ----------------------------
        'general'   => array(
            'brand_positioning'         => array(),
            'drive_action'              => array(),
            'engagement'                => array()
        ),
        //-----------------------------
        // Instagram *coming soon
        // ----------------------------
    )
);
