<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('MainModel');
		$this->load->library('session');
	}

	public function index()
	{
		$data = array();
		// Load the view
//		$this->load->model('MainModel');
//		$data['notes'] = $this->MainModel->get_notes($this->session->userdata('user_id'));

		$this->load->view('NoteMain', $data);
	}

//USER Section
	public function register()
	{
		// Check if the user is already logged in
		if ($this->session->userdata('user_id')) {
			redirect('note');
		}

		// Get the input data from the user
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate the input data
		if (empty($username) || empty($password)) {
			echo json_encode([
				'code'=>-1,
				'message'=>"Please provide a username and password",
			]);
			return;
		}

		// Register the user
		$user_id = $this->MainModel->register_user($username, $password);
		if ($user_id) {
			$this->session->set_userdata('user_id', $user_id);
			echo json_encode([
				'code'=>1,
				'message'=>'Register proccess was a bust'
			]);
		} else {
			echo json_encode([
				'code'=>-2,
				'message'=>'Username already exists'
			]);
		}
	}

	public function login()
	{
		// Check if the user is already logged in
		if ($this->session->userdata('user_id')) {
			redirect('index');
		}

		// Get the input data from the user
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		// Validate the input data
		if (empty($username) || empty($password)) {
			echo json_encode([
				'code'=>-1,
				'message'=>"Please provide a username and password",
			]);
			return;
		}

		// Login the user
		$user = $this->MainModel->login_user($username, $password);
		if ($user) {
			$this->session->set_userdata('user_id', $user['id']);
			echo json_encode([
				'code'=>2,
				'message'=>"Login is Done",
			]);
//			redirect('index');
		} else {
			echo json_encode([
				'code'=>-3,
				'message'=>"Check your Credentials once more",
			]);
		}
	}

	public function logout()
	{
		// Destroy the session and redirect to the login page
		$this->session->sess_destroy();
		redirect('Main/index');
	}
//Note Section CRUD
	public function create_note() {
		// Check if the user is logged in
		if (!$this->session->userdata('user_id')) {
			echo 'please login first';
		}

		// Get the input data from the user
		$user_id = $this->session->userdata('user_id');
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		// Create the note in the database
		$note_id = $this->MainModel->create_note($user_id, $title, $content);

		// Return the ID of the newly created note
		echo json_encode(array(
			'code'=>1,
			'message'=>"Done!"
		));
	}

	public function get_notes() {

		// Get the note ID from the user
		$user_id = $this->session->userdata('user_id');
		$page_index = $this->input->post('page_index');
		$page_size = $this->input->post('page_size');
		$total_notes = $this->MainModel->count_notes($user_id);
		// Get the note from the database
		$notes = $this->MainModel->get_notes($user_id,$page_size,$page_index,$total_notes);

		// Return the note as a JSON object
		echo json_encode($notes);

	}
	public function get_note_details() {
		$note_id = $this->input->post('note_id');
		$note = $this->MainModel->get_note_details($note_id);
		echo json_encode($note);
	}
	public function get_note($note_id) {

		// Get the note ID from the user
		$user_id = $this->session->userdata('user_id');

		// Get the note from the database
		$note = $this->MainModel->get_single_note($note_id, $user_id);

		// Return the note as a JSON object
		echo json_encode($note);
	}

	public function update_note() {

		// Get the input data from the user
		$note_id = $this->input->post('note_id');
		$title = $this->input->post('title');
		$content = $this->input->post('content');
		$user_id = $this->session->userdata('user_id');

		// Update the note in the database
		$this->MainModel->update_note($note_id, $user_id, $title, $content);

		// Return a success message
		echo json_encode(array(
			'code'=>10,
			'message'=>"Done!"
		));
	}

	public function delete_note() {

		// Get the note ID from the user
		$id = $this->input->post('note_id');
		$user_id = $this->session->userdata('user_id');

		// Delete the note from the database
		$this->load->model('MainModel');
		$this->MainModel->delete_note_by_id($id, $user_id);

		// Return a success message
		echo json_encode(array(
			'code'=>9,
			'message'=>"Delete Done!"
		));
	}
}
