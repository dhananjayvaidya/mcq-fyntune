<?php

namespace App\Controllers;

class Admin extends BaseController
{	
	private $db;
	private $session;
	public function __construct(){
		$this->db = db_connect();
		$this->session = session();
	}
	public function index()
	{
		if ($this->session->get('isAdminLoggedIn') == 1){
			$Guest = new \App\Models\Guest();
			$data['total_guests'] = count($Guest->findAll());
			return view("admin/dashboard",$data);
		}else{
			return view('admin/login');
		}
	}
	public function guests($action='list',$id=false)
	{
		if ($this->session->get('isAdminLoggedIn') == 1){
			switch($action){
				case "list":
					$Guest = new \App\Models\Guest();
					$data['guests'] = $Guest->findAll();
					return view("admin/guests",$data);		
				break;
				case "view":
					$Guest = new \App\Models\Guest();
					$data['guest'] = $Guest->where('id',$id)->findAll();
					$GuestTest = new \App\Models\GuestTest();
					$GuestTestData = $GuestTest->where('gid',$id)->orderBy('id','desc')->findAll();
					$GuestResult = new \App\Models\GuestResult();
					$GuestResultData = $GuestResult->where('guest_id',$id)->orderBy('id','desc')->findAll();
					$GuestLatestResultData = $GuestResult->where('test_id',$GuestTestData[0]['id'])->findAll();
					$data['guest_results'] = $this->db->query("SELECT * FROM `guest_results`,`guest_tests` WHERE `guest_results`.`test_id` = `guest_tests`.`id` AND `guest_results`.`guest_id` = '".$id."' ORDER BY `guest_results`.`id` DESC")->getResult('array');
					$total_correct = 0;
					$total_questions = 0; 
					
					foreach($GuestLatestResultData as $GRD){
						if ($GRD['is_correct']){
							$total_correct = $total_correct+1;
						}
						$total_questions = $total_questions+1;
					}
					$data['total_questions'] = $total_questions;
					$data['total_correct'] = $total_correct;
					return view("admin/guest_detail",$data);		
				break;
			}
			
		}else{
			return view('admin/login');
		}
	}
	
	public function logout(){
		$this->session->destroy();
		return redirect()->route('admin');
	}
}
