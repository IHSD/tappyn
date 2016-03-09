<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vouchers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('/', 'refresh');
        }
        if(!is_ajax())
        {
            $this->load->view('templates/navbar');
        }
        $this->load->model('contest');
        $this->load->library('vouchers_library');
    }

    /**
     * Show all vouchers_library
     * @return void
     */
    public function index()
    {
        $this->params = array();
        if($this->input->get('status')) $params['status'] = $this->input->get('status');

        $config['base_url'] = base_url().'admin/vouchers_library/index';
        $config['total_rows'] = $this->vouchers_library->count($this->params);
        $config['per_page'] = 20;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 3;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->vouchers_library->limit($config['per_page']);
        $this->vouchers_library->offset($offset);
        // Set ordering
        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->vouchers_library->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->vouchers_library->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['vouchers'] = $this->vouchers_library->fetch()->result();

        $this->load->view('admin/vouchers/index', $this->data);
    }

    /**
     * Get details of a single voucher
     * @param integer $vid ID of the voucher
     * @return void
     */
    public function show($vid)
    {
        $voucher = $this->vouchers_library->where('id', $vid)->limit(1)->fetch();
        if(!$voucher)
        {
            $this->session->set_flashdata('error', "That voucher doesn't exist");
            redirect('admin/vouchers/index', 'refresh');
        }

        $voucher = $voucher->row();
        $voucher->uses = $this->vouchers_library->uses($vid);
        $this->data['voucher'] = $voucher;
        $this->load->view('admin/vouchers/show', $this->data);
    }

    /**
     * Create a voucher
     * @return void
     */
    public function create()
    {
        $this->form_validation->set_rules('code', 'Code', 'required|min_length[8]|max_length[16]|is_unique[vouchers.code]|alpha_numeric');
        $this->form_validation->set_rules('discount_type', 'Discount Type', 'required|callback_discount_type_check');
        $this->form_validation->set_rules('value', 'Value', 'required');
        $this->form_validation->set_rules('expiration', 'Expiration', 'required|callback_expiration_check');
        if($this->input->post('expiration') == 'time_length')
        {
            $this->form_validation->set_rules('start_time', 'Start Time', "required");
            $this->form_validation->set_rules('stop_time', 'Stop Time', 'required');
        } else {
            $this->form_validation->set_rules('usage_limit', 'Usage Limit', 'required|is_natural_no_zero');
        }
        if($this->form_validation->run() === true)
        {
            $data = array(
                'code' => strtoupper($this->input->post('code')),
                'discount_type' => $this->input->post('discount_type'),
                'value' => $this->input->post('value'),
                'expiration' => $this->input->post('expiration'),
                'starts_at' => $this->input->post('start_time') ? strtotime($this->input->post('start_time')) : NULL,
                'ends_at' => $this->input->post('stop_time') ? strtotime($this->input->post('stop_time')) : NULL,
                'usage_limit' => $this->input->post('usage_limit'),
                'created_at' => time()
            );
        }
        if($this->form_validation->run() === true && $vid = $this->vouchers_library->create($data))
        {
            // Successfully created the voucher code
            $this->data['message'] = "Voucher {$this->input->get('code')} successfully created";
        } else {
            // There was an error creating the voucher
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->vouchers_library->errors() ? $this->voucher_library->errors() : "An unknown error occured"));
        }
        $this->load->view('admin/vouchers/create', $this->data);
    }

    /**
     * Activate a voucher
     * @param integer $vid ID of the voucher
     * @return void
     */
    public function activate($vid)
    {
        if($this->vouchers_library->update($vid, array('status' => 1)))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail("There was an error activating that voucher")->code(500)->respond();
        }
    }

    /**
     * Deactivate a voucher
     * @param integer $vid ID of the voucher
     * @return void
     */
    public function deactivate($vid)
    {
        if($this->vouchers_library->update($vid, array('status' => 0)))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail("There was an error deactivating that voucher")->code(500)->respond();
        }
    }

    /**
     * Check if a voucher is valid. Does not guarantee that it can be used,
     * just that it is valid at time of request.
     * @param integer $vid ID of the voucher
     * @return void
     */
    public function valid($vid)
    {

    }

    /**
     * Update a voucher
     * @return void
     */
    public function update()
    {

    }

    /**
     * Delete a voucher
     * @return void
     */
    public function delete()
    {

    }

    function discount_type_check($param)
    {
        $valid_params = array(
            'amount',
            'percentage'
        );
        if(in_array($param, $valid_params))
        {
            return TRUE;
        }
        $this->form_validation->set_message("discount_type_check", "The %s field must be either amount or percentage");
        return FALSE;
    }

    function expiration_check($param)
    {
        $valid_params = array(
            'uses',
            'time_length'
        );
        if(in_array($param, $valid_params))
        {
            return TRUE;
        }
        $this->form_validation->set_rules('expiration_check', "The %s field must be either uses or time_length");
        return FALSE;
    }
}
