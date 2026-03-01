<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class TamanhoProdutoCoreMODEL 
{

	public $id = "id";
	public $descricao = "descricao";
	public $valor = "valor";
	public $id_produto = "id_produto";

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

	public function getValor(){

		return $this->valor;
	}

	public function setValor($valor){

		$this->valor = $valor;
		return $this->valor;
	}

	public function getIdProduto(){

		return $this->id_produto;
	}

	public function setIdProduto($id_produto){

		$this->id_produto = $id_produto;
		return $this->id_produto;
	}

}