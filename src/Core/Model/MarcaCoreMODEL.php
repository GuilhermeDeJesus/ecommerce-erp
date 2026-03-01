<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class MarcaCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $descricao = "descricao";

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

	public function getDescricao(){

		return $this->descricao;
	}

	public function setDescricao($descricao){

		$this->descricao = $descricao;
		return $this->descricao;
	}

}