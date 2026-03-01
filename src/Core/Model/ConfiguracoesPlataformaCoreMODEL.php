<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 24/03/2019 14:52:00
 */ 

namespace Store\Core\Model;

class ConfiguracoesPlataformaCoreMODEL 
{

	public $id = "id";
	public $numero_conta_anuncio_facebook = "numero_conta_anuncio_facebook";
	public $token_pag_seguro = "token_pag_seguro";
	public $email_conta_pag_seguro = "email_conta_pag_seguro";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getNumeroContaAnuncioFacebook(){

		return $this->numero_conta_anuncio_facebook;
	}

	public function setNumeroContaAnuncioFacebook($numero_conta_anuncio_facebook){

		$this->numero_conta_anuncio_facebook = $numero_conta_anuncio_facebook;
		return $this->numero_conta_anuncio_facebook;
	}

	public function getTokenPagSeguro(){

		return $this->token_pag_seguro;
	}

	public function setTokenPagSeguro($token_pag_seguro){

		$this->token_pag_seguro = $token_pag_seguro;
		return $this->token_pag_seguro;
	}

	public function getEmailContaPagSeguro(){

		return $this->email_conta_pag_seguro;
	}

	public function setEmailContaPagSeguro($email_conta_pag_seguro){

		$this->email_conta_pag_seguro = $email_conta_pag_seguro;
		return $this->email_conta_pag_seguro;
	}

}