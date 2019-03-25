<?php

class base_model extends CI_Model{
    function __construct(){
        parent :: __construct();
    }
// --select--update--insert--delete--  //
function get_data($table,$select, $ret = 'result', $where=NULL,$limit=NULL , $offset=NULL ,$order_by=NULL ,$group_by=NULL, $between = NULL){
    
    $this->db->select($select);
    
    if($where !=NULL){
        $this -> db -> where($where);
    }
    if($between != NULL){
        $this->db->where($between);
    }
    if($group_by !=NULL){
        $this -> db -> group_by($group_by);
    }
    if($order_by !=NULL){
        $this->db->order_by($order_by[0] , $order_by[1]);
    }
    $query = $this->db->get($table , $limit , $offset);
    
    if($ret != 'result'){
        return $query->row();
    }else{
     return $query->result();
    }
}

function get_data_join($from , $join , $select ,$join_where ,$ret = 'result' , $where = NULL, $limit = NULL , $offset = NULL , $order_by = NULL , $join2 = NULL , $join3 = NULL , $between = NULL ){
    $this->db->select($select);
    $this->db->from($from);
    $this->db->join($join , $join_where);
	if($join2 != NULL){
		$this->db->join($join2[0] , $join2[1]);
	}
	if($join3 != NULL){
		$this->db->join($join3[0] , $join3[1]);
	}
    if($where != NULL){
        $this->db->where($where);
    }
    if($between != NULL){
        $this->db->where($between);
    }
    if($order_by != NULL){
        $this->db->order_by($order_by[0] , $order_by[1]);
    }
   if($limit != NULL){
        $this->db->limit($limit , $offset);
    }
    $query = $this->db->get();
    if($ret != 'result'){
        return $query->row();
    }else{
        return $query->result();
    }
}
function get_data_left($from , $join , $select ,$join_where, $side ,$ret = 'result' , $where = NULL, $limit = NULL , $offset = NULL , $order_by = NULL , $group = NULL){
    $this->db->select($select);
    $this->db->from($from);
    $this->db->join($join , $join_where , $side);
    if($where != NULL){
        $this->db->where($where);
    }
    if($order_by != NULL){
        $this->db->order_by($order_by[0] , $order_by[1]);
    }
   if($limit != NULL){
        $this->db->limit($limit , $offset);
    }
    if($group != NULL){
        $this->db->group_by($group);
    }
    $query = $this->db->get();
    if($ret != 'result'){
        return $query->row();
    }else{
        return $query->result();
    }
}

function insert_data($table , $data){
    if($this->db->insert($table , $data)){
        return $this->db->insert_id();
    }else{
        return FALSE;
    }
}

function update_data($table , $data , $where){
    $this->db->where($where);
    if($this->db->update($table , $data)){
        return TRUE;
    }else{
        return FALSE;
    }
}

function get_count($table , $where){
			if($where == 'ALL'){
				return $this->db->count_all($table);
			}
			else{
			$this->db->count_all_results($table);
			$this->db->where($where);
			$this->db->from($table);
			return $this->db->count_all_results();	
			}
}
function get_count_between($table , $where = NULL , $between = NULL){
    $this->db->count_all_results($table);
    if($where != NULL){
        $this->db->where($where);
    }
    if($between != NULL){
        $this->db->where($between);
    }
    $this->db->from($table);
    return $this->db->count_all_results();	

}
function search_data($from , $join , $select ,$join_where , $side  , $like , $where = NULL , $order_by = NULL , $group_by = NULL , $join2 = NULL){
    $this->db->select($select);
    $this->db->from($from);
    $this->db->join($join , $join_where , $side);
    if($join2 != NULL){
		$this->db->join($join2[0] , $join2[1]);
	}
	if($where != NULL){
		$this->db->where($array);
	}
	if($order_by != NULL){
		$this->db->order_by($order_by[0] , $order_by[1]);
    }
    if($group_by != NULL){
        $this->db->group_by($group_by);
    }
	$this->db->like($like);
	$query = $this->db->get();
	return $query->result();
}

function insert_batch($table , $data){
	if($this->db->insert_batch($table , $data)){
		return TRUE;
	}else{
		return FALSE;
	}
	
}

function update_batch($table , $data , $key){
    if($this->db->update_batch($table , $data , $key)){
        return TRUE;
    }else{
        return FALSE;
    }
}

function delete_data($table , $where){
    if($this->db->delete($table, $where)){
        return TRUE;
    }else{
        return FALSE;
    }
}
function run_query($query) {
    return $this->db->query($query)->result()‍;
}

// --select--update--insert--delete--  //

}
?>