<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemupukan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_pemupukan');
        $this->load->model('Mod_lahan');
    }

    public function index()
    {
        $data['judul'] = 'Pemupukan';
        $data['lahan'] = $this->Mod_lahan->get_all();

        $data['modal_tambah'] = show_my_modal('pemupukan/modal_tambah_pemupukan', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel == 'Guest') {
                $js = $this->load->view('pemupukan/pemupukan-guest-js', null, true);
                $this->template->views('pemupukan/home-guest', $data, $js);
            } else {
                $js = $this->load->view('pemupukan/pemupukan-js', null, true);
                $this->template->views('pemupukan/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_pemupukan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $pemupukan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $pemupukan->lokasi;
            $row[] = tgl_indonesia($pemupukan->tgl_pemupukan);
            $row[] = $pemupukan->jumlah_pupuk;
            $row[] = $pemupukan->lama_pupuk;
            $row[] = $pemupukan->id_pemupukan;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_pemupukan->count_all(),
            "recordsFiltered" => $this->Mod_pemupukan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_pemupukan->get_pemupukan($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->tgl_pemupukan = $post['tgl_pemupukan'];
        $this->jumlah_pupuk = $post['jumlah_pupuk'];
        $this->lama_pupuk = $post['lama_pupuk'];

        $this->Mod_pemupukan->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_pemupukan');
        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->tgl_pemupukan = $post['tgl_pemupukan'];
        $this->jumlah_pupuk = $post['jumlah_pupuk'];
        $this->lama_pupuk = $post['lama_pupuk'];

        $this->Mod_pemupukan->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_pemupukan');

        $this->Mod_pemupukan->delete($id);
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

        if ($this->input->post('tgl_pemupukan') == '') {
            $data['inputerror'][] = 'tgl_pemupukan';
            $data['error_string'][] = 'Tanggal Pemupukan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jumlah_pupuk') == '') {
            $data['inputerror'][] = 'jumlah_pupuk';
            $data['error_string'][] = 'Jumlah Pupuk Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('lama_pupuk') == '') {
            $data['inputerror'][] = 'lama_pupuk';
            $data['error_string'][] = 'Lama Pupuk Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Pemupukan.php */