<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 29/10/2019 21:53:45
 */ 

namespace Store\Core\Model;

class NewsletterCoreMODEL 
{

	public $id = "id";
	public $email = "email";
	public $confirmado = "confirmado";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){

		$this->email = $email;
		return $this->email;
	}

	public function getConfirmado(){

		return $this->confirmado;
	}

	public function setConfirmado($confirmado){

		$this->confirmado = $confirmado;
		return $this->confirmado;
	}

}