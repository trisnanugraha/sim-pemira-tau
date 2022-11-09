<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('fungsi');
        $this->load->library('user_agent');
        $this->load->helper('myfunction_helper');
        $this->load->model('Mod_user');
        $this->load->model('Mod_userlevel');
        $this->load->model('Mod_aktivasi_user');
        $this->load->model('Mod_userlevel');
        $this->load->model('Mod_lahan');
        $this->load->model('Mod_budidaya');
        $this->load->model('Mod_panen');
        $this->load->model('Mod_tanam');
        $this->load->model('Mod_dashboard');
        // backButtonHandle();
    }

    function index()
    {
        $data['judul'] = 'Dashboard';
        $data['lahan'] = $this->Mod_lahan->total_rows();
        $data['budidaya'] = $this->Mod_budidaya->total_rows();
        $data['panen'] = $this->Mod_panen->total_rows();
        $data['tanam'] = $this->Mod_tanam->total_rows();

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            // $this->template->load('layoutbackend', 'dashboard/view_dashboard', $data);
            $checklevel = $this->_cek_status($this->session->userdata['id_level']);

            if ($checklevel == 'Guest') {
                $js = $this->load->view('dashboard/dashboard-js', null, true);
            } else {
                $js = $this->load->view('dashboard/dashboard-js', null, true);
            }
            $this->template->views('dashboard/home', $data, $js);
        }

        // echo json_encode($data['dataPenelitian']);
        // echo json_encode($data['dataPKM']);
    }

    private function _cek_status($id_level)
    {
        $nama_level = $this->Mod_userlevel->getUserlevel($id_level);
        return $nama_level->nama_level;
    }
}
/* End of file Dashboard.php */
