<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_tanam extends CI_Model
{

    var $table = 'tbl_tanam';
    var $column_order = array('', 'lokasi', 'kd_produksi', 'tgl_tanam', 'jml_tanam');
    var $column_search = array('lokasi', 'kd_produksi', 'tgl_tanam', 'jml_tanam');
    var $order = array('id_tanam' => 'asc'); // default order 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query()
    {

        $this->db->select('a.*,b.lokasi, c.kd_produksi');
        $this->db->join('tbl_lahan b', 'a.id_lahan=b.id_lahan');
        $this->db->join('tbl_budidaya c', 'a.id_produksi=c.id_produksi');
        $this->db->from("{$this->table} a");
        $this->db->order_by('a.id_tanam', 'asc');

        $i = 0;

        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {

        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function get_all()
    {
        return $this->db->get($this->table)
            ->result();
    }

    function get_tanam($id)
    {
        $this->db->where('id_tanam', $id);
        return $this->db->get($this->table)->row();
    }

    function get_foto($id)
    {
        $this->db->select('foto, foto2, foto3');
        $this->db->from($this->table);
        $this->db->where('id_kegiatan', $id);
        return $this->db->get();
    }

    function insert($data)
    {
        $insert = $this->db->insert($this->table, $data);
        return $insert;
    }

    function update($id, $data)
    {
        $this->db->where('id_tanam', $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where('id_tanam', $id);
        $this->db->delete($this->table);
    }

    function total_rows()
    {
        $data = $this->db->get($this->table);
        return $data->num_rows();
    }
}

/* End of file Mod_lahan.php */
