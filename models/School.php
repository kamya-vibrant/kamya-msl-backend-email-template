<?php
include 'config/config.php';

class School
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
	
}
