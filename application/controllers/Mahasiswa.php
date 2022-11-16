<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mahasiswa extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_mahasiswa');
        $this->load->model('Mod_fakultas');
        $this->load->model('Mod_prodi');
        $this->load->model('Mod_tahun_angkatan');
    }

    public function index()
    {
        $data['judul'] = 'Data Mahasiswa';
        $data['fakultas'] = $this->Mod_fakultas->get_all();
        $data['prodi'] = $this->Mod_prodi->get_all();
        $data['ta'] = $this->Mod_tahun_angkatan->get_all();

        $data['paslon'] = $this->Mod_mahasiswa->get_all();
        $data['modal_tambah'] = show_my_modal('mahasiswa/modal_tambah_mahasiswa', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel != 'Guest') {
                $js = $this->load->view('mahasiswa/mahasiswa-js', null, true);
                $this->template->views('mahasiswa/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_mahasiswa->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $mhs) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $mhs->nama_lengkap;
            $row[] = $mhs->nim;
            $row[] = $mhs->nama_fakultas;
            $row[] = $mhs->nama_prodi;
            $row[] = $mhs->tahun_angkatan;
            $row[] = $mhs->id_mahasiswa;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_mahasiswa->count_all(),
            "recordsFiltered" => $this->Mod_mahasiswa->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_mahasiswa->get_mahasiswa($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_mahasiswa = random_string('alnum', 25);
        $this->nama_lengkap = $post['nama_lengkap'];
        $this->nim = $post['nim'];
        $this->id_fakultas = $post['id_fakultas'];
        $this->id_prodi = $post['id_prodi'];
        $this->id_tahun_angkatan = $post['id_tahun_angkatan'];

        $this->Mod_mahasiswa->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_mahasiswa');
        $post = $this->input->post();

        $this->nama_lengkap = $post['nama_lengkap'];
        $this->nim = $post['nim'];
        $this->id_fakultas = $post['id_fakultas'];
        $this->id_prodi = $post['id_prodi'];
        $this->id_tahun_angkatan = $post['id_tahun_angkatan'];

        $this->Mod_mahasiswa->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_mahasiswa');

        $this->Mod_mahasiswa->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('nama_lengkap') == '') {
            $data['inputerror'][] = 'nama_lengkap';
            $data['error_string'][] = 'Nama Lengkap Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('nim') == '') {
            $data['inputerror'][] = 'nim';
            $data['error_string'][] = 'NIM Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('id_fakultas') == '') {
            $data['inputerror'][] = 'id_fakultas';
            $data['error_string'][] = 'Fakultas Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('id_prodi') == '') {
            $data['inputerror'][] = 'id_prodi';
            $data['error_string'][] = 'Program Studi Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('id_tahun_angkatan') == '') {
            $data['inputerror'][] = 'id_tahun_angkatan';
            $data['error_string'][] = 'Tahun Angkatan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Mahasiswa.php */