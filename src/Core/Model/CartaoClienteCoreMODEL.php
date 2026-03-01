<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 27/03/2020 12:37:35
 */ 

namespace Store\Core\Model;

class CartaoClienteCoreMODEL 
{

	public $id = "id";
	public $nome_titular = "nome_titular";
	public $numero = "numero";
	public $mes_validade = "mes_validade";
	public $ano_validade = "ano_validade";
	public $cvv = "cvv";
	public $bandeira = "bandeira";
	public $id_cliente = "id_cliente";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNomeTitular(){

		return $this->nome_titular;
	}

	public function setNomeTitular($nome_titular){

		$this->nome_titular = $nome_titular;
		return $this->nome_titular;
	}

	public function getNumero(){

		return $this->numero;
	}

	public function setNumero($numero){

		$this->numero = $numero;
		return $this->numero;
	}

	public function getMesValidade(){

		return $this->mes_validade;
	}

	public function setMesValidade($mes_validade){

		$this->mes_validade = $mes_validade;
		return $this->mes_validade;
	}

	public function getAnoValidade(){

		return $this->ano_validade;
	}

	public function setAnoValidade($ano_validade){

		$this->ano_validade = $ano_validade;
		return $this->ano_validade;
	}

	public function getCvv(){

		return $this->cvv;
	}

	public function setCvv($cvv){

		$this->cvv = $cvv;
		return $this->cvv;
	}

	public function getBandeira(){

		return $this->bandeira;
	}

	public function setBandeira($bandeira){

		$this->bandeira = $bandeira;
		return $this->bandeira;
	}

	public function getIdCliente(){

		return $this->id_cliente;
	}

	public function setIdCliente($id_cliente){

		$this->id_cliente = $id_cliente;
		return $this->id_cliente;
	}

}