<?php
class Authen_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_users($username = "", $password = "")
    {
        $where = array("username" => $username, "sys_status" => "active", "sys_action" => "publish");
        $rows = $this->db
            ->where($where)->get("sys_users")
            ->first_row("array");
        if (isset($rows['password'])) {
            $hash = $rows['password'];
            if (password_verify($password, $hash)) {
                return $rows;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function get_module()
    {
        return $this->db->order_by("priority ASC")->get("sys_module")->result_array();
    }

    function get_permission($id = 0)
    {
        $where = array("sys_roles_id" => $id);
        return $this->db->where($where)->get("sys_permission")->result_array();
    }

    function get_permission_u($id = 0)
    {
        $where = array("sys_users_id" => $id);
        return $this->db->where($where)->get("sys_permission_u")->result_array();
    }

    function update_permission($id = 0, $post = array())
    {
        $this->db->delete("sys_permission", array("sys_roles_id" => $id));
        $modules = $this->get_module();
        $k_mod = "";
        $val_mod = "";
        foreach ($modules as $k_mod => $val_mod) :
            $_view = $val_mod["module"] . "_view";
            $_created = $val_mod["module"] . "_created";
            $_modified = $val_mod["module"] . "_modified";
            $_publish = $val_mod["module"] . "_publish";
            $_deleted = $val_mod["module"] . "_deleted";
            $_import = $val_mod["module"] . "_import";
            $_export = $val_mod["module"] . "_export";
            $_confirmed = $val_mod["module"] . "_confirmed";

            if (@$post[$_view] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "view"));
            if (@$post[$_created] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "created"));
            if (@$post[$_modified] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "modified"));
            if (@$post[$_publish] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "publish"));
            if (@$post[$_deleted] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "deleted"));
            if (@$post[$_import] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "import"));
            if (@$post[$_export] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "export"));
            if (@$post[$_confirmed] == '1') $this->db->insert("sys_permission", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "confirmed"));
        endforeach;
    }

    function update_permission_u($id = 0, $post = array())
    {
        $this->db->delete("sys_permission_u", array("sys_users_id" => $id));
        $modules = $this->get_module();
        $k_mod = "";
        $val_mod = "";
        foreach ($modules as $k_mod => $val_mod) :
            $_view = $val_mod["module"] . "_view";
            $_created = $val_mod["module"] . "_created";
            $_modified = $val_mod["module"] . "_modified";
            $_publish = $val_mod["module"] . "_publish";
            $_deleted = $val_mod["module"] . "_deleted";
            $_import = $val_mod["module"] . "_import";
            $_export = $val_mod["module"] . "_export";
            $_confirmed = $val_mod["module"] . "_confirmed";


            if (@$post[$_view] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "view"));
            if (@$post[$_created] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "created"));
            if (@$post[$_modified] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "modified"));
            if (@$post[$_publish] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "publish"));
            if (@$post[$_deleted] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "deleted"));
            if (@$post[$_import] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "import"));
            if (@$post[$_export] == '1') $this->db->insert("sys_permission_u", array("sys_users_id" => $id, "module" => $val_mod["module"], "action" => "export"));
            if (@$post[$_confirmed] == '1') $this->db->insert("sys_permission_u", array("sys_roles_id" => $id, "module" => $val_mod["module"], "action" => "confirmed"));
        endforeach;
    }
}
