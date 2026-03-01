<?php 

/** 
 * Created by Eclipse. 
 * User: guilherme 
 * Date : 19/08/2020 10:04:41
 */ 

namespace Store\Core\Dao;
use Krypitonite\Dao\Dao; 

require_once 'krypitonite/src/Dao/Dao.php'; 

class ConfiguracoesPlataformaCoreDAO extends Dao 
{

	/** 
	 * @var Constant $tableName 
	 * Required - Is the table name refers to this class 
	 */
	const TABLE = "configuracoes_plataforma";

	public function __construct(){}

	private function __clone(){}

	/** 
	 * @param Array $form 
	 * @param Array $conditions 
	 * @return Integer count changes 
	 */
	public function update(Array $form, Array $conditions){
		return self::queryUpdate(self::TABLE, $form, $conditions);
	}

	/** 
	 * @param Array $form 
	
 */
	public function insert(Array $form){
		return self::queryInsert(self::TABLE, $form);
	}

	/** 
	 * @param Array $form 
	 */
	public function insertORUpdate(Array $form){
		if (isset($form["id"]) && $form["id"] != NULL && $form["id"] != 0) {
			self::queryUpdate(self::TABLE, $form, ["id", "=", (int) $form["id"]], FALSE);
			return (int) $form["id"];
		} else {
			unset($form["id"]);
			return self::queryInsert(self::TABLE, $form);
		}
	}

	/** 
	* @param Array $fields 
	* @param Array $conditions 
	* @param Array $orderBy 
	* @param Integer $limit 
	* @return Array $fetchAll 
	*/
	public function select(Array $fields, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL, $groupBy = NULL){
		return self::querySelect(self::TABLE, $fields, $conditions, $orderBy, $limit, $amount, $groupBy);
	}

	public function getField($field = NULL, $id = NULL){
		$value = $this->select([$field], ["id", "=", $id]);
		if(sizeof($value) != 0){
			return $value[0][$field];
		}
	}

	/** 
	* @param Array $tableJoin 
	* @param Array $on 
	* @param Array $conditions 
	* @param Array $orderBy 
	* @param Integer $limit 
	* @return Array $fetchAll 
	*/
	public function selectJoin($tableJoin, Array $on, Array $conditions = NULL, Array $orderBy = NULL, $limit = NULL, $amount = NULL){
		return self::querySelectInnerJOIN(self::TABLE, $tableJoin, $on, $conditions, $orderBy, $limit, $amount);
	}

	/** 
	 * @param Array $conditions
	 */
	public function delete(Array $conditions){
		return self::queryDelete(self::TABLE, $conditions);
	}

	/** 
	* @param String $field 
	* @param Array $conditions 
	*/
	public static function countOcurrence($field = "*", Array $conditions = NULL){
		return self::queryCountOcurrence(self::TABLE, $field = "*", $conditions);
	}

	/** 
	 * @return Array All Fields
	 */
	public function getAllFields(){
		return [
				"id",
				"nome_loja",
				"nome_logo",
				"nome_logo_mobile",
				"numero_conta_anuncio_facebook",
				"token_pag_seguro",
				"email_conta_pag_seguro",
				"cliente_id_mp",
				"client_secret_mp",
				"gateway",
				"email_envio",
				"senha_email_envio",
				"tag_keywords",
				"tag_description",
				"tag_title",
				"url_loja",
				"email_contato_loja",
				"telefone_contato_loja",
				"cor_loja",
				"parcelar_sem_juros",
				"quantidade_parcelas_sem_juros",
				"valor_minimo_para_frete_gratis",
				"taf_d_1_mp",
				"taf_d_14_mp",
				"taf_d_30_mp",
				"cupom",
				"percentual_desconto_cupom",
			];
	}

}