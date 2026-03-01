<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class HistoricoVisualizacaoProdutoCarrinhoCoreMODEL 
{

	public $id = "id";
	public $id_produto = "id_produto";
	public $id_cliente = "id_cliente";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getIdProduto(){

		return $this->id_produto;
	}

	public function setIdProduto($id_produto){

		$this->id_produto = $id_produto;
		return $this->id_produto;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}