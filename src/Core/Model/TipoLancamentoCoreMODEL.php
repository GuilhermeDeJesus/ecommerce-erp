<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 15/01/2020 15:14:13
 */ 

namespace Store\Core\Model;

class TipoLancamentoCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";

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

}