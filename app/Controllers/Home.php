<?php

namespace App\Controllers;

class Home extends BaseController
{	
	private $db;
	private $session;
	public function __construct(){
		$this->db = db_connect();
		$this->session = session();
	}
	public function index()
	{
		if ($this->session->get('isGuestLoggedIn') == 1){
			$questions = file_get_contents( "https://opentdb.com/api.php?amount=10");
			$questions = json_decode($questions,true);
			
			$data['questions'] = $questions['results'];

			//create a test 
			$test_reference = md5(rand(1111,9999));
			$GuestTest = new \App\Models\GuestTest();
			$GuestTest->insert(array(
						"gid" => $this->session->get('guest_id'),
						"test_reference" => $test_reference,
						"status"	=> 0
					));
			$this->session->set('guest_test_id',$GuestTest->insertID);
			$data['test_reference'] = $test_reference;
			
			return view("exam",$data);
		}else{
			return view('login');
		}
	}
	
	public function submit_exam(){
		$data = $this->request->getVar();
		$x = 10;
		$total_correct = 0;
		$total_skipped = 0;
		$GuestResult = new \App\Models\GuestResult();
		
		for($y=1;$y<=$x;$y++){
			$question = $data['question_'.$y];
			
			if (isset($data['q_'.$y]) == true){
				if ($data['q_'.$y] == $data['correct_answer_'.$y]){
					$is_correct = 1;
					$total_correct = $total_correct+1;
				}else{
					$is_correct = 0;
				}
				$is_skipped = 0;
				$answer = $data['q_'.$y];
			}else{
				$answer = "";
				$is_correct = 0;
				$is_skipped = 1;
				$total_skipped = $total_skipped+1;
			}
			$GuestResult->insert(array(
				"guest_id"		=> $this->session->get('guest_id'),
				"test_id"		=> $this->session->get('guest_test_id'),
				"question"		=> $question,
				"answer"		=> $answer,
				"is_correct" 	=> $is_correct,
				"is_skipped"	=> $is_skipped
			));
		}
		$result = array("total_skipped"=>$total_skipped,"total_correct"=>$total_correct,"total_questions"=>$x);
		$view_data['result'] = $result;
		$view_data['exam_data'] = $data;
		return view("exam_submit",$view_data);
	}
	public function logout(){
		$this->session->destroy();
		return redirect()->route('/');
	}
}
