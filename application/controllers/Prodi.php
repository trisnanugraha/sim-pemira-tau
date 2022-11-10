<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Prodi extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_fakultas');
        $this->load->model('Mod_prodi');
    }

    public function index()
    {
        $data['judul'] = 'Data Program Studi';
        $data['fakultas'] = $this->Mod_fakultas->get_all();
        $data['modal_tambah'] = show_my_modal('prodi/modal_tambah_prodi', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel != 'Guest') {
                $js = $this->load->view('prodi/prodi-js', null, true);
                $this->template->views('prodi/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_prodi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $p->nama_prodi;
            $row[] = $p->nama_fakultas;
            $row[] = $p->id_prodi;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_prodi->count_all(),
            "recordsFiltered" => $this->Mod_prodi->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_prodi->get_prodi($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_prodi = random_string('alnum', 25);
        $this->id_fakultas = $post['id_fakultas'];
        $this->nama_prodi = $post['nama_prodi'];

        $this->Mod_prodi->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_prodi');
        $post = $this->input->post();

        $this->id_fakultas = $post['id_fakultas'];
        $this->nama_prodi = $post['nama_prodi'];

        $this->Mod_prodi->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_prodi');

        $this->Mod_prodi->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('id_fakultas') == '') {
            $data['inputerror'][] = 'id_fakultas';
            $data['error_string'][] = 'Fakultas Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nama_prodi') == '') {
            $data['inputerror'][] = 'nama_prodi';
            $data['error_string'][] = 'Nama Prodi Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Prodi.php */