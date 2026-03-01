<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 25/06/2020 19:29:33
 */ 

namespace Store\Core\Model;

class PreListaPostagemCoreMODEL 
{

	public $id = "id";
	public $numero_plp = "numero_plp";
	public $data_geracao = "data_geracao";
	public $hora_geracao = "hora_geracao";
	public $fechada = "fechada";
	public $mensagem_error = "mensagem_error";
	public $error_fechamento = "error_fechamento";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNumeroPlp(){

		return $this->numero_plp;
	}

	public function setNumeroPlp($numero_plp){

		$this->numero_plp = $numero_plp;
		return $this->numero_plp;
	}

	public function getDataGeracao(){

		return $this->data_geracao;
	}

	public function setDataGeracao($data_geracao){

		$this->data_geracao = $data_geracao;
		return $this->data_geracao;
	}

	public function getHoraGeracao(){

		return $this->hora_geracao;
	}

	public function setHoraGeracao($hora_geracao){

		$this->hora_geracao = $hora_geracao;
		return $this->hora_geracao;
	}

	public function getFechada(){

		return $this->fechada;
	}

	public function setFechada($fechada){

		$this->fechada = $fechada;
		return $this->fechada;
	}

	public function getMensagemError(){

		return $this->mensagem_error;
	}

	public function setMensagemError($mensagem_error){

		$this->mensagem_error = $mensagem_error;
		return $this->mensagem_error;
	}

	public function getErrorFechamento(){

		return $this->error_fechamento;
	}

	public function setErrorFechamento($error_fechamento){

		$this->error_fechamento = $error_fechamento;
		return $this->error_fechamento;
	}

}