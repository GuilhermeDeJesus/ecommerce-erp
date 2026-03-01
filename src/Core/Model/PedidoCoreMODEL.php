<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class PedidoCoreMODEL 
{

	public $id = "id";
	public $numero_pedido = "numero_pedido";
	public $data = "data";
	public $valor = "valor";
	public $frete = "frete";
	public $id_cliente = "id_cliente";
	public $id_endereco = "id_endereco";
	public $id_situacao_pedido = "id_situacao_pedido";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNumeroPedido(){

		return $this->numero_pedido;
	}

	public function setNumeroPedido($numero_pedido){

		$this->numero_pedido = $numero_pedido;
		return $this->numero_pedido;
	}

	public function getData(){

		return $this->data;
	}

	public function setData($data){

		$this->data = $data;
		return $this->data;
	}

	public function getValor(){

		return $this->valor;
	}

	public function setValor($valor){

		$this->valor = $valor;
		return $this->valor;
	}

	public function getFrete(){

		return $this->frete;
	}

	public function setFrete($frete){

		$this->frete = $frete;
		return $this->frete;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

	public function getIdEndereco(){

		return $this->id_endereco;
	}

	public function setIdEndereco($id_endereco){

		$this->id_endereco = $id_endereco;
		return $this->id_endereco;
	}

	public function getIdSituacaoPedido(){

		return $this->id_situacao_pedido;
	}

	public function setIdSituacaoPedido($id_situacao_pedido){

		$this->id_situacao_pedido = $id_situacao_pedido;
		return $this->id_situacao_pedido;
	}

}