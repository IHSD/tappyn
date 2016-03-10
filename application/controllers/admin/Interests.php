<?php defined("BASEPATH") or exit('No direct script access allowed');

class Interests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interest');
        $this->interest->setDatabase($this->db);
        $this->config->load('interest');
        $active = $this->config->item('may_edit_interests');
        if($active !== TRUE)
        {
            die("Interests are not currently accessible");
        }
        if(!is_ajax())
        {
            $this->load->view('templates/navbar');
        }
    }

    public function index()
    {
        $this->data['interests'] = $this->interest->tree();
        $this->load->view('admin/interests/index', $this->data);
    }

    public function create()
    {
        $this->form_validation->set_rules('name', "Name", 'required|is_unique[interests.name]');
        $this->form_validation->set_rules('display_name', "Name", 'required|is_unique[interests.display_name]');
        $this->form_validation->set_rules('parent_id', "Parent ID", 'required');

        if($this->form_validation->run() === TRUE)
        {
            // Pre-process
        }
        if($this->form_validation->run() === TRUE && $this->interest->create($this->input->post('name'), $this->input->post('display_name'), $this->input->post('parent_id')))
        {
            $this->session->set_flashdata('message', 'Interest successfully created');
        }
        else
        {
            $this->session->set_flashdata('error', ($this->interest->errors() ? $this->interest->errors() : "An unknown error occured"));
        }
        redirect("admin/interests/index", 'refresh');
    }

    public function delete($id = NULL)
    {
        if(is_null($id) || (int) $id == 0)
        {
            $this->session->set_flashdata('You must provide an interest to delete brody');
            redirect('admin/intersts/index', 'refresh');
        }
        if($this->interest->delete($id))
        {
            $this->session->set_flashdata('message', "Interest successfully deleted");
        } else {
            $this->session->set_flashdata('error' , ($this->interest->errors() ? $this->interest->errors() : "An unknown error occured"));
        }
        redirect('admin/interests/index', 'refresh');
    }
}
