<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class LinkedinAPI extends CI_Controller {

	function __construct(){
		
	}

	public function index()
	{
		$this->load->library('linkedin');
		// You'll probably use a database
		session_name('linkedin');
		//session_start();

		// OAuth 2 Control Flow
		if (isset($_GET['error'])) {
			// LinkedIn returned an error
			print $_GET['error'] . ': ' . $_GET['error_description'];
			exit;
		} elseif (isset($_GET['code'])) {
			// User authorized your application
			if ($_SESSION['state'] == $_GET['state']) {
				// Get token so you can make API calls
				$this->linkedin->getAccessToken();
			} else {
				// CSRF attack? Or did you mix up your states?
				exit;
			}
		} else { 
			if ((empty($_SESSION['expires_at'])) || (time() > $_SESSION['expires_at'])) {
				// Token has expired, clear the state
				$_SESSION = array();
			}
			if (empty($_SESSION['access_token'])) {
				// Start authorization process
				$this->linkedin->getAuthorizationCode();
			}
		}
	

		// Congratulations! You have a valid token. Now fetch your profile 
		$user_from_linkedin = $this->linkedin->fetch('GET', '/v1/people/~:(id,firstName,lastName,headline,location,industry,positions,specialties,picture-url,email-address)');
		//var_dump($user_from_linkedin);
		$data['linkedin_id']=$user_from_linkedin->id;
		$user_in_db=""; // here get your user from database, create your own method.
		
		if($user_in_db){
			// user exist, just login
			echo 'login user';
			//start login session
		}
		else{
			echo 'register and login';
			// register user $user_from_linkedin
			//and start session for login
		}
		exit();
	}
	

	
}
