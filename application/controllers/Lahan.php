<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lahan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mod_lahan');
    }

    public function index()
    {
        $data['judul'] = 'Lahan';
        $data['modal_tambah'] = show_my_modal('lahan/modal_tambah_lahan', $data);

        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in != TRUE || empty($logged_in)) {
            redirect('login');
        } else {
            $checklevel = $this->session->userdata('hak_akses');

            if ($checklevel == 'Guest') {
                $js = $this->load->view('lahan/lahan-guest-js', null, true);
                $this->template->views('lahan/home-guest', $data, $js);
            } else {
                $js = $this->load->view('lahan/lahan-js', null, true);
                $this->template->views('lahan/home', $data, $js);
            }
        }
    }

    public function ajax_list()
    {
        ini_set('memory_limit', '512M');
        set_time_limit(3600);
        $list = $this->Mod_lahan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $lahan) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $lahan->lokasi;
            $row[] = $lahan->longitude;
            $row[] = $lahan->latitude;
            $row[] = $lahan->id_lahan;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Mod_lahan->count_all(),
            "recordsFiltered" => $this->Mod_lahan->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function edit($id)
    {
        $data = $this->Mod_lahan->get_lahan($id);
        echo json_encode($data);
    }

    public function insert()
    {
        $this->_validate();

        $post = $this->input->post();

        $this->lokasi = $post['lokasi'];
        $this->longitude = $post['longitude'];
        $this->latitude = $post['latitude'];

        $this->Mod_lahan->insert($this);
        echo json_encode(array("status" => TRUE));
    }

    public function update()
    {
        $this->_validate();
        $id      = $this->input->post('id_lahan');
        $post = $this->input->post();

        $this->lokasi = $post['lokasi'];
        $this->longitude = $post['longitude'];
        $this->latitude = $post['latitude'];

        $this->Mod_lahan->update($id, $this);
        echo json_encode(array("status" => TRUE));
    }

    public function delete()
    {
        $id = $this->input->post('id_lahan');

        $this->Mod_lahan->delete($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('lokasi') == '') {
            $data['inputerror'][] = 'lokasi';
            $data['error_string'][] = 'Lahan Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('longitude') == '') {
            $data['inputerror'][] = 'longitude';
            $data['error_string'][] = 'Longitude Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($this->input->post('latitude') == '') {
            $data['inputerror'][] = 'latitude';
            $data['error_string'][] = 'Latitude Tidak Boleh Kosong';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }
}

/* End of file Lahan.php */