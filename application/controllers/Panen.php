<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Panen extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_panen');
        $this->load->model('Mod_lahan');
    }

    public function index()
    {
        $data['judul'] = 'Panen';
        $data['lahan'] = $this->Mod_lahan->get_all();
        $data['modal_tambah'] = show_my_modal('panen/modal_tambah_panen', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel == 'Guest') {
                $js = $this->load->view('panen/panen-guest-js', null, true);
                $this->template->views('panen/home-guest', $data, $js);
            } else {
                $js = $this->load->view('panen/panen-js', null, true);
                $this->template->views('panen/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_panen->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $panen) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $panen->jumlah_panen;
            $row[] = $panen->jumlah_hasil;
            $row[] = $panen->id_panen;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_panen->count_all(),
            "recordsFiltered" => $this->Mod_panen->count_filtered(),
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

/* End of file Panen.php */