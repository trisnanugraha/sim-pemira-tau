<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tanam extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_tanam');
        $this->load->model('Mod_lahan');
        $this->load->model('Mod_budidaya');
    }

    public function index()
    {
        $data['judul'] = 'Tanam';
        $data['lahan'] = $this->Mod_lahan->get_all();
        $data['budidaya'] = $this->Mod_budidaya->get_all();

        $data['modal_tambah'] = show_my_modal('tanam/modal_tambah_tanam', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel == 'Guest') {
                $js = $this->load->view('tanam/tanam-guest-js', null, true);
                $this->template->views('tanam/home-guest', $data, $js);
            } else {
                $js = $this->load->view('tanam/tanam-js', null, true);
                $this->template->views('tanam/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_tanam->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $tanam) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $tanam->lokasi;
            $row[] = $tanam->kd_produksi;
            $row[] = tgl_indonesia($tanam->tgl_tanam);
            $row[] = $tanam->jml_tanam;
            $row[] = $tanam->id_tanam;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_tanam->count_all(),
            "recordsFiltered" => $this->Mod_tanam->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_tanam->get_tanam($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->id_produksi = $post['id_produksi'];
        $this->tgl_tanam = $post['tgl_tanam'];
        $this->jml_tanam = $post['jml_tanam'];

        $this->Mod_tanam->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_tanam');
        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->id_produksi = $post['id_produksi'];
        $this->tgl_tanam = $post['tgl_tanam'];
        $this->jml_tanam = $post['jml_tanam'];

        $this->Mod_tanam->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_tanam');

        $this->Mod_tanam->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('id_lahan') == '') {
            $data['inputerror'][] = 'id_lahan';
            $data['error_string'][] = 'Lahan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('id_produksi') == '') {
            $data['inputerror'][] = 'id_produksi';
            $data['error_string'][] = 'Kode Produksi Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('tgl_tanam') == '') {
            $data['inputerror'][] = 'tgl_tanam';
            $data['error_string'][] = 'Tanggal Tanam Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jml_tanam') == '') {
            $data['inputerror'][] = 'jml_tanam';
            $data['error_string'][] = 'Jumlah Tanaman Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Tanam.php */