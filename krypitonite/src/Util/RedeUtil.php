<?php
namespace Krypitonite\Util;

use Rede\Transaction;
use Rede\Store;
use Rede\eRede;
use Rede\Exception\RedeException;
use Krypitonite\Log\Log;
require_once 'vendor/developersrede/erede-php/src/Rede/Transaction.php';
require_once 'vendor/developersrede/erede-php/src/Rede/Store.php';
require_once 'vendor/developersrede/erede-php/src/Rede/eRede.php';

// O seu número de identificação na Rede é:
// 84441631
// Senha Aesso: 1995179Ati
class RedeUtil
{

    // sandbox
    // private static $_url = "https://api.userede.com.br/desenvolvedores/v1/transactions";

    // private static $_pv = "10005366";

    // private static $_token = "211129deddc6491eb55fadadfb194fc8";

    // prodution
    private static $_url = "https://api.userede.com.br/erede/v1/transactions";

    private static $_pv = "84441631";

    private static $_token = "8c6baa230f934b59bd9d0a70e252d299";

    public static function getStore()
    {
        // return new Store(self::$_pv, self::$_token, \Rede\Environment::sandbox());
        return new Store(self::$_pv, self::$_token, \Rede\Environment::production());
    }

    public static function transacion($value, $numberCard, $cvv, $_expiry_month, $_expiry_year, $_parcela, $name)
    {
        try {

            $transaction = (new Transaction($value, 'pedido' . time()))->creditCard($numberCard, $cvv, $_expiry_month, $_expiry_year, $name);

            // Configuração de parcelamento
            $_part = (int) $_parcela;
            if ($_part > 1) {
                $transaction->setInstallments($_part);
            }

            // Autoriza a transação
            $transaction = (new eRede(self::getStore()))->create($transaction);

            // Transação autorizada com sucesso
            if ($transaction->getReturnCode() == '00') {
                return [
                    'situacao' => 'APROVADO',
                    'code' => $transaction->getReturnCode(),
                    'situacao_pagamento' => self::getStatusTransacao($transaction->getReturnCode()),
                    'date' => date('Y-m-d'),
                    'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                    'forma_pagamento' => "<b>Cartão de Crédito</b>",
                    'cardNumber' => $transaction->getCardNumber(),
                    'expirationMonth' => $transaction->getExpirationMonth(),
                    'expirationYear' => $transaction->getExpirationYear(),
                    'securityCode' => $transaction->getSecurityCode(),
                    'success' => TRUE,
                    'total' => $value
                ];
            }
        } catch (RedeException $e) {

            Log::error(implode(',', [
                'Message' => $e->getMessage(),
                'Code' => $e->getCode(),
                'Line' => $e->getLine()
            ]));

            return [
                'situacao' => 'REJEITADO',
                'code' => $e->getCode(),
                'situacao_pagamento' => self::getStatusTransacao($e->getCode()),
                'date' => date('Y-m-d'),
                'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                'forma_pagamento' => "<b>Cartão de Crédito</b>",
                'success' => FALSE,
                'total' => $value
            ];
        }
    }

    public static function transacionAntifruadeLoteria($value, $numberCard, $cvv, $_expiry_month, $_expiry_year, $_parcela, $name, $cliente, $cpf, $email, $telefone, $endereco, $numero, $cep, $bairro, $cidade, $uf)
    {
        try {
            // Ambiente de produção
            $environment = \Rede\Environment::production();

            // Ambiente sandbox
            // $environment = \Rede\Environment::sandbox();

            $environment->setIp($_SERVER["REMOTE_ADDR"]);
            // $environment->setIp('177.107.52.163');
            $environment->setSessionId('Shopvitas-' . time());

            // Configuração da loja
            $store = new \Rede\Store(self::$_pv, self::$_token, $environment);
            // $store = new \Rede\Store('10005366', '211129deddc6491eb55fadadfb194fc8', $environment);

            // Transação que será autorizada
            $transaction = (new \Rede\Transaction($value, 'pedido' . time()))->creditCard($numberCard, $cvv, $_expiry_month, $_expiry_year, $name);

            // Configuração de parcelamento
            $_part = (int) $_parcela;
            if ($_part > 1) {
                $transaction->setInstallments($_part);
            }

            // Dados do antifraude
            $antifraud = $transaction->antifraud($environment);
            $antifraud->consumer($cliente, $email, $cpf)->setPhone(new \Rede\Phone(substr($telefone, 0, 2), substr($telefone, 2, 9)));

            $antifraud->address()
                ->setAddress($endereco)
                ->setNumber($numero)
                ->setZipCode($cep)
                ->setNeighbourhood($bairro)
                ->setCity($cidade)
                ->setState($uf);

            $numberOrder = time();

            $amount = str_replace(',', '', str_replace('.', '', $value));
            $amount = (int) $amount;
            $antifraud->addItem((new \Rede\Item($numberOrder, 1, \Rede\Item::PHYSICAL))->setAmount($amount)
                ->setDescription('Pedido Shopvitas - ' . $numberOrder));

            // Autoriza a transação
            $transaction = (new \Rede\eRede($store))->create($transaction);
            if ($transaction->getReturnCode() == '00') {
                $antifraud = $transaction->getAntifraud();
                $recomendacaoRede = $antifraud->getRecommendation();
                switch ($recomendacaoRede) {
                    case 'Aprovar':
                        return [
                            'situacao' => 'APROVADO',
                            'code' => $transaction->getReturnCode(),
                            'situacao_pagamento' => self::getStatusTransacao($transaction->getReturnCode()),
                            'date' => date('Y-m-d'),
                            'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                            'forma_pagamento' => "<b>Cartão de Crédito</b>",
                            'cardNumber' => $transaction->getCardNumber(),
                            'expirationMonth' => $transaction->getExpirationMonth(),
                            'expirationYear' => $transaction->getExpirationYear(),
                            'securityCode' => $transaction->getSecurityCode(),
                            'success' => TRUE,
                            'total' => $value,
                            'antifraude' => $antifraud->isSuccess() ? 'Sucesso' : 'Falha',
                            'score' => $antifraud->getScore(),
                            'nivel_risco' => $antifraud->getRiskLevel(),
                            'recomendacao' => $recomendacaoRede,
                            'nsu' => $transaction->getNsu()
                        ];
                        break;
                    case 'Negar':

                        $tid = $transaction->getTid();
                        $transaction = (new \Rede\eRede($store))->cancel($transaction)->setTid($tid);
                        Log::error(implode('|', [
                            'Recomendacao' => $recomendacaoRede,
                            'Code' => $transaction->getReturnCode(),
                            'Nivel de Risco' => $antifraud->getRiskLevel(),
                            'Tid' => $tid
                        ]));

                        return [
                            'situacao' => 'REJEITADO',
                            'code' => $transaction->getReturnCode(),
                            'situacao_pagamento' => "<b style='color: red;'>Transação não autorizada<b>",
                            'date' => date('Y-m-d'),
                            'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                            'forma_pagamento' => "<b>Cartão de Crédito</b>",
                            'cardNumber' => $transaction->getCardNumber(),
                            'expirationMonth' => $transaction->getExpirationMonth(),
                            'expirationYear' => $transaction->getExpirationYear(),
                            'securityCode' => $transaction->getSecurityCode(),
                            'success' => FALSE,
                            'total' => $value,
                            'antifraude' => $antifraud->isSuccess() ? 'Sucesso' : 'Falha',
                            'score' => $antifraud->getScore(),
                            'nivel_risco' => $antifraud->getRiskLevel(),
                            'recomendacao' => $recomendacaoRede,
                            'nsu' => $transaction->getNsu()
                        ];
                        break;
                }
            }
        } catch (RedeException $e) {
            Log::error(implode(',', [
                'Message' => $e->getMessage(),
                'Code' => $e->getCode(),
                'Line' => $e->getLine()
            ]));

            return [
                'situacao' => 'REJEITADO',
                'code' => $e->getCode(),
                'situacao_pagamento' => self::getStatusTransacao($e->getCode()),
                'date' => date('Y-m-d'),
                'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                'forma_pagamento' => "<b>Cartão de Crédito</b>",
                'success' => FALSE,
                'total' => $value,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }

    public static function transacionAntifruadeDefault($value, $itens, $numberCard, $cvv, $_expiry_month, $_expiry_year, $_parcela, $name, $cliente, $cpf, $email, $telefone, $endereco, $numero, $cep, $bairro, $cidade, $uf)
    {
        try {
            // Ambiente de produção
            $environment = \Rede\Environment::production();

            // Ambiente sandbox
            // $environment = \Rede\Environment::sandbox();

            $environment->setIp($_SERVER["REMOTE_ADDR"]);
            // $environment->setIp('177.107.52.163');
            $environment->setSessionId('Shopvitas-' . time());

            // Configuração da loja
            $store = new \Rede\Store(self::$_pv, self::$_token, $environment);
            // $store = new \Rede\Store('10005366', '211129deddc6491eb55fadadfb194fc8', $environment);

            // Transação que será autorizada
            $transaction = (new \Rede\Transaction((floatval($value)), 'pedido' . time()))->creditCard($numberCard, $cvv, $_expiry_month, $_expiry_year, $name);

            // Configuração de parcelamento
            $_part = (int) $_parcela;
            if ($_part > 1) {
                $transaction->setInstallments($_part);
            }

            // Dados do antifraude
            $antifraud = $transaction->antifraud($environment);
            $antifraud->consumer($cliente, $email, $cpf)->setPhone(new \Rede\Phone(substr($telefone, 0, 2), substr($telefone, 2, 9)));

            $antifraud->address()
                ->setAddresseeName($cliente)
                ->setAddress($endereco)
                ->setNumber($numero)
                ->setZipCode($cep)
                ->setNeighbourhood($bairro)
                ->setCity($cidade)
                ->setState($uf);

            foreach ($itens as $item) {
                $numberOrder = time();
                $amount = str_replace(',', '', str_replace('.', '', $item['valor']));
                $amount = (int) $amount;
                $antifraud->addItem((new \Rede\Item($numberOrder, $item['quantidade'], \Rede\Item::PHYSICAL))->setAmount($amount)
                    ->setDescription($item['descricao']));
            }

            // Autoriza a transação
            $transaction = (new \Rede\eRede($store))->create($transaction);
            if ($transaction->getReturnCode() == '00') {
                $antifraud = $transaction->getAntifraud();
                $recomendacaoRede = $antifraud->getRecommendation();
                switch ($recomendacaoRede) {
                    case 'Aprovar':
                        return [
                            'situacao' => 'APROVADO',
                            'code' => $transaction->getReturnCode(),
                            'situacao_pagamento' => self::getStatusTransacao($transaction->getReturnCode()),
                            'date' => date('Y-m-d'),
                            'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                            'forma_pagamento' => "<b>Cartão de Crédito</b>",
                            'cardNumber' => $transaction->getCardNumber(),
                            'expirationMonth' => $transaction->getExpirationMonth(),
                            'expirationYear' => $transaction->getExpirationYear(),
                            'securityCode' => $transaction->getSecurityCode(),
                            'success' => TRUE,
                            'total' => $value,
                            'antifraude' => $antifraud->isSuccess() ? 'Sucesso' : 'Falha',
                            'score' => $antifraud->getScore(),
                            'nivel_risco' => $antifraud->getRiskLevel(),
                            'recomendacao' => $recomendacaoRede,
                            'nsu' => $transaction->getNsu()
                        ];
                        break;
                    case 'Negar':
                        // Cancelar a transação
                        $tid = $transaction->getTid();
                        $transaction = (new \Rede\eRede($store))->cancel($transaction)->setTid($tid);
                        Log::error(implode('|', [
                            'Recomendacao' => $recomendacaoRede,
                            'Code' => $transaction->getReturnCode(),
                            'Nivel de Risco' => $antifraud->getRiskLevel(),
                            'Tid' => $tid
                        ]));

                        return [
                            'situacao' => 'REJEITADO',
                            'code' => $transaction->getReturnCode(),
                            'situacao_pagamento' => "<b style='color: red;'>Transação não autorizada<b>",
                            'date' => date('Y-m-d'),
                            'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                            'forma_pagamento' => "<b>Cartão de Crédito</b>",
                            'cardNumber' => $transaction->getCardNumber(),
                            'expirationMonth' => $transaction->getExpirationMonth(),
                            'expirationYear' => $transaction->getExpirationYear(),
                            'securityCode' => $transaction->getSecurityCode(),
                            'success' => FALSE,
                            'total' => $value,
                            'antifraude' => $antifraud->isSuccess() ? 'Sucesso' : 'Falha',
                            'score' => $antifraud->getScore(),
                            'nivel_risco' => $antifraud->getRiskLevel(),
                            'recomendacao' => $recomendacaoRede,
                            'nsu' => $transaction->getNsu()
                        ];
                        break;
                }
            }
        } catch (RedeException $e) {
            Log::error(implode(',', [
                'Message' => $e->getMessage(),
                'Code' => $e->getCode(),
                'Line' => $e->getLine()
            ]));

            return [
                'situacao' => 'REJEITADO',
                'code' => $e->getCode(),
                'situacao_pagamento' => self::getStatusTransacao($e->getCode()),
                'date' => date('Y-m-d'),
                'parcela' => $_part . "x de R$ " . ValidateUtil::setFormatMoney($value / $_part),
                'forma_pagamento' => "<b>Cartão de Crédito</b>",
                'success' => FALSE,
                'total' => $value,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ];
        }
    }

    public static function getStatusTransacao($code)
    {
        switch ($code) {
            case 00:
                return "<b style='color: green;'>Compra realizada com sucesso</b>";
                break;
            case 111:
                return "<b style='color: red;'>Nao autorizado. Saldo insuficiente</b>";
                break;
            case 119:
                return "<b style='color: red;'>Nao autorizado. Código de segurança invalido</b>";
                break;
            case 64:
                return "<b style='color: red;'>Transação nao processada. Tente novamente</b>";
                break;
            case 55:
                return "<b style='color: red;'>Titular do Cartao Invalido</b>";
                break;
            case 908:
                return "<b style='color: red;'>CPF de cadastro Invalido</b>";
                break;
            case 37:
            case 53:
            case 56:
            case 57:
            case 58:
            case 69:
            case 72:
            case 74:
            case 79:
            case 80:
            case 83:
            case 84:
            case 86:
            case 101:
            case 102:
            case 103:
            case 104:
            case 105:
            case 106:
            case 107:
            case 108:
            case 109:
            case 110:
            case 111:
            case 112:
            case 113:
            case 114:
            case 115:
            case 116:
            case 117:
            case 118:
            case 121:
            case 122:
            case 123:
            case 124:
            case 204:
                return "<b style='color: red;'>Pagamento nao autorizado. Entre em contato com o emissor</b>";
                break;
        }
    }
}