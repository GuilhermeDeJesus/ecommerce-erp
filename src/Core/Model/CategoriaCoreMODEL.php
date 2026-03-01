<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class CategoriaCoreMODEL 
{

	public $id = "id";
	public $descricao = "descricao";
	public $icone = "icone";
	public $categoria_pai = "categoria_pai";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getDescricao(){

		return $this->descricao;
	}

	public function setDescricao($descricao){

		$this->descricao = $descricao;
		return $this->descricao;
	}

	public function getIcone(){

		return $this->icone;
	}

	public function setIcone($icone){

		$this->icone = $icone;
		return $this->icone;
	}

	public function getCategoriaPai(){

		return $this->categoria_pai;
	}

	public function setCategoriaPai($categoria_pai){

		$this->categoria_pai = $categoria_pai;
		return $this->categoria_pai;
	}

}