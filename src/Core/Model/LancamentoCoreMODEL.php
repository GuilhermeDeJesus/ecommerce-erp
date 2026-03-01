<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 15/01/2020 15:14:13
 */ 

namespace Store\Core\Model;

class LancamentoCoreMODEL 
{

	public $id = "id";
	public $valor = "valor";
	public $data = "data";
	public $id_tipo_lancamento = "id_tipo_lancamento";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getValor(){

		return $this->valor;
	}

	public function setValor($valor){

		$this->valor = $valor;
		return $this->valor;
	}

	public function getData(){

		return $this->data;
	}

	public function setData($data){

		$this->data = $data;
		return $this->data;
	}

	public function getIdTipoLancamento(){

		return $this->id_tipo_lancamento;
	}

	public function setIdTipoLancamento($id_tipo_lancamento){

		$this->id_tipo_lancamento = $id_tipo_lancamento;
		return $this->id_tipo_lancamento;
	}

}