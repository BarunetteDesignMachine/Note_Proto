<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MainModel extends CI_Model {
	public function __construct() {
		$this->load->database();
	}

	public function get_notes($user_id,$page_size = 4,$page_index = 0) {
		$this->db->from('notes');
		$this->db->where('user_id', $user_id);
		$total_notes = $this->db->count_all_results();
		//
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->order_by('id', 'desc');
		$this->db->where('user_id', $user_id);
		$this->db->limit($page_size, $page_size*$page_index);
		$query = $this->db->get();

		$result = $query->result_array();

		return (array(
			'total_notes'=>$total_notes,
			'notes'=>$result
		));
	}
	public function count_notes($user_id) {
		$this->db->from('notes');
		$this->db->where('user_id', $user_id);
		return $this->db->count_all_results();
	}
	public function get_note_details($note_id) {
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->where('id', $note_id);
		$query = $this->db->get();
		$result = $query->row_array();
		return $result;
	}
	public function create_note($user_id, $title, $content) {
		$data = array(
			'user_id' => $user_id,
			'title' => $title,
			'content' => $content,
			'created_at' => date('Y-m-d H:i:s')
		);
		$this->db->insert('notes', $data);
		return $this->db->insert_id();
	}

	public function get_note_by_id($note_id, $user_id) {
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->where('id', $note_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		return $query->row_array();
	}
	public function get_single_note($note_id, $user_id){
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->where('id', $note_id);
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		return $query->row_array();
	}

		public function update_note($note_id, $user_id, $title, $content) {
			$data = array(
				'title' => $title,
				'content' => $content,
				'updated_at' => date('Y-m-d H:i:s')
			);
			$this->db->where('id', $note_id);
			$this->db->where('user_id', $user_id);
			$this->db->update('notes', $data);
			return true;
		}

	public function delete_note_by_id($id, $user_id) {
		$this->db->select('*');
		$this->db->from('notes');
		$this->db->where('id', $id);
		$this->db->where('user_id', $user_id);
		$this->db->delete('notes');
		return true;
	}
	public function register_user($username, $password) {
		// Check if the username already exists
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		if ($query->num_rows() > 0) {
			return false;
		}

		// Insert the new user into the database
		$data = array(
			'username' => $username,
			'password' => password_hash($password, PASSWORD_DEFAULT)
		);
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function login_user($username, $password) {
		// Check if the username exists
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		if ($query->num_rows() == 0) {
			return false;
		}

		// Verify the password
		$user = $query->row_array();
		if (password_verify($password, $user['password'])) {
			return $user;
		} else {
			return false;
		}
	}
}

