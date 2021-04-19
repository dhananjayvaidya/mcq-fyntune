<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Api extends \CodeIgniter\Controller
{
    use ResponseTrait;
	
	private $db;
	private $session;
	public function __construct(){
		$this->db = db_connect();
		$this->session = session();
	}
    public function isGuestLogged(){
		
		if ($this->session->get('isGuestLoggedIn') == 1){
			$data = array("message"=>"Guest is Logged In", "code"=>1);
		}else{
			$data = array("message"=>"Guest Not Logged In", "code"=>0);
		}
		
		return $this->respond($data,200);
	}
	public function guestLogin(){
		$fullName 	= $this->request->getVar('full_name');
		$email		= $this->request->getVar('email');
		$phone		= $this->request->getVar('phone');

		if ($fullName !== "" || $email !== "" ){
			$Guest = new \App\Models\Guest();
			$GuestInfo = $Guest->where('guest_email',$email)->findAll();
			if (count($GuestInfo)>=1){
				$this->session->set("isGuestLoggedIn",1);
				$this->session->set('guest_id',$GuestInfo[0]['id']);
				$data = array("message"=>"Successfully registered!","code"=>1);
			}else{
				if ($Guest->insert(array("guest_name"=>$fullName, "guest_email"=> $email,"guest_phone"=>$phone,'ip_address'=>$_SERVER['REMOTE_ADDR'],"user_agent"=>$_SERVER['HTTP_USER_AGENT']))){
					$this->session->set("isGuestLoggedIn",1);
					$this->session->set('guest_id',$Guest->insertID);
					$data = array("message"=>"Successfully registered!","code"=>1);
				}else{
					$data = array("message"=>"Problem occur while registering","code"=>0);
				}
			}
		}else{
			$data = array("message"=>"Please provide full name & email id","code"=>0);
		}
		return $this->respond($data,200);
	}
	public function adminLogin(){
		
		$email		= $this->request->getVar('email');
		$password	= sha1($this->request->getVar('password'));

		if ($email !== "" || $password !== "" ){
			$Admin = new \App\Models\Admin();
			$AdminInfo = $Admin->where(array('email_id'=>$email, "password"=>$password))->findAll();
			if (count($AdminInfo)>=1){
				$this->session->set("isAdminLoggedIn",1);
				$this->session->set('admin_id',$AdminInfo[0]['id']);
				$data = array("message"=>"Successfully Login","code"=>1);
			}else{
				$data = array("message"=>"Problem occur while login","code"=>0);
			}
		}else{
			$data = array("message"=>"Please provide full name & email id","code"=>0);
		}
		return $this->respond($data,200);
	}
	public function getQuestions(){
		$questions = file_get_contents( "https://opentdb.com/api.php?amount=10");
		$questions = json_decode($questions,true);
		print_r($questions);
	}
}

