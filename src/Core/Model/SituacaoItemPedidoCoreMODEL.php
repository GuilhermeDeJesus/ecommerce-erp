<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class SituacaoItemPedidoCoreMODEL 
{

	public $id = "id";
	public $situacao = "situacao";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getSituacao(){

		return $this->situacao;
	}

	public function setSituacao($situacao){

		$this->situacao = $situacao;
		return $this->situacao;
	}

}