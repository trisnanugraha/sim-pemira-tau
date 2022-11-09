<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Budidaya extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_budidaya');
    }

    public function index()
    {
        $data['judul'] = 'Budidaya';
        $data['modal_tambah'] = show_my_modal('budidaya/modal_tambah_budidaya', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel == 'Guest') {
                $js = $this->load->view('budidaya/budidaya-guest-js', null, true);
                $this->template->views('budidaya/home-guest', $data, $js);
            } else {
                $js = $this->load->view('budidaya/budidaya-js', null, true);
                $this->template->views('budidaya/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_budidaya->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $budidaya) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $budidaya->kd_produksi;
            $row[] = $budidaya->nama_produksi;
            $row[] = $budidaya->id_produksi;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_budidaya->count_all(),
            "recordsFiltered" => $this->Mod_budidaya->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_budidaya->get_budidaya($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->kd_produksi = $post['kd_produksi'];
        $this->nama_produksi = $post['nama_produksi'];

        $this->Mod_budidaya->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_produksi');
        $post = $this->input->post();

        $this->kd_produksi = $post['kd_produksi'];
        $this->nama_produksi = $post['nama_produksi'];

        $this->Mod_budidaya->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_produksi');

        $this->Mod_budidaya->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('kd_produksi') == '') {
            $data['inputerror'][] = 'kd_produksi';
            $data['error_string'][] = 'Kode Produksi Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nama_produksi') == '') {
            $data['inputerror'][] = 'nama_produksi';
            $data['error_string'][] = 'Nama Produksi Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }
    }
}

/* End of file Budidaya.php */