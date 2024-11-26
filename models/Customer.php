<?php
include 'config/config.php';

class Customer
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

   

    public function generateCode($length=10){

    	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	    $charactersLength = strlen($characters);
	    $randomString = '';

	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[random_int(0, $charactersLength - 1)];
	    }

	    return sha1($randomString.date("Ymd-His"));
    }
	
}
