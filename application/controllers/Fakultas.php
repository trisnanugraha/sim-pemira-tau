<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fakultas extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_fakultas');
    }

    public function index()
    {
        $data['judul'] = 'Data Fakultas';
        $data['modal_tambah'] = show_my_modal('fakultas/modal_tambah_fakultas', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel != 'Guest') {
                $js = $this->load->view('fakultas/fakultas-js', null, true);
                $this->template->views('fakultas/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_fakultas->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $f) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $f->nama_fakultas;
            $row[] = $f->id_fakultas;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_fakultas->count_all(),
            "recordsFiltered" => $this->Mod_fakultas->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_fakultas->get_fakultas($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_fakultas = random_string('alnum', 25);
        $this->nama_fakultas = $post['nama_fakultas'];

        $this->Mod_fakultas->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_fakultas');
        $post = $this->input->post();

        $this->nama_fakultas = $post['nama_fakultas'];

        $this->Mod_fakultas->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_fakultas');

        $this->Mod_fakultas->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_fakultas') == '') {
            $data['inputerror'][] = 'nama_fakultas';
            $data['error_string'][] = 'Nama Fakultas Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Fakultas.php */