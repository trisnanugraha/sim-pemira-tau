<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Maps extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fungsi');
        $this->load->library('user_agent');
        $this->load->helper('myfunction_helper');
        $this->load->model('Mod_user');
        $this->load->model('Mod_userlevel');
        $this->load->model('Mod_maps');
    }

    function index()
    {
        $data['judul'] = 'Maps';

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $js = $this->load->view('maps/maps-js', null, true);
            $this->template->views('maps/home', $data, $js);
        }
    }

    function fetch_data(){
        $data = $this->Mod_maps->fetch_data();
        echo json_encode($data);
    }
}
/* End of file Maps.php */
