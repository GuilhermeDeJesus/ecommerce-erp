<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class FretePedidoCoreMODEL 
{

	public $id = "id";
	public $cep_origem = "cep_origem";
	public $cep_destino = "cep_destino";
	public $peso = "peso";
	public $comprimento = "comprimento";
	public $altura = "altura";
	public $largura = "largura";
	public $id_pessoa = "id_pessoa";
	public $id_cliente = "id_cliente";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getCepOrigem(){

		return $this->cep_origem;
	}

	public function setCepOrigem($cep_origem){

		$this->cep_origem = $cep_origem;
		return $this->cep_origem;
	}

	public function getCepDestino(){

		return $this->cep_destino;
	}

	public function setCepDestino($cep_destino){

		$this->cep_destino = $cep_destino;
		return $this->cep_destino;
	}

	public function getPeso(){

		return $this->peso;
	}

	public function setPeso($peso){

		$this->peso = $peso;
		return $this->peso;
	}

	public function getComprimento(){

		return $this->comprimento;
	}

	public function setComprimento($comprimento){

		$this->comprimento = $comprimento;
		return $this->comprimento;
	}

	public function getAltura(){

		return $this->altura;
	}

	public function setAltura($altura){

		$this->altura = $altura;
		return $this->altura;
	}

	public function getLargura(){

		return $this->largura;
	}

	public function setLargura($largura){

		$this->largura = $largura;
		return $this->largura;
	}

	public function getIdPessoa(){

		return $this->id_pessoa;
	}

	public function setIdPessoa($id_pessoa){

		$this->id_pessoa = $id_pessoa;
		return $this->id_pessoa;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}