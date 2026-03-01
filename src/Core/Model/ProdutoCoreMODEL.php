<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 10/01/2019 14:09:46
 */ 

namespace Store\Core\Model;

class ProdutoCoreMODEL 
{

	public $id = "id";
	public $descricao = "descricao";
	public $sobre = "sobre";
	public $reducao_iva_st = "reducao_iva_st";
	public $lucro = "lucro";
	public $peso_bruto = "peso_bruto";
	public $peso_liquido = "peso_liquido";
	public $unidade = "unidade";
	public $valor_compra = "valor_compra";
	public $valor_venda = "valor_venda";
	public $valor_sem_oferta = "valor_sem_oferta";
	public $ativo = "ativo";
	public $observacao = "observacao";
	public $ncm = "ncm";
	public $link_produto = "link_produto";
	public $cod_url_produto = "cod_url_produto";
	public $descricao_despacho = "descricao_despacho";
	public $frete_gratis = "frete_gratis";
	public $comprimento = "comprimento";
	public $largura = "largura";
	public $altura = "altura";
	public $codigo_de_barras = "codigo_de_barras";
	public $id_categoria = "id_categoria";
	public $id_marca = "id_marca";
	public $id_fornecedor = "id_fornecedor";

	public function __construct(){}

	public function getId(){

		return $this->id;
	}

	public function setId($id){

		$this->id = $id;
		return $this->id;
	}

	public function getDescricao(){

		return $this->descricao;
	}

	public function setDescricao($descricao){

		$this->descricao = $descricao;
		return $this->descricao;
	}

	public function getSobre(){

		return $this->sobre;
	}

	public function setSobre($sobre){

		$this->sobre = $sobre;
		return $this->sobre;
	}

	public function getReducaoIvaSt(){

		return $this->reducao_iva_st;
	}

	public function setReducaoIvaSt($reducao_iva_st){

		$this->reducao_iva_st = $reducao_iva_st;
		return $this->reducao_iva_st;
	}

	public function getLucro(){

		return $this->lucro;
	}

	public function setLucro($lucro){

		$this->lucro = $lucro;
		return $this->lucro;
	}

	public function getPesoBruto(){

		return $this->peso_bruto;
	}

	public function setPesoBruto($peso_bruto){

		$this->peso_bruto = $peso_bruto;
		return $this->peso_bruto;
	}

	public function getPesoLiquido(){

		return $this->peso_liquido;
	}

	public function setPesoLiquido($peso_liquido){

		$this->peso_liquido = $peso_liquido;
		return $this->peso_liquido;
	}

	public function getUnidade(){

		return $this->unidade;
	}

	public function setUnidade($unidade){

		$this->unidade = $unidade;
		return $this->unidade;
	}

	public function getValorCompra(){

		return $this->valor_compra;
	}

	public function setValorCompra($valor_compra){

		$this->valor_compra = $valor_compra;
		return $this->valor_compra;
	}

	public function getValorVenda(){

		return $this->valor_venda;
	}

	public function setValorVenda($valor_venda){

		$this->valor_venda = $valor_venda;
		return $this->valor_venda;
	}

	public function getValorSemOferta(){

		return $this->valor_sem_oferta;
	}

	public function setValorSemOferta($valor_sem_oferta){

		$this->valor_sem_oferta = $valor_sem_oferta;
		return $this->valor_sem_oferta;
	}

	public function getAtivo(){

		return $this->ativo;
	}

	public function setAtivo($ativo){

		$this->ativo = $ativo;
		return $this->ativo;
	}

	public function getObservacao(){

		return $this->observacao;
	}

	public function setObservacao($observacao){

		$this->observacao = $observacao;
		return $this->observacao;
	}

	public function getNcm(){

		return $this->ncm;
	}

	public function setNcm($ncm){

		$this->ncm = $ncm;
		return $this->ncm;
	}

	public function getLinkProduto(){

		return $this->link_produto;
	}

	public function setLinkProduto($link_produto){

		$this->link_produto = $link_produto;
		return $this->link_produto;
	}

	public function getCodUrlProduto(){

		return $this->cod_url_produto;
	}

	public function setCodUrlProduto($cod_url_produto){

		$this->cod_url_produto = $cod_url_produto;
		return $this->cod_url_produto;
	}

	public function getDescricaoDespacho(){

		return $this->descricao_despacho;
	}

	public function setDescricaoDespacho($descricao_despacho){

		$this->descricao_despacho = $descricao_despacho;
		return $this->descricao_despacho;
	}

	public function getFreteGratis(){

		return $this->frete_gratis;
	}

	public function setFreteGratis($frete_gratis){

		$this->frete_gratis = $frete_gratis;
		return $this->frete_gratis;
	}

	public function getComprimento(){

		return $this->comprimento;
	}

	public function setComprimento($comprimento){

		$this->comprimento = $comprimento;
		return $this->comprimento;
	}

	public function getLargura(){

		return $this->largura;
	}

	public function setLargura($largura){

		$this->largura = $largura;
		return $this->largura;
	}

	public function getAltura(){

		return $this->altura;
	}

	public function setAltura($altura){

		$this->altura = $altura;
		return $this->altura;
	}

	public function getCodigoDeBarras(){

		return $this->codigo_de_barras;
	}

	public function setCodigoDeBarras($codigo_de_barras){

		$this->codigo_de_barras = $codigo_de_barras;
		return $this->codigo_de_barras;
	}

	public function getIdCategoria(){

		return $this->id_categoria;
	}

	public function setIdCategoria($id_categoria){

		$this->id_categoria = $id_categoria;
		return $this->id_categoria;
	}

	public function getIdMarca(){

		return $this->id_marca;
	}

	public function setIdMarca($id_marca){

		$this->id_marca = $id_marca;
		return $this->id_marca;
	}

	public function getIdFornecedor(){

		return $this->id_fornecedor;
	}

	public function setIdFornecedor($id_fornecedor){

		$this->id_fornecedor = $id_fornecedor;
		return $this->id_fornecedor;
	}

}