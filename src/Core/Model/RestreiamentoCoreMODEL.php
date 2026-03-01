<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class RestreiamentoCoreMODEL 
{

	public $id = "id";
	public $codigo = "codigo";
	public $postado = "postado";
	public $id_pedido = "id_pedido";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getCodigo(){

		return $this->codigo;
	}

	public function setCodigo($codigo){

		$this->codigo = $codigo;
		return $this->codigo;
	}

	public function getPostado(){

		return $this->postado;
	}

	public function setPostado($postado){

		$this->postado = $postado;
		return $this->postado;
	}

	public function getIdPedido(){

		return $this->id_pedido;
	}

	public function setIdPedido($id_pedido){

		$this->id_pedido = $id_pedido;
		return $this->id_pedido;
	}

}