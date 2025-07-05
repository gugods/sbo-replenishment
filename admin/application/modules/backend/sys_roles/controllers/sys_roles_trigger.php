<?php
class sys_roles_trigger extends Backend
{
	function __construct()
	{
		parent::__construct();
	}

	function set_trigger($action = "", $id = 0, $data = array())
	{
		$obj = array("status" => true, "message" => "");
		if ($action == "delete:before" || $action == "unpublic:before") {
			$obj = $this->checkDeleteAndUnPublic($id, $data);
		} else {
			$obj["status"] = true;
		}
		return $obj;
	}

	private function checkDeleteAndUnPublic($id = 0, $data = [])
	{
		$obj = array("status" => true, "message" => "");
		$num_rows = $this->db
			->where("sys_roles_id", $id)
			->where("sys_status", "active")
			->get("sys_users")
			->num_rows();
		if ($num_rows == 0) {
			$obj["status"] = true;
		} else {
			$obj['message'] = 'Can not delete or unpublic. Because there are users.';
			$obj["status"] = false;
		}
		return $obj;
	}
}
