<?php
class Sys_junk_model extends CI_Model 
{
    var $USER_ID = '';
     var $FIELDS = "sys_contacts_id,fullname,email,subject,message,senddate,reader,sys_status,sys_action,createdate,createby,lastupdate,updateby";

    function __construct()
    {
        parent::__construct();
				$this->USER_ID = $this->session->userdata("USER_ID");
    }

    function get_rows($post = array())
    {
    	
		$this->db->where("sys_contacts.sys_status","archived");
        $this->db->where("sys_contacts.sys_action","delete");

    	if(@$post["keyword"]!="") $this->db->where("( sys_contacts.fullname LIKE '%".$post["keyword"]."%' OR sys_contacts.email LIKE '%".$post["keyword"]."%' OR sys_contacts.subject LIKE '%".$post["keyword"]."%' )");
		
    	if(@$post["reader"]!="") $this->db->where('sys_contacts.reader',$post["reader"]);
		

    	$rows = $this->db
    			->count_all_results("sys_contacts_log sys_contacts");

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

	function get_page($option = array(),$start,$end)
	{
		$post = $option["post"];
		
		$this->db->where("sys_contacts.sys_status","archived");
        $this->db->where("sys_contacts.sys_action","delete");

		if(@$post["keyword"]!="") $this->db->where("( sys_contacts.fullname LIKE '%".$post["keyword"]."%' OR sys_contacts.email LIKE '%".$post["keyword"]."%' OR sys_contacts.subject LIKE '%".$post["keyword"]."%' )");
		 
		if(@$post["reader"]!="") $this->db->where('sys_contacts.reader',$post["reader"]);
		

		$sorting = "";
		if(@$option["sorting"]=="update_name") {
			$sorting = "updated.username";
		} else if(@$option["sorting"]=="create_name") {
			$sorting = "created.username";
		} else if(strpos(@$option["sorting"],".")!==false) {
			$sorting = $option["sorting"];
		} else {
			$sorting = "sys_contacts.".$option["sorting"];
		}

		$sql =  $this->db->select("sys_contacts.*")
				->select("created.username AS create_name,updated.username AS update_name")
				->select("sys_contacts.sys_contacts_id AS sys_junk_id")
				->join("sys_users created","created.sys_users_id = sys_contacts.createby","LEFT")
				->join("sys_users updated","updated.sys_users_id = sys_contacts.updateby","LEFT")
				->order_by($sorting,@$option["orderby"])
				->from("sys_contacts_log sys_contacts")
				->query_string();
		$newsql = get_page($sql,$this->db->dbdriver,$start,$end);
		return  $this->db->query($newsql)->result_array();
	}


	function count_log($id = 0,&$rows,&$totalpage)
    {
        return 0;
    }

    function get_log($option = array(),$start,$end)
    {
        return array();
    }

    function get_id($id = 0)
    {
        $where = array("sys_contacts_id"=>$id,"sys_status"=>"archived","sys_action"=>"delete");
        return $this->db->get_where("sys_contacts_log sys_contacts",$where)->first_row("array");
    }

    function delete($val)
    {
        $where_update = array("sys_contacts_id"=>$val,"sys_status"=>"archived","sys_action"=>"delete");
        $this->db->delete("sys_contacts_log",$where_update);
    }

    function save_public($val)
    {   
        $where_update = array("sys_contacts_id"=>$val,"sys_status"=>"archived","sys_action"=>"delete");
        $update = array("sys_status"=>"active","sys_action"=>"publish","lastupdate"=>date("Y-m-d H:i:s"),"updateby"=>$this->USER_ID);
        $this->db->update("sys_contacts_log",$update,$where_update);

        $this->db->query("INSERT INTO sys_contacts (".$this->FIELDS.") SELECT ".$this->FIELDS." FROM sys_contacts_log WHERE sys_contacts_id = '{$val}' AND sys_status = 'active'");
        $where = array("sys_contacts_id"=>$val,"sys_status"=>"active");
        $this->db->delete("sys_contacts_log",$where);
    }

}