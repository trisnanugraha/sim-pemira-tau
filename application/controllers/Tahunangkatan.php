<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tahunangkatan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_tahun_angkatan');
    }

    public function index()
    {
        $data['judul'] = 'Data Tahun Angkatan';
        $data['fakultas'] = $this->Mod_tahun_angkatan->get_all();
        $data['modal_tambah'] = show_my_modal('tahun_angkatan/modal_tambah_tahun_angkatan', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel != 'Guest') {
                $js = $this->load->view('tahun_angkatan/tahun-angkatan-js', null, true);
                $this->template->views('tahun_angkatan/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_tahun_angkatan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $t) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $t->tahun_angkatan;
            $row[] = $t->id_tahun_angkatan;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_tahun_angkatan->count_all(),
            "recordsFiltered" => $this->Mod_tahun_angkatan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_tahun_angkatan->get_tahun_angkatan($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_tahun_angkatan = random_string('alnum', 25);
        $this->tahun_angkatan = $post['tahun_angkatan'];

        $this->Mod_tahun_angkatan->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_tahun_angkatan');
        $post = $this->input->post();

        $this->tahun_angkatan = $post['tahun_angkatan'];

        $this->Mod_tahun_angkatan->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_tahun_angkatan');

        $this->Mod_tahun_angkatan->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('tahun_angkatan') == '') {
            $data['inputerror'][] = 'tahun_angkatan';
            $data['error_string'][] = 'Tahun Angkatan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Tahunangkatan.php */