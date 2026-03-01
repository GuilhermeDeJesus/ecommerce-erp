<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 20/03/2019 11:26:27
 */ 

namespace Store\Core\Model;

class PedidoStatusFornecedorCoreMODEL 
{

	public $id = "id";
	public $status = "status";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getStatus(){

		return $this->status;
	}

	public function setStatus($status){

		$this->status = $status;
		return $this->status;
	}

}