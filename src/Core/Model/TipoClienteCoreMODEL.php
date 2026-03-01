<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 12/08/2020 14:44:32
 */ 

namespace Store\Core\Model;

class TipoClienteCoreMODEL 
{

	public $id = "id";
	public $tipo = "tipo";
	public $sigla = "sigla";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getTipo(){

		return $this->tipo;
	}

	public function setTipo($tipo){

		$this->tipo = $tipo;
		return $this->tipo;
	}

	public function getSigla(){

		return $this->sigla;
	}

	public function setSigla($sigla){

		$this->sigla = $sigla;
		return $this->sigla;
	}

}