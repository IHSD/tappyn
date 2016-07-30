<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    protected $stripe_customer_id = false;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('company');
        $this->load->model('user');
        $this->load->model('contest');
        $this->load->model('ad_model');
        $this->config->load('secrets');
        $this->load->library('payout');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');
        $this->load->library('stripe/stripe_customer_library');
        if ($this->ion_auth->logged_in()) {
            $this->stripe_customer_id = $this->company->payment_details($this->ion_auth->user()->row()->id);
        }

    }

    public function index()
    {
        $companies = $this->company->select('*')->from('profiles')->where(
            'summary IS NOT NULL', null
        )->limit(25, ($this->input->get('offset') ? $this->input->get('offset') : 0));
        $followed = $this->input->get('followed');
        if ($followed) {
            $follows = $this->user->following($this->ion_auth->user()->row()->id);
            if (empty($follows)) {
                $this->responder->data(array())->respond();
                return;
            }
            $this->company->where_in('id', $follows);
        }
        $companies = $this->company->fetch()->result();
        $this->responder->data(array(
            'companies' => $companies,
        ))->respond();
    }

    public function show($cid = 0)
    {
        $this->db_test = $this->load->database('master', true);
        if (!$this->ion_auth->in_group(3, $cid)) {
            $this->responder->fail(
                "That company does not exist"
            )->code(500)->respond();
            return;
        }

        $company = $this->company->get($cid);
        unset($company->stripe_customer_id);
        if (!$company) {
            $this->responder->fail(
                "That company does not exist"
            )->code(500)->respond();
            return;
        }

        // get follow
        $company->follows = $this->db_test->select('COUNT(*) as count')->from('follows')->where('following', $cid)->get()->row()->count;
        $user_follows     = $this->db_test->select('*')->from('follows')->where(array('following' => $cid, 'follower' => $this->ion_auth->user()->row()->id))->get();
        if ($user_follows->num_rows() == 0) {
            $company->user_may_follow = true;
        } else {
            $company->user_may_follow = false;
        }

        $company->requests = $this->db_test->select('COUNT(*) as count')->from('requests')->where(array('company_id' => $cid, 'fulfilled' => 0))->get()->row()->count;
        $this->responder->data(array(
            'company' => $company,
        ))->respond();
    }

    public function contests($cid)
    {
        if (!$this->ion_auth->in_group(3, $cid)) {
            $this->responder->fail(
                "That company does not exist"
            )->code(500)->respond();
            return;
        }

        $contests = $this->db->select('*')->from('contests')->where(array(
            'start_time <' => date('Y-m-d H:i:s'),
            'paid'         => 1,
            'owner'        => $cid,
        ))->get()->result();
        foreach ($contests as $contest) {
            if ($contest->stop_time > date('Y-m-d H:i:s')) {
                $contest->status = 'active';
                $contest->link   = 'contest';
            } else {
                $contest->status = 'ended';
                $contest->link   = 'ended';
            }
            $contest->submission_count = $this->db->select('COUNT(*) as count')->from('submissions')->where('contest_id', $contest->id)->get()->row()->count;
        }
        $this->responder->data(array('contests' => $contests))->respond();
    }

    public function dashboard()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if ($this->ion_auth->in_group(2)) {
            redirect("api/v1/users/dashboard");
        }

        $this->data['status'] = 'all';

        if ($this->input->get('type') === 'completed' || $this->input->get('type') === 'need_winner') {
            $this->contest->where('stop_time <', date('Y-m-d H:i:s'));
        } else if ($this->input->get('type') === 'in_progress') {
            $this->contest->where(array(
                'start_time <' => date('Y-m-d H:i:s'),
                'stop_time >'  => date('Y-m-d H:i:s'),
            ));
        }

        // Make sure we only grab ones belonging to the user
        $this->contest->where('contests.owner', $this->ion_auth->user()->row()->id);
        $contests = $this->contest->fetch();
        if ($contests !== false) {
            $contests = $this->contest->result();
            // Check the input type
            if ($this->input->get('type') === 'need_winner') {
                foreach ($contests as $key => $contest) {
                    if ($this->payout->exists(array('contest_id' => $contest->id))) {
                        unset($contests[$key]);
                    }
                }
            }
            foreach ($contests as $contest) {
                $contest->submission_count = $this->contest->submissionsCount($contest->id);
                $contest->views            = $this->contest->views($contest->id);
                $contest->votes            = $this->vote->select("COUNT(*) as count")->where('contest_id', $contest->id)->fetch()->row()->count;
                $share_data                = $this->submission->select('SUM(shares) as shares, SUM(share_clicks) as share_clicks')->where('contest_id', $contest->id)->fetch()->row();
                $contest->shares           = $share_data->shares;
                $contest->share_clicks     = $share_data->share_clicks;
                $contest->status           = $this->contest->get_status($contest);
            }
            $this->responder->data(
                array(
                    'contests' => $contests,
                    'profile'  => $this->user->profile($this->ion_auth->user()->row()->id),
                )
            )->respond();
        } else {
            $this->responder->fail('There was an error fetching your dashboard')->code(500)->respond();
        }
    }

    public function profile()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = array(
                'logo_url'       => $this->input->post('logo_url'),
                'mission'        => $this->input->post('mission'),
                'extra_info'     => $this->input->post('extra_info'),
                'name'           => $this->input->post('name'),
                'company_email'  => $this->input->post('company_email'),
                'company_url'    => $this->input->post('company_url'),
                'facebook_url'   => $this->input->post('facebook_url'),
                'twitter_handle' => $this->input->post('twitter_handle'),
                'different'      => $this->input->post('different'),
                'summary'        => $this->input->post('summary'),
            );
            if ($this->user->saveProfile($this->ion_auth->user()->row()->id, $data)) {
                $this->responder->data(array('profile' => $this->user->profile($this->ion_auth->user()->row()->id)))->message("Profile successfully updated")->respond();
            } else {
                $this->responder->fail("There was an error updating your profile")->code(500)->respond();
            }
        } else {
            $this->responder->data(array(
                'profile' => $this->user->profile($this->ion_auth->user()->row()->id),
            ))->respond();
        }
    }

    public function accounts()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        // Check if we have to process a form in any way
        if ($this->input->post('stripeToken') && $this->stripe_customer_id) {
            // We're going to add a new payment method to the customer
            if ($this->stripe_customer_library->addPaymentSource($this->stripe_customer_id, $this->input->post('stripeToken'))) {
                $this->data['message'] = "Your payment method was successfully added";
            } else {
                $this->data['error'] = ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : "An unknown error occured");
            }
        } else if ($this->input->post('stripeToken') && $this->input->post('remember_me')) {
            // We're going to create a new customer altogether
            if ($customer = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $this->input->post('stripeToken'))) {
                // Create row in database for the user
                $this->stripe_customer_id = $customer->id;
            } else {
                $this->data['error'] = $this->stripe_customer_library->errors();
            }
        }

        $this->data['customer'] = null;

        // After any proccessing, we fetch the updated customer to then show the user
        if ($this->stripe_customer_id) {
            $this->data['customer'] = $this->stripe_customer_library->fetch($this->stripe_customer_id);
            $this->responder->data(array(
                'customer' => $this->data['customer'],
            ))->respond();
        } else {
            $this->responder->fail("You dont have any account details yet")->code(200)->respond();
            return;
        }
    }

    public function removeCard()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if (!$stripe_customer_id) {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('api/v1/companies/accounts', 'refresh');
        }
        if ($this->input->post('source_id')) {
            $data = array(
                'default_source' => $this->input->post('source_id'),
            );

            if ($this->stripe_customer_library->update($stripe_customer_id, $data)) {
                $this->session->set_flashdata('message', 'Default payment option successfully updated');
                redirect('api/v1/companies/accounts', 'refresh');
            } else {
                $this->session->set_flashdata('error', ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : 'An unknown error occured'));
                redirect('api/v1/companies/accounts', 'refresh');
            }
        } else {
            $this->session->set_flashdata('You must provide a payment option to remove');
            redirect('api/v1/companies/accounts', 'refresh');
        }
    }

    /**
     * NEW PAYMENT METHOD
     *
     * If the user has selected to be remembered, we create a customer and charge that
     * Else we just straight charge the card
     *
     * // $contest_id, $token = NULL, $customer_id = NULL, $source_id = NULL, $amount = 100
     * @param  [type] $contest_id [description]
     * @todo Still need to test selected payment method
     * @return [type]             [description]
     */
    public function payment($contest_id = false)
    {
        $this->load->library('slack');
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        // Initialize the control variables
        $charge = false;

        $this->load->library('price_lib');
        $this->load->library('stripe/stripe_charge_library');

        $post           = $this->input->post();
        $post['go_pay'] = true;
        $data           = $this->price_lib->get_price_from_post($post);
        if ($data['success']) {
            $amount = $data['price'] * 100;
        } else {
            $this->responder->fail($data['message'])->code(500)->respond();
            exit();
        }

        if ($amount != $post['price'] * 100) {
            $this->responder->fail("amount not right")->code(500)->respond();
            exit();
        }

        if ($post['pay_for'] == 'subscription') {
            $post['save_method'] = 'true';
        }

        if ($amount > 000) {
            $charge = $this->get_charge($post, $amount);
        } else {
            $charge = array();
        }

        $post['origin_price'] = $data['origin_price'];
        // Check if charge was succesful and handle accordingly
        if ($charge !== false) {
            $this->load->library('slack');
            if ($post['pay_for'] == 'purchase') {
                $this->select_winner($post);
            } else if ($post['pay_for'] == 'ab') {
                $this->ab($post);
            } else if ($post['pay_for'] == 'launch') {
                $this->load->library('contest_lib');
                $msg = $this->contest_lib->set_live($post['contest_id']);
                if ($msg === true) {
                    $msg = '[payment][launch]contest ' . $post['contest_id'] . ' paid ' . $post['price'] . ' amount for launch (origin price:' . $post['origin_price'] . ') ';
                    $this->slack->send($msg);
                    $this->responder->message("Your Campaign Launch Successfully!")->respond();
                } else {
                    $this->responder->fail($msg)->code(500)->respond();
                }
            } else if ($post['pay_for'] == 'subscription') {
                $this->subscription_update($post);
            }
        }
        // An error occured, so respond as such
        else {
            $this->responder->fail(
                $this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : ($this->stripe_charge_library->errors() ? $this->stripe_charge_library->errors() : "An unknown error occured with payment")
            )->code(500)->respond();
            return;
        }
    }

    public function get_charge($post, $amount)
    {
        $post['stripe_token'] = isset($post['stripe_token']) ? $post['stripe_token'] : '';
        $post['save_method']  = isset($post['save_method']) ? $post['save_method'] : '';
        $contest_id           = $post['contest_id'];
        $metadata             = array();
        $description          = 'Charge for contest ' . $contest_id . $post['pay_for'];
        $token                = null;
        $customer_id          = null;
        $source_id            = null;
        // If payment details were supplied, we're either going to charge the card, or create / update a customer
        if ($post['stripe_token']) {
            if ($post['save_method'] == 'true') {
                // Update the customer with the new payment method, and get the source id
                if ($this->stripe_customer_id) {
                    $customer    = $this->stripe_customer_library->update($this->ion_auth->user()->row()->id, $this->stripe_customer_id, array("source" => $post['stripe_token']));
                    $customer_id = $this->stripe_customer_id;
                }
                // We need to create a customer, save the payment method, and charge them accordingly
                else {
                    // Create the customer
                    $customer    = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $post['stripe_token'], $this->ion_auth->user()->row()->email);
                    $customer_id = $customer->id;
                }
            }
            // The user does not want to save the method, so we just charge the card
            else {
                $token = $post['stripe_token'];
            }
        }

        // Check if we have a customer, and chosen source
        else if ($post['passing_method'] && $this->stripe_customer_id) {
            $customer_id = $this->stripe_customer_id;
            $source_id   = $post['passing_method'];
        }
        // Tell them we cant process their request
        else {
            $this->responder->fail("We were unable to process your request1111")->code(500)->respond();
            die();
        }
        $charge = $this->stripe_charge_library->create($contest_id, $token, $customer_id, $source_id, $amount, $metadata, $description);
        return $charge;
    }

    public function setAsDefault()
    {
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if (!$stripe_customer_id) {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
    }

    private function select_winner($post)
    {
        $this->load->library('payout');
        $cid     = $post['contest_id'];
        $sids    = $post['submission_ids'];
        $contest = $this->contest->get($cid);

        $company_name = $this->db->select('name')->from('profiles')->where("id", $contest->owner)->get();
        if ($company_name) {
            $company_name = $company_name->row()->name;
        } else {
            $company_name = '';
        }
        // Check that we are admin or the ccontest owner

        if ($this->ion_auth->user()->row()->id != $contest->owner) {
            if (!$this->ion_auth->is_admin()) {
                $this->responder->fail('You must own the contest to select a winner')->code(403)->respond();
                return;
            }
        }
        $payout = $this->payout->exists(array('contest_id' => $cid));
        if ($payout) {
            $this->responder->fail("An ad has already been chosen as the winner.")->code(500)->respond();
            return;
        }
        // Attempt to create the payouts
        if ($pid = $this->payout->create($cid, $sids)) {
            // Send the email congratulating the user
            // Tell the company they have successfully selected a winner!
            $msg = '[payment][select_winner]contest ' . $post['contest_id'] . ' paid ' . $post['price'] . ' amount for select_winner (' . count($sids) . ' Ads) (origin price:' . $post['origin_price'] . ') ';
            $this->slack->send($msg);
            $this->responder->message(
                "Your ads have been successfully purchased!"
            )->respond();
            $this->load->library('vote');
            foreach ($sids as $sid) {
                $submission = $this->submission->get($sid);

                $this->user->attribute_points($submission->owner, $this->config->item('points_per_winning_submission'));
                $this->vote->dole_out_points($submission->id);
                $this->notification->create($submission->owner, 'submission_chosen', 'submission', $submission->id);
            }

            // We have to notify the winner they won, and all other users that it ended but they didnt win
            $eid         = $this->mailer->id($this->ion_auth->user()->row()->email, 'submission_chosen');
            $submissions = $this->db->select('users.*, submissions.id as sub_id, users.id as uid')->from('submissions')->join('users', 'submissions.owner = users.id', 'left')->where('contest_id', $contest->id)->get()->result();
            foreach ($submissions as $entry) {
                if (in_array($entry->sub_id, $sids)) {
                    // Notify the winner
                    $this->mailer->queue($entry->email, $entry->uid, 'submission_chosen', 'contest', $contest->id);
                } else {
                    // Let them know it ended, but they didnt win
                    $this->mailer->queue($entry->email, $entry->uid, 'winner_announced', 'contest', $contest->id);
                }
            }
            $this->analytics->track(array(
                'event_name'  => "winner_selected",
                'object_type' => "contest",
                'object_id'   => $cid,
            ));
            return;
        } else {
            $this->responder->fail(
                $this->payout->errors() ? $this->payout->errors() : "An unknown error occured"
            )->code(500)->respond();
            return;
        }
    }

    private function ab($post)
    {
        $this->load->library('ad_lib');
        $post['info'] = '[payment][ab_test]contest ' . $post['contest_id'] . ' paid ' . $post['price'] . ' amount for a/b test (origin price:' . $post['origin_price'] . ') ';
        $this->slack->send($post['info']);
        $content = serialize($post);
        if (!$this->ad_lib->go_test_by_company($post['contest_id'], $post['submission_ids'], $content)) {
            $this->responder->fail("An unknown error occured ab test")->code(500)->respond();
        } else {
            $this->mailer->queue('alek@tappyn.com', $this->ion_auth->user()->row()->id, 'ab_test', 'contest', $post['contest_id']);
            $this->responder->message("A/B Test have been set!")->respond();
        }
    }

    private function subscription_update($post)
    {
        $this->load->library('subscription_lib');
        $user_id = $this->ion_auth->user()->row()->id;
        $result  = $this->subscription_lib->update_level($user_id, array('next_level' => $post['sub_level']));
        if ($result === true) {
            if ($this->subscription_lib->msg) {
                $post['info'] = '[payment][subscription] user ' . $user_id . ' paid ' . $post['price'] . ' amount for ' . $this->subscription_lib->msg . ' (origin price:' . $post['origin_price'] . ') ';
                $this->slack->send($post['info']);
            }
            $this->responder->message("update subscription success")->respond();
        } else {
            $this->responder->fail($result)->code(500)->respond();
        }
    }
}
