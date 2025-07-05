<?php
class Token_keys_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function save_token($post = [])
    {
        $save = [
            "employees_id" => $post['employees_id'],
            "access_token" => $post['access_token'],
            "ip_addresses" => $post['ip_addresses'],
            "expire_date" => $post['expire_date'],
            "create_date" => $post['create_date'],
        ];

        $this->db->insert("token_keys", $save);
    }

    function check_ip_address($employees_id = '', $ip_addresses = '')
    {
        $num_rows = $this->db
            ->where('employees_id', $employees_id)
            ->where('ip_addresses', $ip_addresses)
            ->where("expire_date >=", date("Y-m-d H:i:s"))
            ->get("token_keys")
            ->num_rows();

        return ($num_rows > 0) ? true : false;
    }

    function get_expire_token($employees_id = '')
    {
        $result = $this->db
            ->where('employees_id', $employees_id)
            ->where("expire_date >=", date("Y-m-d H:i:s"))
            ->get("token_keys")
            ->first_row("array");

        return $result;
    }

    function remove_token($employees_id = '')
    {
        $this->remove_cache($employees_id);
        $this->db->where('employees_id', $employees_id);
        $this->db->delete("token_keys");
    }

    function remove_all_token()
    {
        $this->db->where("expire_date <", date("Y-m-d H:i:s"));
        $this->db->delete("token_keys");
    }

    function remove_cache($employees_id = '')
    {
        $results = $this->db
            ->select("access_token")
            ->where("employees_id", $employees_id)
            ->get("token_keys")
            ->result_array();

        foreach ($results as $val) {
            del_cache_token($val['access_token']);
        }
    }
}
