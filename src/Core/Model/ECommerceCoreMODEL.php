<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class ECommerceCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $logo = "logo";

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

	public function getLogo(){

		return $this->logo;
	}

	public function setLogo($logo){

		$this->logo = $logo;
		return $this->logo;
	}

}