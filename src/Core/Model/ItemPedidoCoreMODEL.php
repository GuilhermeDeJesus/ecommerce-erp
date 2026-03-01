<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class ItemPedidoCoreMODEL 
{

	public $id = "id";
	public $preco = "preco";
	public $quantidade = "quantidade";
	public $lucro = "lucro";
	public $id_pedido = "id_pedido";
	public $id_produto = "id_produto";
	public $id_cor_produto = "id_cor_produto";
	public $id_tamanho_produto = "id_tamanho_produto";
	public $id_situacao_item_pedido = "id_situacao_item_pedido";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getPreco(){

		return $this->preco;
	}

	public function setPreco($preco){

		$this->preco = $preco;
		return $this->preco;
	}

	public function getQuantidade(){

		return $this->quantidade;
	}

	public function setQuantidade($quantidade){

		$this->quantidade = $quantidade;
		return $this->quantidade;
	}

	public function getLucro(){

		return $this->lucro;
	}

	public function setLucro($lucro){

		$this->lucro = $lucro;
		return $this->lucro;
	}

	public function getIdPedido(){

		return $this->id_pedido;
	}

	public function setIdPedido($id_pedido){

		$this->id_pedido = $id_pedido;
		return $this->id_pedido;
	}

	public function getIdProduto(){

		return $this->id_produto;
	}

	public function setIdProduto($id_produto){

		$this->id_produto = $id_produto;
		return $this->id_produto;
	}

	public function getIdCorProduto(){

		return $this->id_cor_produto;
	}

	public function setIdCorProduto($id_cor_produto){

		$this->id_cor_produto = $id_cor_produto;
		return $this->id_cor_produto;
	}

	public function getIdTamanhoProduto(){

		return $this->id_tamanho_produto;
	}

	public function setIdTamanhoProduto($id_tamanho_produto){

		$this->id_tamanho_produto = $id_tamanho_produto;
		return $this->id_tamanho_produto;
	}

	public function getIdSituacaoItemPedido(){

		return $this->id_situacao_item_pedido;
	}

	public function setIdSituacaoItemPedido($id_situacao_item_pedido){

		$this->id_situacao_item_pedido = $id_situacao_item_pedido;
		return $this->id_situacao_item_pedido;
	}

}