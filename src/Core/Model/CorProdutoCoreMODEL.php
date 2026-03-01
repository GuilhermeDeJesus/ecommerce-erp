<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class CorProdutoCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $url_img = "url_img";
	public $ativo = "ativo";
	public $id_produto = "id_produto";

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

	public function getUrlImg(){

		return $this->url_img;
	}

	public function setUrlImg($url_img){

		$this->url_img = $url_img;
		return $this->url_img;
	}

	public function getAtivo(){

		return $this->ativo;
	}

	public function setAtivo($ativo){

		$this->ativo = $ativo;
		return $this->ativo;
	}

	public function getIdProduto(){

		return $this->id_produto;
	}

	public function setIdProduto($id_produto){

		$this->id_produto = $id_produto;
		return $this->id_produto;
	}

}