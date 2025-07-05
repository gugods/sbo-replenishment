<?php
class sys_junk_trigger extends Backend
{
	function __construct()
	{
		parent::__construct();
	}

	function set_trigger($action = "", $id = 0, $data = array())
	{
		$obj = array("status" => true, "message" => "");
		if ($action == "view:display") {
			$this->updateReader($id, $data);
			$obj["status"] = true;
		} else {
			$obj["status"] = true;
		}
		return $obj;
	}

	private function updateReader($id = 0, $data = [])
	{
		$save = array("reader" => "Read");
		$this->db->where("sys_contacts_id", $id);
		$this->db->update("sys_contacts", $save);
	}
}
