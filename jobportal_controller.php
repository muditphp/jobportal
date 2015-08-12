<?php
/***********************************************************/
	/* Display Reassign Job list. @return Response    **********/
	/***********************************************************/
	public function reassignJobStatus() {
		//** Get SERVICE URL **//
		$responseJson = array();
		$filter_svc = Config::get("constants.SVC_REASSIGN_JOB");
		//** Get input from post method **//
		$jobId = (int) Input::get('job_id');
		$reasonOfCancellation = Input::get('reason');
		$comments = Input::get('comments');
		//** GET posted by from constants file (temporary till login functionality) **//
		$postedBy = Session::get('admin_user_details')['emailId']; //Config::get("constants.POSTED_BY");
		if( !$postedBy ){
			$responseJson['message'] = 'Something went wrong. Please login again and try';
			echo json_encode($responseJson);
			exit;
		}
		$allowanceType = Input::get('allowance_type');
		$allowanceCost = Input::get('allowance_cost');
		$newServiceProvider = Input::get('service_provider');
		//**  Prepare parameter arry for server request **//
		$filter = array();
		$filter['jobId'] = $jobId;
		$filter['reasonOfCancellation'] = $reasonOfCancellation;
		$filter['comments'] = $comments;
		$filter['userType'] = 'admin';
		$filter['postedBy'] = $postedBy;
		if ($allowanceType != '' && $allowanceCost != '') {
			$filter['allowanceType'] = $allowanceType;
			$filter['allowanceCost'] = (float) $allowanceCost;
		}
		$filter['newServiceProvider'] = (int) $newServiceProvider;
		$this->exception_log->addInfo($jobId);
		if ($jobId == 0) {
			$this->exception_log->addError($jobId);
			$responseJson['message'] = 'ERROR';
			echo json_encode($responseJson);
			exit;
		}
		//** Send request to server using curl **//
		$client = new Client();
		$request = $client->post($filter_svc, array(), $filter);
		$response = $request->send();
		$responseJson = json_decode(($response->getBody()), true);
		$responseJson['message'] = '';
		echo json_encode($responseJson);
		// $results = $responseJson['jobMaster'];
		//        echo '<pre>';
		//        print_r($responseJson);
		//        exit;
	}

	/***********************************************************/
	/* Display Assign Job list. @return Response      **********/
	/***********************************************************/
	public function assignJobStatus() {
		$responseJson = array();
		$assignType = Input::get('assignType');
		//** Get SERVICE URL **//
		$filter_svc = Config::get("constants.SVC_ASSIGN_JOB");
				
		//** Get input from post method **//
		$jobId = (int)Input::get('job_id');


		//** GET posted by from constants file (temporary till login functionality) **//
		$postedBy = Session::get('admin_user_details')['emailId']; //Config::get("constants.POSTED_BY");
		if( !$postedBy ){
			$responseJson['message'] = 'Something went wrong. Please login again and try';
			echo json_encode($responseJson);
			exit;
		}
		$userType = 'admin';
		$serviceProviderId = (int) Input::get('service_provider');
		$paymentType = Input::get('allowance_type');
		$paymentValue = (float) Input::get('allowance_cost');
		//**  Prepare parameter arry for server request **//
		$filter = array();
		$filter['jobId'] = $jobId;
		$filter['serviceProviderId'] = $serviceProviderId;
		$filter['userType'] = 'admin';
        if( $paymentType != '' && $paymentValue != '' ){
			$filter['paymentType'] = $paymentType;
			$filter['paymentValue'] = (float) $paymentValue;
		}
		if( $assignType == 'drop'){
			$filter_svc = Config::get("constants.DROP_PROVIDER_BOOKED");
			$filter['notificationFlag'] = Input::get('notificationFlag');;
			$filter['comments'] = Input::get('comments');;
		}
		$filter['postedBy'] = $postedBy;
		$this->exception_log->addInfo($jobId);
		if( $jobId == 0 ){
			$this->exception_log->addError($jobId);
			$responseJson['message'] = 'ERROR';
			echo json_encode($responseJson);
			exit;
		}
		//** Send request to server using curl **//
		$client = new Client();
		$request = $client->post($filter_svc, array(), $filter);
		$response = $request->send();
		$responseJson = json_decode(($response->getBody()), true);
		$responseJson['message'] = '';
		// $results = $responseJson['jobMaster'];
		echo json_encode($responseJson);
//        echo '<pre>';
		//        print_r($responseJson);
		//        exit;
	}
	
?>	