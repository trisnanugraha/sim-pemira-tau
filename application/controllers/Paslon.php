<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Paslon extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_paslon');
    }

    public function index()
    {
        $data['judul'] = 'Data Pasangan Calon';
        $data['paslon'] = $this->Mod_paslon->get_all();
        $data['modal_tambah'] = show_my_modal('paslon/modal_tambah_paslon', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel != 'Guest') {
                $js = $this->load->view('paslon/paslon-js', null, true);
                $this->template->views('paslon/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_paslon->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $paslon) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $paslon->jumlah_panen;
            $row[] = $paslon->jumlah_hasil;
            $row[] = $paslon->id_panen;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_paslon->count_all(),
            "recordsFiltered" => $this->Mod_paslon->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_panen->get_panen($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->jumlah_panen = $post['jumlah_panen'];
        $this->jumlah_hasil = $post['jumlah_hasil'];

        $this->Mod_panen->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_lahan');
        $post = $this->input->post();

        $this->id_lahan = $post['id_lahan'];
        $this->jumlah_panen = $post['jumlah_panen'];
        $this->jumlah_hasil = $post['jumlah_hasil'];

        $this->Mod_panen->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_panen');

        $this->Mod_panen->delete($id);
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

        if ($this->input->post('jumlah_panen') == '') {
            $data['inputerror'][] = 'jumlah_panen';
            $data['error_string'][] = 'Jumlah Panen Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('jumlah_hasil') == '') {
            $data['inputerror'][] = 'jumlah_hasil';
            $data['error_string'][] = 'Jumlah Hasil Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Paslon.php */