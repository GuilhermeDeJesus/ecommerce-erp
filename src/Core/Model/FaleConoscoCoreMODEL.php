<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class FaleConoscoCoreMODEL 
{

	public $id = "id";
	public $nome = "nome";
	public $email = "email";
	public $telefone = "telefone";
	public $numero_pedido = "numero_pedido";
	public $mensagem = "mensagem";
	public $id_cliente = "id_cliente";

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

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){

		$this->email = $email;
		return $this->email;
	}

	public function getTelefone(){

		return $this->telefone;
	}

	public function setTelefone($telefone){

		$this->telefone = $telefone;
		return $this->telefone;
	}

	public function getNumeroPedido(){

		return $this->numero_pedido;
	}

	public function setNumeroPedido($numero_pedido){

		$this->numero_pedido = $numero_pedido;
		return $this->numero_pedido;
	}

	public function getMensagem(){

		return $this->mensagem;
	}

	public function setMensagem($mensagem){

		$this->mensagem = $mensagem;
		return $this->mensagem;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}