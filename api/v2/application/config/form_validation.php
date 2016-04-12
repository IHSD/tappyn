<?php defined("BASEPATH") or exit('No direct script access allowed');

$config = array();
$config['contest:create'] = array(
    array(
        'field' => 'different',
        'label' => "How Youre Different",
        'rules' => 'required'
    ),
    array(
        'field' => 'objective',
        'label' => "Objective",
        'rules' => 'required'
    ),
    array(
        'field' => 'platform',
        'label' => 'Format',
        'rules' => "Required",
    ),
    array(
        'field' => 'summary',
        'label' => "Summary",
        'rules' => "required"
    ),
    array(
        'field' => 'audeince',
        'label' => "Audience Description",
        'rules' => 'required'
    ),
    array(
        'field' => 'industry',
        'label' => "Interest",
        'rules' => 'required'
    ),
    array(
        'field' => 'display_type',
        'label' => "Display Type",
        'rules' => 'required'
    )
);
$config['auth:user_register'] = array(

)

$config['auth:company_register'] = array(

)

$config['stripe:account'] = array(
    array(
        'field' => 'first_name',
        'label' => "First Name",
        'rules' => 'required'
    ),
    array(
        'field' => 'last_name',
        'label' => "Last Name",
        'rules' => 'required'
    ),
    array(
        'field' => 'dob_day',
        'label' => "DOB - Day",
        'rules' => 'required'
    ),
    array(
        'field' => 'dob_year',
        'label' => "DOB - Year",
        'rules' => 'required',
    ),
    array(
        'field' => "dob_month",
        'label' => "DOB - Month",
        'rules' => 'required',
    ),
    array(
        'field' => 'address_line1',
        'label' => 'Address Line 1',
        'rules' => 'required',
    ),
    array(
        'field' => 'state',
        'label' => "State",
        'rules' => 'required'
    ),
    array(
        'field' => 'postal_code',
        'label' => "Postal Code",
        'rules' => 'required',
    ),
    array(
        'field' => 'country',
        'label' => "Country",
        'rules' => 'required'
    )
);

$config['stripe:']

$config['auth:login'] = array(
    array(
        'field' => 'identity',
        'label' => "Identity",
        'rules' => 'required',
    ),
    array(
        'field' => "password",
        'label' => "Password",
        'rules' => 'required';
    )
)
