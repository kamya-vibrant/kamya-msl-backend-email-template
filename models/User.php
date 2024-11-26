<?php
include 'config/config.php';

$logger = require 'service/logger.php';

class User
{

	/*
	*
	*	Actions: insert, update, delete, retrieve
	*
	*/

    public function insert($query){

    	try {
	   		global $dbh;

			$stmt = $dbh->prepare($query);
			$stmt->execute();

			return array('code'=>200, 'message'=>'Successful', 'id'=> $dbh->lastInsertId());

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }	

    /*
	*
	*	Actions: insert, update, delete, retrieve
	*
	*/

    public function update($query){

    	try {
	   		global $dbh;

			$stmt = $dbh->prepare($query);
			$stmt->execute();

			return array('code'=>200, 'message'=>'Successful');

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }	

    /*
	*
	*	Actions: insert, update, delete, retrieve
	*
	*/

    public function delete($query){

    	try {
	   		global $dbh;

			$stmt = $dbh->prepare($query);
			$stmt->execute();

			return array('code'=>200, 'message'=>'Successful');

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }	

    /*
	*
	*	
	*
	*/

    public function login($query){
    	try {
    		global $QueryTransformer;
	   		global $dbh;
			global $logger;

			$stmt = $dbh->prepare($query);
			$stmt->execute();

			$records = $QueryTransformer->transformRecord($stmt);
			$records = json_decode($records, true);

			$logger->debug("records: " . print_r($records, true));
			//we still need to add the access control list for this user and resend back to login

			return (empty($records)) ? 
				   array('code'=>201, 'message'=>'Invalid user!', 'data'=>$records) :
				   array('code'=>200, 'message'=>'Logged-in Successful!', 'data'=>$records, 'logged_datetime'=>date("Y-m-d H:i:s")) ;

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }


    /*
	*
	*	
	*
	*/

    public function setupAccessControl($query){

    	try {
    		global $QueryTransformer;
	   		global $dbh;

			$stmt = $dbh->prepare($query);
			$stmt->execute();

			$records = $QueryTransformer->transformRecord($stmt);
			$records = json_decode($records, true);

			return (empty($records)) ? 
				   array('code'=>201, 'message'=>'Invalid user!', 'data'=>$records) :
				   array('code'=>200, 'message'=>'Logged-in Successful!', 'data'=>$records, 'logged_datetime'=>date("Y-m-d H:i:s")) ;

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }	

    /*
	*
	*	
	*
	*/

    public function generateCode($length=10){

    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[random_int(0, $charactersLength - 1)];
	    }

	    return sha1($randomString.date("Ymd-His"));
    }


	public function retrieve($query){
    	try {
	   		global $dbh;

			$stmt = $dbh->prepare($query);
			$data = $stmt->execute();

			$data = $stmt->fetchALL(PDO::FETCH_ASSOC);

			return array('code'=>200, 'message'=>'Successful', 'data'=>$data);

		} catch (PDOException $e) {
			return array('code'=>402, 'message'=>$e->getMessage());
		} catch (Exception $e) {
		    return array('code'=>402, 'message'=>$e->getMessage());
		}
    }	
	
}
