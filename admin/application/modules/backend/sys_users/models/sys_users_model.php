<?php
class Sys_users_model extends CI_Model
{
	var $USER_ID = '';
	var $FIELDS = 'sys_users_id,fullname,username,password,email,image,sys_roles_id,assigned,sys_status,sys_action,createdate,createby,lastupdate,updateby';

	function __construct()
	{
		parent::__construct();
		$this->load->model("authen_model");
		$this->USER_ID = $this->session->userdata("USER_ID");
		$this->ROLES_ID = $this->session->userdata("ROLES_ID");
	}

	function get_rows($post = array())
	{
		if (@$post["sys_action"] != "delete") {
			$this->db->where("sys_users.sys_status", "active");
		} else {
			$this->db->where("sys_users.sys_status", "archived");
		}

		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_users.sys_action = 'created' OR sys_users.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_users.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("(sys_users.username LIKE '%" . $post["keyword"] . "%' OR sys_users.fullname LIKE '%" . $post["keyword"] . "%')");
		if ($this->ROLES_ID != '1') $this->db->where('sys_roles.sys_roles_id <>', 1);

		$rows = $this->db
			->join("sys_roles", "sys_roles.sys_roles_id = sys_users.sys_roles_id", "LEFT")
			->count_all_results("sys_users");

		return $rows;
	}

	function get_rows_publish($post = array())
	{
		$post['sys_action'] = "publish";
		return $this->get_rows($post);
	}

	function get_rows_modified($post = array())
	{
		$post['sys_action'] = "modified";
		return $this->get_rows($post);
	}

	function get_rows_unpublish($post = array())
	{
		$post['sys_action'] = "unpublish";
		return $this->get_rows($post);
	}

	function get_rows_delete($post = array())
	{
		$post["sys_action"] = "delete";
		return $this->get_rows($post);
	}

	function get_page($option = array(), $start, $end)
	{
		$post = $option["post"];

		if (@$post["sys_action"] != "delete") {
			$this->db->where("sys_users.sys_status", "active");
		} else {
			$this->db->where("sys_users.sys_status", "archived");
		}

		if (@$post["sys_action"] != "") {
			if ($post['sys_action'] == "unpublish") {
				$this->db->where("(sys_users.sys_action = 'created' OR sys_users.sys_action = 'unpublish')", null, false);
			} else {
				$this->db->where("sys_users.sys_action", $post['sys_action']);
			}
		}

		if (@$post["keyword"] != "") $this->db->where("(sys_users.username LIKE '%" . $post["keyword"] . "%' OR sys_users.fullname LIKE '%" . $post["keyword"] . "%')");
		if ($this->ROLES_ID != '1') $this->db->where('sys_roles.sys_roles_id <>', 1);

		$sorting = "";
		if (@$option["sorting"] == "update_name") {
			$sorting = "updated.username";
		} else if (@$option["sorting"] == "create_name") {
			$sorting = "created.username";
		} else if (strpos(@$option["sorting"], ".") !== false) {
			$sorting = $option["sorting"];
		} else {
			$sorting = "sys_users." . $option["sorting"];
		}

		$sql =  $this->db->select("sys_users.*")
			->select("created.username AS create_name,updated.username AS update_name")
			->select("sys_roles.roles_name")
			->join("sys_users created", "created.sys_users_id = sys_users.createby", "LEFT")
			->join("sys_users updated", "updated.sys_users_id = sys_users.updateby", "LEFT")
			->join("sys_roles", "sys_roles.sys_roles_id = sys_users.sys_roles_id", "LEFT")
			->order_by($sorting, @$option["orderby"])
			->from("sys_users")
			->query_string();
		$newsql = get_page($sql, $this->db->dbdriver, $start, $end);
		return  $this->db->query($newsql)->result_array();
	}

	function count_log($id = 0, &$rows, &$totalpage)
	{
		return 0;
	}

	function get_log($option = array(), $start, $end)
	{
		return array();
	}

	function get_id($id = 0)
	{
		$where = array("sys_users_id" => $id, "sys_status" => "active");
		return $this->db->get_where("sys_users", $where)->first_row("array");
	}

	function insert($post = array())
	{
		$save = array();

		if (isset($post["fullname"])) $save["fullname"] = $post["fullname"];
		if (isset($post["username"])) $save["username"] = $post["username"];
		if (isset($post["password"])) $save["password"] = password_hash($post['password'], PASSWORD_DEFAULT);
		if (isset($post["email"])) $save["email"] = $post["email"];
		$fileName = doUploadImage("image", "sys_users");
		if ($fileName != "") $save["image"] = $fileName;
		if (isset($post["sys_roles_id"])) $save["sys_roles_id"] = $post["sys_roles_id"];
		if ($this->ROLES_ID == '1') $save["assigned"] = @$post["assigned"];

		$save["createdate"] = date("Y-m-d H:i:s");
		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["createby"] = $this->USER_ID;
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "created";
		$save["sys_status"] = "active";

		$this->db->insert("sys_users", $save);
		$val = $this->db->insert_id();

		$this->update_child($val);
		if ($this->ROLES_ID == '1') $this->authen_model->update_permission_u($val, $post);

		return $val;
	}


	function update($post = array())
	{
		$val = @$post["id"];
		$where_update = array("sys_users_id" => $val, "sys_status" => "active");

		$save = array();
		if (isset($post["fullname"])) $save["fullname"] = $post["fullname"];
		if (isset($post["username"])) $save["username"] = $post["username"];
		if (isset($post["password"]) && $post["password"] != "") $save["password"] = password_hash($post['password'], PASSWORD_DEFAULT);
		if (isset($post["email"])) $save["email"] = $post["email"];
		$fileName = doUploadImage("image", "sys_users");
		if ($fileName != "") $save["image"] = $fileName;
		if (isset($post["sys_roles_id"])) $save["sys_roles_id"] = $post["sys_roles_id"];
		if ($this->ROLES_ID == '1') $save["assigned"] = @$post["assigned"];


		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["updateby"] = $this->USER_ID;
		$save["sys_action"] = "modified";
		$save["sys_status"] = "active";
		$this->db->update("sys_users", $save, $where_update);

		if ($this->ROLES_ID == '1') $this->authen_model->update_permission_u($val, $post);
	}

	function update_users($post)
	{
		$where_update = array("sys_users_id" => $this->USER_ID, "sys_status" => "active");

		$save = array();

		if (isset($post["fullname"])) $save["fullname"] = $post["fullname"];
		if (isset($post["password"]) && $post["password"] != "") $save["password"] = password_hash($post['password'], PASSWORD_DEFAULT);
		$fileName = doUploadImage("image", "sys_users");
		if ($fileName != "") $save["image"] = $fileName;
		if (isset($post["email"])) $save["email"] = $post["email"];

		$save["lastupdate"] = date("Y-m-d H:i:s");
		$save["updateby"] = $this->USER_ID;

		$this->db->update("sys_users", $save, $where_update);
	}

	function savePublish($val = 0, $cmd = "")
	{
		$where_update = array("sys_users_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_users", $update, $where_update);
	}

	function delete($val)
	{
		$where_update = array("sys_users_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "archived", "sys_action" => "delete", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_users", $update, $where_update);
	}

	function undelete($val)
	{
		$where_update = array("sys_users_id" => $val, "sys_status" => "archived");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_users", $update, $where_update);
	}

	function delete_file($post = array())
	{
		$val = $post["id"];
		$where = array("sys_users_id" => $val, "sys_status" => "active");
		$update = array($post['filename'] => "");
		$this->db->update("sys_users", $update, $where);
	}

	function save_public($val)
	{
		$where_update = array("sys_users_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "publish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_users", $update, $where_update);
	}

	function save_unpublic($val)
	{
		$where_update = array("sys_users_id" => $val, "sys_status" => "active");
		$update = array("sys_status" => "active", "sys_action" => "unpublish", "lastupdate" => date("Y-m-d H:i:s"), "updateby" => $this->USER_ID);
		$this->db->update("sys_users", $update, $where_update);
	}

	function update_child($sys_parent_id = 0)
	{
	}
}
