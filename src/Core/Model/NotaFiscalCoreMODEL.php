<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 18/06/2020 15:18:15
 */ 

namespace Store\Core\Model;

class NotaFiscalCoreMODEL 
{

	public $id = "id";
	public $status = "status";
	public $numero = "numero";
	public $serie = "serie";
	public $chave_nfe = "chave_nfe";
	public $caminho_pdf = "caminho_pdf";
	public $caminho_xml = "caminho_xml";
	public $mensagem_sefax = "mensagem_sefax";
	public $ref = "ref";
	public $numero_carta_correcao = "numero_carta_correcao";
	public $status_sefaz = "status_sefaz";
	public $data_emissao = "data_emissao";
	public $id_pedido = "id_pedido";

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

	public function getNumero(){

		return $this->numero;
	}

	public function setNumero($numero){

		$this->numero = $numero;
		return $this->numero;
	}

	public function getSerie(){

		return $this->serie;
	}

	public function setSerie($serie){

		$this->serie = $serie;
		return $this->serie;
	}

	public function getChaveNfe(){

		return $this->chave_nfe;
	}

	public function setChaveNfe($chave_nfe){

		$this->chave_nfe = $chave_nfe;
		return $this->chave_nfe;
	}

	public function getCaminhoPdf(){

		return $this->caminho_pdf;
	}

	public function setCaminhoPdf($caminho_pdf){

		$this->caminho_pdf = $caminho_pdf;
		return $this->caminho_pdf;
	}

	public function getCaminhoXml(){

		return $this->caminho_xml;
	}

	public function setCaminhoXml($caminho_xml){

		$this->caminho_xml = $caminho_xml;
		return $this->caminho_xml;
	}

	public function getMensagemSefax(){

		return $this->mensagem_sefax;
	}

	public function setMensagemSefax($mensagem_sefax){

		$this->mensagem_sefax = $mensagem_sefax;
		return $this->mensagem_sefax;
	}

	public function getRef(){

		return $this->ref;
	}

	public function setRef($ref){

		$this->ref = $ref;
		return $this->ref;
	}

	public function getNumeroCartaCorrecao(){

		return $this->numero_carta_correcao;
	}

	public function setNumeroCartaCorrecao($numero_carta_correcao){

		$this->numero_carta_correcao = $numero_carta_correcao;
		return $this->numero_carta_correcao;
	}

	public function getStatusSefaz(){

		return $this->status_sefaz;
	}

	public function setStatusSefaz($status_sefaz){

		$this->status_sefaz = $status_sefaz;
		return $this->status_sefaz;
	}

	public function getDataEmissao(){

		return $this->data_emissao;
	}

	public function setDataEmissao($data_emissao){

		$this->data_emissao = $data_emissao;
		return $this->data_emissao;
	}

	public function getIdPedido(){

		return $this->id_pedido;
	}

	public function setIdPedido($id_pedido){

		$this->id_pedido = $id_pedido;
		return $this->id_pedido;
	}

}