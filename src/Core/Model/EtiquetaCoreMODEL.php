<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 27/03/2020 12:37:35
 */ 

namespace Store\Core\Model;

class EtiquetaCoreMODEL 
{

	public $id = "id";
	public $plp = "plp";
	public $pedidos = "pedidos";
	public $data_geracao = "data_geracao";
	public $data_validade = "data_validade";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getPlp(){

		return $this->plp;
	}

	public function setPlp($plp){

		$this->plp = $plp;
		return $this->plp;
	}

	public function getPedidos(){

		return $this->pedidos;
	}

	public function setPedidos($pedidos){

		$this->pedidos = $pedidos;
		return $this->pedidos;
	}

	public function getDataGeracao(){

		return $this->data_geracao;
	}

	public function setDataGeracao($data_geracao){

		$this->data_geracao = $data_geracao;
		return $this->data_geracao;
	}

	public function getDataValidade(){

		return $this->data_validade;
	}

	public function setDataValidade($data_validade){

		$this->data_validade = $data_validade;
		return $this->data_validade;
	}

}