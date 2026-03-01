<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 12/03/2019 22:07:41
 */ 

namespace Store\Core\Model;

class ComentarioCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $email = "email";
	public $texto = "texto";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNome(){

		return $this->nome;
	}

	public function setNome($nome){

		$this->nome = $nome;
		return $this->nome;
	}

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){

		$this->email = $email;
		return $this->email;
	}

	public function getTexto(){

		return $this->texto;
	}

	public function setTexto($texto){

		$this->texto = $texto;
		return $this->texto;
	}

}