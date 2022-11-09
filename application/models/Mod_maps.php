<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Mod_maps extends CI_Model
{
    function fetch_data()
    {
        $this->db->select('a.lokasi, 
                           a.longitude,
                           a.latitude,
                           b.tgl_pemupukan,
                           c.tgl_tanam,
                           c.jml_tanam,
                           d.jumlah_panen');
        $this->db->join('tbl_pemupukan b', 'a.id_lahan=b.id_lahan');
        $this->db->join('tbl_tanam c', 'a.id_lahan=c.id_lahan');
        $this->db->join('tbl_panen d', 'a.id_lahan=d.id_lahan');
        $this->db->from("tbl_lahan a");
        return $this->db->get()->result();
    }
}

/* End of file Mod_maps.php */
