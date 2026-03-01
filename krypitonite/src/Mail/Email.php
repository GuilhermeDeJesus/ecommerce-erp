<?php
namespace Krypitonite\Mail;

use Configuration\Configuration;
use Krypitonite\Util\ValidateUtil;
use Krypitonite\Log\Log;
use PHPMailer\PHPMailer\PHPMailer;
require_once 'vendor/phpmailer/phpmailer/class.phpmailer.php';
require_once 'krypitonite/src/Util/ValidateUtil.php';

class Email
{
    private $_isDebug = 0;

    private $_host = "nspro18.hostgator.com.br";

    private $_username = EMAIL_UOL;

    private $_password = SENHA_UOL;

    private $_port = 587;

    private $_SMTPAuth = true;

    private $_SMTPSecure = "tsl";

    private $_mail = NULL;

    public function __construct()
    {
        $this->_mail = new PHPMailer();
        $this->_mail->isSMTP();
        $this->_mail->SMTPDebug = $this->_isDebug;
        $this->_mail->Host = $this->_host;
        $this->_mail->SMTPAuth = $this->_SMTPAuth;
        $this->_mail->Username = $this->_username;
        $this->_mail->Password = $this->_password;
        $this->_mail->SMTPSecure = $this->_SMTPSecure;
        $this->_mail->Port = $this->_port;
        $this->_mail->setFrom(EMAIL_UOL, "Shopvitas");
    }

    /*
     * Class send mail default
     */
    public function send($to = NULL, $subject = NULL, $message = NULL, $cid = '', $imgs = NULL)
    {
        $this->_mail->addAddress($to); // Add a recipient | name optional
        $this->_mail->Subject = $subject;
        $src = Configuration::PATH_APPICATION . '\\public\\img\\';
        $src = str_replace('\\', '/', $src);

        $logo = $this->getImg($cid);
        if ($logo != NULL) {
            $this->_mail->AddEmbeddedImage($src . "$logo", (int) $cid, 'logo.png');
        }

        // set imagens
        if ($imgs != NULL) {
            foreach ($imgs as $prod) {
                $id = $prod['id'];
                $this->_mail->AddEmbeddedImage(Configuration::PATH_APPICATION . "/data/products/" . $id . "/principal.jpg", $id, "principal.jpg");
            }
        }

        $body = "<body style='padding: 50px;'>";
        $body .= "<img alt='' style='width: 25%; display: block; margin-left: auto; margin-right: auto;' src='cid:1001'>";
        $body .= $message;
        $body .= "<p style='font-size: 13px; font-family: Arial; text-align: center;'>Atendimento ao consumidor " . NOME_LOJA . "</p>";
        $body .= "<p style='font-size: 13px; font-family: Arial; text-align: center;'>Telefone " . TELEFONE_CONTATO . "</p>";
        $body .= "<p style='font-size: 13px; font-family: Arial; text-align: center;'>Este é um e-mail automático disparado pelo sistema. Favor não respondê-lo, pois esta conta não é monitorada</p>";
        $body .= "</body>";

        $this->_mail->Body = $body;
        $this->_mail->CharSet = "UTF-8";
        $this->_mail->isHTML(true);
        $this->_mail->AltBody = NOME_LOJA;
        $this->_mail->Debugoutput = 'text';

        if (! $this->_mail->send()) {
            Log::error('Falha no Envio do E-mail: ' . $this->_mail->ErrorInfo);
        }

        $this->_mail->clearAddresses();
    }

    public function getImg($cid = 0)
    {
        $img = [
            1001 => NOME_LOGO
        ];

        if (isset($img[$cid]))
            return $img[$cid];
        else
            return NULL;
    }

    public function promocoes($nomeCliente, $produtos = Array())
    {
        $_produtos = '';
        $cliente = explode(' ', $nomeCliente);

        foreach ($produtos as $prod) {
            $_produtos .= '<table width="600" class="m_8928509291239586807principal" align="center" cellpadding="0" cellspacing="0">
				<tbody><tr>
					<td align="center" style="background-color:#ffffff">
						<table width="600" class="m_8928509291239586807principal" align="center" cellpadding="0" cellspacing="0">
							<tbody><tr>
								<td align="center" valign="middle" style="font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#555555;font-size:16px;padding:20px 0">
									<a href="https://mkt.shoptime.com/pub/cc?_ri_=X0Gzc2X%3DAQpglLjHJlYQGtTTqPzcNWPzdczfOiBzaL4kwzbjuf8PozaXH8Fty2zbCUzbDPt3oBM8roEMzgczd1dKmTcImuYnVXtpKX%3DWSSWTWRY&amp;_ei_=Eq2tf9zs59idfPO1Sc_9Bbl0UZ0XHMhcnz3jNs41N_1-Lx9yAUQIBknpW3KO2fKtTDCJxijLB7WSWonGCWWnOzCtzb92soKm4ZC_7c87M1I0Uya6E-ASFuIvcRbyeHp_tYZHrGCXV54P6NpsDhPn4zw9-CcF0zstT39RUbJb3fGnCBNU29P4DIgourQTEw_WQhe4D9CI3AYUGh9GQfh7OvmHeJj3a-JxGXXLlxi0Um4Gx1Q9dzuISrWRQJAv0YuHQg.&amp;_di_=vgi7t8r92gieksrui433t4c6jinu6u5m2ogoif6jmk8j46t4mipg" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://mkt.shoptime.com/pub/cc?_ri_%3DX0Gzc2X%253DAQpglLjHJlYQGtTTqPzcNWPzdczfOiBzaL4kwzbjuf8PozaXH8Fty2zbCUzbDPt3oBM8roEMzgczd1dKmTcImuYnVXtpKX%253DWSSWTWRY%26_ei_%3DEq2tf9zs59idfPO1Sc_9Bbl0UZ0XHMhcnz3jNs41N_1-Lx9yAUQIBknpW3KO2fKtTDCJxijLB7WSWonGCWWnOzCtzb92soKm4ZC_7c87M1I0Uya6E-ASFuIvcRbyeHp_tYZHrGCXV54P6NpsDhPn4zw9-CcF0zstT39RUbJb3fGnCBNU29P4DIgourQTEw_WQhe4D9CI3AYUGh9GQfh7OvmHeJj3a-JxGXXLlxi0Um4Gx1Q9dzuISrWRQJAv0YuHQg.%26_di_%3Dvgi7t8r92gieksrui433t4c6jinu6u5m2ogoif6jmk8j46t4mipg&amp;source=gmail&amp;ust=1597504434043000&amp;usg=AFQjCNGmdFV-qjmOrekG8PBq3CuPIdadjA">
										<img border="0" src="cid:' . $prod['id'] . '" width="180" height="180" alt="" class="CToWUd">
									</a>
								</td>
							</tr>
							<tr>
								<td align="center">
										    
									<table width="350" class="m_8928509291239586807principal" align="left" cellpadding="0" cellspacing="0" border="0">
										<tbody><tr>
											<td align="left" valign="middle" class="m_8928509291239586807font-14 m_8928509291239586807padding-0-10 m_8928509291239586807text-center" style="font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#888888;font-size:20px;line-height:26px;padding:0 0 0 30px">
												' . $prod['produto'] . '
											</td>
										</tr>
									</tbody></table>
												    
									<table width="213" class="m_8928509291239586807principal" align="left" cellpadding="0" cellspacing="0" border="0">
										<tbody><tr>
											<td style="padding:0 0 0 30px" class="m_8928509291239586807reset-padding m_8928509291239586807padding-top-10">
												<table cellpadding="0" cellspacing="0" border="0">
													<tbody><tr>
														<td align="center" style="font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#ff6900;font-size:16px;line-height:20px">
															A partir de: <span style="font-weight:bold">R$ </span><span style="font-weight:bold;font-size:30px">' . $prod['valor'] . '</span><span style="font-weight:bold">,' . $prod['centavos'] . '0</span>
														</td>
													</tr>
													<tr>
														<td align="center" style="font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#8c18d7;font-size:17px;line-height:20px;font-weight:bold;padding-top:5px">
															em até <span style="font-size:24px">12x</span> no cartão
														</td>
													</tr>
													<tr>
														<td align="left" class="m_8928509291239586807height-auto m_8928509291239586807text-center m_8928509291239586807principal m_8928509291239586807padding-top-10" style="padding-top:20px">
															<table align="center" cellpadding="0" class="m_8928509291239586807btn-laranja m_8928509291239586807text-center" width="180" cellspacing="0" border="0">
																<tbody><tr>
																	<td align="center" style="background-color:#ff6900" class="m_8928509291239586807padding-0-5">
																		<table cellpadding="0" cellspacing="0" border="0">
																			<tbody><tr>
																				<td align="center">
																					<img border="0" src="https://ci3.googleusercontent.com/proxy/4nrO6yKEdjz84gj8098gntYqmeGMMAhjwFCgxy_TVinpEwsbKoGms7rkEU6sqm5M0BNxR-EpD4X9pOCwqfJvLQBeTLQ1P5DJxZxRwgAGx3es4WxwbHBEEI3sOTIZQq_-WDhEvZydIK4sIxOdmQUqbQXK8J3mcykMVE0VTljI_L29yMY=s0-d-e1-ft#https://static.cdn.responsys.net/i5/responsysimages/shoptime/contentlibrary/template_emd/blocos/img/carrinho.jpg" width="24" height="20" alt="" class="CToWUd">
																				</td>
																				<td width="9"></td>
																				<td align="center" height="44" valign="middle" style="font-family:Arial,Helvetica Neue,Helvetica,sans-serif;color:#ffffff;font-size:18px;font-weight:bold;font-weight:bold;background-color:#ff6900">
																					<a href="' . $prod['url'] . '" style="color:#ffffff;text-decoration:none;display:block" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://mkt.shoptime.com/pub/cc?_ri_%3DX0Gzc2X%253DAQpglLjHJlYQGtTTqPzcNWPzdczfOiBzaL4kwzbjuf8PozaXH8Fty2zbCUzbDPt3oBM8roEMzgczd1dKmTcImuYnVXtpKX%253DWSSWTWRY%26_ei_%3DEq2tf9zs59idfPO1Sc_9Bbl0UZ0XHMhcnz3jNs41N_1-Lx9yAUQIBknpW3KO2fKtTDCJxijLB7WSWonGCWWnOzCtzb92soKm4ZC_7c87M1I0Uya6E-ASFuIvcRbyeHp_tYZHrGCXV54P6NpsDhPn4zw9-CcF0zstT39RUbJb3fGnCBNU29P4DIgourQTEw_WQhe4D9CI3AYUGh9GQfh7OvmHeJj3a-JxGXXLlxi0Um4Gx1Q9dzuISrWRQJAv0YuHQg.%26_di_%3Dvgi7t8r92gieksrui433t4c6jinu6u5m2ogoif6jmk8j46t4mipg&amp;source=gmail&amp;ust=1597504434044000&amp;usg=AFQjCNGvosNl53845mTgXi9jYq5O3jm2IA">
																						COMPRAR
																					</a>
																				</td>
																			</tr>
																		</tbody></table>
																	</td>
																</tr>
															</tbody></table>
														</td>
													</tr>
												</tbody></table>
											</td>
										</tr>
										<tr>
											<td height="30" class="m_8928509291239586807hidden"></td>
										</tr>
									</tbody></table>
								</td>
							</tr>
						</tbody></table>
					</td>
				</tr>
			</tbody></table>';
        }

        $body = '<div
                    	style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
                    	<table cellspacing="0" cellpadding="10" border="0">
                    		<tr>
                    			<td width="120"><img alt="" style="width: 100%;"
                    				src="cid:1001"></td>
                    			<td width="600"><a href="#"><p
                    						style="font-size: 15px; font-family: Arial;">&copy;
                    						' . LINK_LOJA . '</p></a>
                    				<p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
                    					' . TELEFONE_CONTATO . '</p></td>
                    		</tr>
                    		<tr>
                    			<td width="600" colspan="2">
                    				<div
                    					style="border-bottom: 0px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
                    					<p style="font-size: 15px; font-family: Arial; color: #666;">
                    						Olá <b>' . $cliente[0] . '</b>,
                    					</p>
                    					<br>
                    					<p style="font-size: 15px; font-family: Arial; color: #666;">E mais uma seleção incríveis de itens pra você e sua casa!</p>
                    				</div>
                    				<div style="border: 1px solid #e5e5e5; padding: 5px;">
                    					' . $_produtos . '
                    				</div>
                    			</td>
                    		</tr>
                    	</table>
                    </div>';

        return $body;
    }

    public function notificarCarrinhoAbandonado($nomeCliente, $produtos = Array())
    {
        $tr_produtos = '';
        $cliente = explode(' ', $nomeCliente);

        foreach ($produtos as $prod) {
            $tr_produtos .= '<tr style="background: #fff; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
						          <td width="700">
                                    <table>
                                        <tr>
                                            <td width="300"><img src="cid:' . $prod['id'] . '" style="width: 65%; padding: 10px;" /></td>
                                            <td width="1000"><span style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['produto'] . '</span></td>
                                            <td width="500"><a target=”new” href="' . $prod['url'] . '"><button style="position: relative; border: 0px solid green; margin: 0; top: 50%; left: 38%; width: 150px; height: 35px; border-radius: 5px; font-weight: bold;">CONCLUIR PEDIDO</button></td>
                                        </tr>
                                    </table>
                                  </td>
    					     </tr>';
        }

        $body .= '<h2>Olá ' . $cliente[0] . '</h2>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: #666;">Seu carrinho não vai esperar por muito tempo!</p>
				  <p style="font-size: 15px; font-family: Arial; color: #666;">Os produtos que você selecionou na Shopvitas estão te aguardando, mas não por muito tempo. Não espere o estoque acabar e garanta já o seu.</p>
				  <p style="font-size: 18px; font-family: Arial; color: #666;">LISTA DE PRODUTO(S)</p>';

        $body .= '<table>' . $tr_produtos . '</table>';

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 50%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">&copy;
        // ' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
        // ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2">
        // <div
        // style="border-bottom: 0px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 15px; font-family: Arial; color: #666;">
        // Olá <b>' . $cliente[0] . '</b>,
        // </p>
        // <br>
        // <p style="font-size: 15px; font-family: Arial; color: #666;">Seu carrinho não vai esperar por muito tempo!</p>
        // <p style="font-size: 15px; font-family: Arial; color: #666;">Os produtos que você selecionou na Shopvitas estão te aguardando, mas não por muito tempo. Não espere o estoque acabar e garanta já o seu.</p>
        // <p style="font-size: 18px; font-family: Arial; color: #666;">LISTA DE PRODUTO(S)</p>
        // </div>
        // <div style="border: 1px solid #e5e5e5; padding: 5px;">
        // <table>
        // <tr
        // style="background: #eee; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        // <td width="700"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Produto</span></td>
        // </tr>
        // ' . $tr_produtos . '
        // </table>
        // </div>
        // </td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function confirmacaoCodigoRastreio($nomeCliente, $codigo, $produtos = Array(), $endereco = Array())
    {
        $tr_produtos = '';
        foreach ($produtos as $prod) {
            $tr_produtos .= '<tr style="background: #fff; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        						<td width="700"><span
        							style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['produto'] . '</span></td>
        						<td width="200"><span
        							style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['quantidade'] . ' por
        								R$ ' . ValidateUtil::setFormatMoney($prod['preco']) . '</span></td>
        					</tr>';
        }

        $span_endereco = '<span style="font-family: Arial; color: #666;"><b>Endereço de
    							Entrega</b></span> <br> <br> <span
    						style="font-family: Arial; color: #666; font-size: 13px;">' . $endereco[0]['endereco'] . ', ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['bairro'] . '<br>' . $endereco[0]['cidade'] . ' / ' . $endereco[0]['uf'] . ' - CEP:
    						' . $endereco[0]['cep'] . '
    					</span>';

        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: #666;">Seu produto(s) está em transporte, em breve chegará em seu endereço.</p>';
        $body .= '<p style="font-size: 20px; font-family: Arial; color: #666;">Código de Rastreamento: ' . $codigo . '</p>';
        $body .= '<table>' . $tr_produtos . '</table><br>';
        $body .= $span_endereco;
        $body .= '<p style="font-size: 15px; font-family: Arial; color: red;">Caso o código apareça como "Objetos não encontrados" no site dos correios, não se preocupe que o sistema dos correios atualizará em breve, precisa-se de um tempo para que os correios processe sua entrega.</p>';

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">&copy;
        // ' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
        // ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 20px; font-family: Arial; color: #666;">Código de Rastreiamento: ' . $codigo . '</p>
        // </div>
        // <br>
        // <div
        // style="border-bottom: 0px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 15px; font-family: Arial; color: #666;">
        // Olá <b>' . $nomeCliente . '</b>,
        // </p>
        // <br>
        // <p style="font-size: 15px; font-family: Arial; color: #666;">Seu produto(s) está em transporte, em breve chegará em seu endereço.</p>
        // <br> <br>
        // <p style="font-size: 18px; font-family: Arial; color: #666;">Estágios
        // da sua compra</p>
        // <br>
        // <p style="font-size: 15px; font-family: Arial; color: red;">Obs.: Caso o código apareça como "Objetos não encontrados" no site dos correios, não se preocupe que o sistema dos correios atualizará em breve, precisa-se de um tempo para que os correios processe sua entrega.</p>
        // <p style="font-size: 15px; font-family: Arial; color: red; display: none;">O prazo para conseguir rastrear do código nos correios é de até 7 dias úteis. Lembrando que o prazo de entrega é de até 30 dias úteis</p>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #eee; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #666; text-align: center; padding-left: 25%;">&#9745;
        // Pedido Realizado</span>
        // </div>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #eee; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #666; text-align: center; padding-left: 13%;">Aprovação
        // de Pagamento</span>
        // </div>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #008109; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #FFF; text-align: center; padding-left: 25%;">Produto
        // em transporte</span>
        // </div>
        // <div
        // style="background: #eee; height: 30px; margin-top: 70px; width: 99%; padding-top: 10px;">
        // <span
        // style="font-family: Arial; font-size: 12px; color: #666; text-align: center; padding-left: 9%;"><b
        // style="color: red;">PRAZO E ENTREGA:</b> consulta essas
        // informações em seu cadastro na loja, no menu "Minhas Compras".</span>
        // </div>
        // </div>
        // <div style="border: 1px solid #e5e5e5; padding: 5px;">
        // <table>
        // <tr
        // style="background: #eee; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        // <td width="700"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Produto:</span></td>
        // <td width="200"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Qtd/Preço:</span></td>
        // </tr>
        // ' . $tr_produtos . '
        // </table>
        // </div>
        // <div
        // style="border: 1px solid #e5e5e5; padding: 10px; margin-top: 20px;">
        // ' . $span_endereco . '
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function confirmacaoPedido($nomeCliente, $numeroPedido, $produtos = Array(), $endereco = Array())
    {
        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: #666;">Recebemos seu pedido: ' . $numeroPedido . '</p>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: #666;">O pagamento de seu pedido número ' . $numeroPedido . ' foi confirmado pelo nosso sistema.</p>';

        $tr_produtos = '';
        foreach ($produtos as $prod) {
            $tr_produtos .= '<tr style="background: #fff; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        						<td width="700"><span
        							style="font-size: 15px; color: #666;">' . $prod['produto'] . '</span></td>
        						<td width="200"><span
        							style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['quantidade'] . ' por
        								R$ ' . ValidateUtil::setFormatMoney($prod['preco']) . '</span></td>
        					</tr>';
        }

        $body .= '<table>' . $tr_produtos . '</table>';

        $span_endereco = '<span style="font-family: Arial; color: #666;"><b>Endereço de
                    							Entrega</b></span> <br> <br> <span
                    						style="font-family: Arial; color: #666; font-size: 13px;">' . $endereco[0]['endereco'] . ', ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['bairro'] . '<br>' . $endereco[0]['cidade'] . ' / ' . $endereco[0]['uf'] . ' - CEP:
                    						' . $endereco[0]['cep'] . '
                    					</span>';

        $body .= '<div style="border: 1px solid #e5e5e5; padding: 10px; margin-top: 20px;">
					' . $span_endereco . '
				</div>';

        // $bodyy = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">&copy;
        // ' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
        // ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 20px; font-family: Arial; color: #666;">Recebemos
        // seu pedido: ' . $numeroPedido . '</p>
        // </div>
        // <div
        // style="border-bottom: 0px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 15px; font-family: Arial; color: #666;">
        // Olá <b>' . $nomeCliente . '</b>,
        // </p>
        // <br>
        // <p style="font-size: 15px; font-family: Arial; color: #666;">O
        // pagamento de seu pedido número ' . $numeroPedido . ' foi confirmado pelo nosso
        // sistema.</p>
        // <br> <br>
        // <p style="font-size: 18px; font-family: Arial; color: #666;">Estágios
        // da sua compra</p>
        // <br>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #008109; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #FFF; text-align: center; padding-left: 20%;">&#9745;
        // Pedido Realizado</span>
        // </div>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #eee; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #666; text-align: center; padding-left: 13%;">Aprovação
        // de Pagamento</span>
        // </div>
        // <div
        // style="width: 33%; position: relative; float: left; height: 40px; background: #eee; padding-top: 20px;">
        // <span
        // style="font-family: Arial; font-size: 13px; color: #666; text-align: center; padding-left: 20%;">Produto
        // em transporte</span>
        // </div>
        // <div
        // style="background: #eee; height: 30px; margin-top: 70px; width: 99%; padding-top: 10px;">
        // <span
        // style="font-family: Arial; font-size: 12px; color: #666; text-align: center; padding-left: 9%;"><b
        // style="color: red;">PRAZO E ENTREGA:</b> consulta essas
        // informações em seu cadastro na loja, no menu "Minhas Compras".</span>
        // </div>
        // </div>
        // <div style="border: 1px solid #e5e5e5; padding: 5px;">
        // <table>
        // <tr
        // style="background: #eee; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        // <td width="700"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Produto:</span></td>
        // <td width="200"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Qtd/Preço:</span></td>
        // </tr>
        // ' . $tr_produtos . '
        // </table>
        // </div>
        // <div
        // style="border: 1px solid #e5e5e5; padding: 10px; margin-top: 20px;">
        // ' . $span_endereco . '
        // </div></td>
        // </tr>
        // <tr>
        // <td width="700"><img alt="" style="width: 45%;"
        // src="cid:1001"></td>
        // <td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        // ao consumidor ' . NOME_LOJA . '</p>
        // <p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
        // ' . TELEFONE_CONTATO . '</p>
        // <p style="font-size: 15px; font-family: Arial;">&#9993; Email:
        // ' . EMAIL_CONTATO . '</p></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function cancelamentoPedido($nomeCliente, $numeroPedido, $produtos = Array(), $endereco = Array())
    {
        $tr_produtos = '';
        foreach ($produtos as $prod) {
            $tr_produtos .= '<tr style="background: #fff; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
						<td width="700"><span
							style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['produto'] . '</span></td>
						<td width="200"><span
							style="margin-left: 15px; font-size: 15px; color: #666;">' . $prod['quantidade'] . ' por
								R$ ' . ValidateUtil::setFormatMoney($prod['preco']) . '</span></td>
					</tr>';
        }

        $span_endereco = '<span style="font-family: Arial; color: #666;"><b>Endereço de
                    							Entrega</b></span> <br> <br> <span
                    						style="font-family: Arial; color: #666; font-size: 13px;">' . $endereco[0]['endereco'] . ', ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['numero'] . ' - ' . $endereco[0]['bairro'] . '<br>' . $endereco[0]['cidade'] . ' / ' . $endereco[0]['uf'] . ' - CEP:
                    						' . $endereco[0]['cep'] . '
                    					</span>';

        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: #666;">O seu pedido número ' . $numeroPedido . ' foi cancelado.</p>';
        $body .= '<table>
					<tr
						style="background: #eee; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
						<td width="700"><span
							style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Produto:</span></td>
						<td width="200"><span
							style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Qtd/Preço:</span></td>
					</tr>
					' . $tr_produtos . '
				</table>';
        $body .= $span_endereco;

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">&copy;
        // ' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">&#9742; Telefone:
        // ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 20px; font-family: Arial; color: #666;"><b>PEDIDO CANCELADO</b></p>
        // </div>
        // <div
        // style="border-bottom: 0px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 15px; font-family: Arial; color: #666;">
        // Olá <b>' . $nomeCliente . '</b>,
        // </p>
        // <br>
        // <p style="font-size: 15px; font-family: Arial; color: #666;">O seu pedido número ' . $numeroPedido . ' foi cancelado.</p>
        // </div>
        // <div style="border: 1px solid #e5e5e5; padding: 5px;">
        // <table>
        // <tr
        // style="background: #eee; width: 95%; font-family: Arial; padding-left: 10px; height: 40px;">
        // <td width="700"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Produto:</span></td>
        // <td width="200"><span
        // style="margin-left: 15px; font-size: 15px; font-weight: 600; color: #666;">Qtd/Preço:</span></td>
        // </tr>
        // ' . $tr_produtos . '
        // </table>
        // </div>
        // <div
        // style="border: 1px solid #e5e5e5; padding: 10px; margin-top: 20px;">
        // ' . $span_endereco . '
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function segundaViaBoletoMercadoPago($nomeCliente, $linkBoleto, $valorBoleto = '')
    {
        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 16px; font-family: Arial;">O prazo para pagamento do boleto no valor de R$ ' . $valorBoleto . ' para ' . NOME_LOJA . ' irá vencer em breve.</p>';
        $body .= '<p style="font-size: 16px; font-family: Arial;"><i>Aproveite: Para concluir a transação, <a href="' . $linkBoleto . '" target="new">imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso</i></p>';

        // $bodyy = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 80%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p><br>
        // <table>
        // <tr>
        // <td colspan="2"> <p style="font-size: 16px; font-family: Arial;"><i>O prazo para pagamento do boleto no valor de R$ ' . $valorBoleto . ' para ' . NOME_LOJA . ' LTDA irá vencer em breve.</i></p></td>
        // </tr>
        // <tr style="display: none;">
        // <td colspan="2"> <br><p style="font-size: 16px; font-family: Arial;"><i>Aproveite: Para concluir a transação, <a href="' . $linkBoleto . '" target="new">imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso</i></p></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"> <br><p style="font-size: 16px; font-family: Arial;"><i>Para acompanhar as suas transações ou alterar os dados da sua conta, acesse http://' . LINK_LOJA . ' </i></p></td>
        // </tr>
        // </table>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Atenciosamente</p><p style="font-size: 16px; font-family: Arial;">Equipe ' . NOME_LOJA . '</p>

        // <p style="font-size: 18px; font-family: Arial;"PagSeguro. Sua compra protegida.</p>
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function segundaViaBoletoPagarme($nomeCliente, $linkBoleto, $valorBoleto = '', $codigoBarras = '')
    {
        $body .= "<h2>Olá " . $nomeCliente . "</h2>";
        $body .= "<p style='font-size: 14px; font-family: Arial;'>O prazo para pagamento do boleto no valor de R$ " . $valorBoleto . " para " . NOME_LOJA . " irá vencer em breve.</p>";
        $body .= "<p style='font-size: 14px; font-family: Arial;'>Aproveite: Para concluir a transação, <a href='" . $linkBoleto . "' target='new'>imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso.</p>";

        if ($codigoBarras != NULL) {
            $body .= "<p style='font-size: 14px; font-family: Arial;'><i>Código de barras: <br><br>  " . $codigoBarras . "</i></p>";
        }

        // $body = "<div style='border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;'>
        // <table cellspacing='0' cellpadding='10' border='0'>
        // <tr>
        // <td width='120'><img alt='' style='width: 80%;' src='cid:1001'></td>
        // <td width='600'><a href='#'>" . LINK_LOJA . "</a>
        // <p style='font-size: 15px; font-family: Arial;'>Telefone: " . TELEFONE_CONTATO . "</p></td>
        // </tr>
        // <tr>
        // <td width='600' colspan='2'><div style='border-top: 2px solid #931b85; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;'>
        // <p style='font-size: 18px; font-family: Arial;'>Olá " . $nomeCliente . ",</p><br>
        // <table>
        // <tr>
        // <td colspan='2'> <p style='font-size: 16px; font-family: Arial;'>O prazo para pagamento do boleto no valor de R$ " . $valorBoleto . " para " . NOME_LOJA . " LTDA irá vencer em breve.</p></td>
        // </tr>
        // <tr>
        // <td colspan='2'> <br><p style='font-size: 16px; font-family: Arial;'>Aproveite: Para concluir a transação, <a href='" . $linkBoleto . "' target='new'>imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso.</p></td>
        // </tr>
        // <tr>
        // <td colspan='2'></td>
        // </tr>";
        // if ($codigoBarras != NULL) {
        // $body .= " <tr>
        // <td colspan='2'> <br><p style='font-size: 16px; font-family: Arial;'><i>Código de barras: <br><br> " . $codigoBarras . "</i></p></td>
        // </tr>";
        // }
        // $body .= " <tr>
        // <td colspan='2'></td>
        // </tr>
        // <tr>
        // <td colspan='2'></td>
        // </tr>
        // <tr>
        // <td colspan='2'></td>
        // </tr>
        // <tr>
        // <td colspan='2'><p style='font-size: 16px; font-family: Arial;'>Para Acompanhar seu pedido em nosso site, basta realizar o login com os seguintes dados:</p></td>
        // </tr>
        // <tr>
        // <td colspan='2'><ul><li>Nossa Loja: <a href='" . LINK_LOJA . "'>" . LINK_LOJA . "</a></li></ul></td>
        // </tr>
        // <tr>
        // <td colspan='2'> <br><p style='font-size: 16px; font-family: Arial;'>Para acompanhar as suas transações ou alterar os dados da sua conta, acesse http://" . LINK_LOJA . " </p></td>
        // </tr>
        // </table>
        // <br>
        // <p style='font-size: 18px; font-family: Arial;'>Atenciosamente</p><p style='font-size: 16px; font-family: Arial;'>Equipe " . NOME_LOJA . "</p>

        // <p style='font-size: 18px; font-family: Arial;'>PagSeguro. Sua compra protegida.</p>
        // </div></td>
        // </tr>
        // <tr>
        // <td width='700'><img alt='' style='width: 45%;' src='cid:1001'></td>
        // <td width='500'><p style='font-size: 15px; font-family: Arial;'>Atendimento ao consumidor " . NOME_LOJA . "</p>
        // <p style='font-size: 15px; font-family: Arial;'>Telefone: " . TELEFONE_CONTATO . "</p>
        // <p style='font-size: 15px; font-family: Arial;'>Email: " . EMAIL_CONTATO . "</p></td>
        // </tr>
        // <tr><td><p style='font-size: 13px; font-family: Arial; text-align: center;'>Este é um e-mail automático disparado pelo sistema. Favor não respondê-lo, pois esta conta não é monitorada</p></td></tr>
        // </table>
        // </div>";

        return $body;
    }

    public function segundaViaBoleto($nomeCliente, $linkBoleto, $valorBoleto = '', $email = '', $senha = '')
    {
        $body = "<h2>Olá " . $nomeCliente . "</h2>";
        $body .= "<p style='font-size: 14px; font-family: Arial;'>O prazo para pagamento do boleto no valor de R$ " . $valorBoleto . " para " . NOME_LOJA . " irá vencer em breve.</p>";
        $body .= "<p style='font-size: 14px; font-family: Arial;'>Aproveite: Para concluir a transação, <a href='" . $linkBoleto . "' target='new'>imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso.</p>";

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 80%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p><br>
        // <table>
        // <tr>
        // <td colspan="2"> <p style="font-size: 16px; font-family: Arial;"><i>O prazo para pagamento do boleto no valor de R$ ' . $valorBoleto . ' para ' . NOME_LOJA . ' LTDA irá vencer em breve.</i></p></td>
        // </tr>
        // <tr>
        // <td colspan="2"> <br><p style="font-size: 16px; font-family: Arial;"><i>Aproveite: Para concluir a transação, <a href="' . $linkBoleto . '" target="new">imprima o boleto e pague até o vencimento</a>. Caso já tenha efetuado o pagamento, desconsidere este aviso</i></p></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"></td>
        // </tr>
        // <tr>
        // <td colspan="2"><p style="font-size: 16px; font-family: Arial;">Para Acompanhar seu pedido em nosso site, basta realizar o login com o seu usuário e senha. </p><br></td>
        // </tr>
        // <tr>
        // <td colspan="2"><ul><li>Nossa Loja: <a href="https://' . LINK_LOJA . '">' . LINK_LOJA . '</a></li></li></td>
        // </tr>
        // <tr style="display: none;">
        // <td colspan="2"><ul><li>E-mail: ' . $email . '</li></li></td>
        // </tr>
        // <tr style="display: none;">
        // <td colspan="2"><ul><li>Senha: ' . $senha . '</li></li></td>
        // </tr>
        // <tr>
        // <td colspan="2"> <br><p style="font-size: 16px; font-family: Arial;"><i>Para acompanhar as suas transações ou alterar os dados da sua conta, acesse http://' . LINK_LOJA . ' </i></p></td>
        // </tr>
        // </table>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Atenciosamente</p><p style="font-size: 16px; font-family: Arial;">Equipe ' . NOME_LOJA . '</p>

        // <p style="font-size: 18px; font-family: Arial;"PagSeguro. Sua compra protegida.</p>
        // </div></td>
        // </tr>
        // <tr>
        // <td width="700"><img alt="" style="width: 45%;"
        // src="cid:1001"></td>
        // <td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        // ao consumidor ' . NOME_LOJA . '</p>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p>
        // <p style="font-size: 15px; font-family: Arial;">Email:
        // ' . EMAIL_CONTATO . '</p></td>
        // </tr>
        // <tr><td><p style="font-size: 13px; font-family: Arial; text-align: center;">Este é um e-mail automático disparado pelo sistema. Favor não respondê-lo, pois esta conta não é monitorada</p></td></tr>
        // </table>
        // </div>';

        return $body;
    }

    public function mensagemCliente($nomeCliente, $email, $mensagem)
    {
        $body = '<div
        	style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        	<table cellspacing="0" cellpadding="10" border="0">
        		<tr>
        			<td width="120"><img alt="" style="width: 80%;"
        				src="cid:1001"></td>
        			<td width="600"><a href="#"><p
        						style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        		</tr>
        		<tr>
        			<td width="600" colspan="2"><div
        					style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
                            <p style="font-size: 18px; font-family: Arial;">Olá Bella, você recebeu a seguinte mensagem,</p><br>
                            <table>
                                <tr>    
                                    <td colspan="2"> <p style="font-size: 16px; font-family: Arial;"><i>' . $mensagem . '</i></p></td>
                                </tr>
                            </table>
                            <ul><li><p style="font-size: 16px; font-family: Arial;"><i>' . $nomeCliente . '</i></p></li><li><p style="font-size: 16px; font-family: Arial;"><i>' . $email . '</i></p></li>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">Confira ;D</p>
        				</div></td>
        		</tr>
        		<tr>
        			<td width="700"><img alt="" style="width: 45%;"
        				src="cid:1001"></td>
        			<td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        					ao consumidor ' . NOME_LOJA . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Email:
        					' . EMAIL_CONTATO . '</p></td>
        		</tr>
        	</table>
        </div>';

        return $body;
    }

    public function compraInCheckout($nomeCliente, $numeroPedido)
    {
        $body = '<div
        	style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        	<table cellspacing="0" cellpadding="10" border="0">
        		<tr>
        			<td width="120"><img alt="" style="width: 80%;"
        				src="cid:1001"></td>
        			<td width="600"><a href="#"><p
        						style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        		</tr>
        		<tr>
        			<td width="600" colspan="2"><div
        					style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        					<p style="font-size: 18px; font-family: Arial;">Olá Bella,</p>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">O Cliente ' . $nomeCliente . ' acaba de gerar um pedido no sistema.</p>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">Confira ;D</p>
        				</div></td>
        		</tr>
        		<tr>
        			<td width="700"><img alt="" style="width: 45%;"
        				src="cid:1001"></td>
        			<td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        					ao consumidor ' . NOME_LOJA . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Email:
        					' . EMAIL_CONTATO . '</p></td>
        		</tr>
        	</table>
        </div>';

        return $body;
    }

    public function confirmacaoNewsletter($email)
    {
        $body = '<h2>Prezado Cliente</h2>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Estamos cadastrando você em nossa newsletter, para receber todas as promocoes e novidades de nosso site ' . NOME_LOJA . ' .</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Gostariamos que confirmasse o interesse em receber emails atraves do link abaixo.</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Adotamos esta politica em respeito a todos os nossos clientes, evitando assim o recebimento de mensagens nao desejaveis - SPAMs.</p>';

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 80%;"
        // src="cid:1001"></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Prezado Cliente,</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Estamos cadastrando você em nossa newsletter, para receber todas as promoções e novidades de nosso site ' . NOME_LOJA . ' .</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Gostaríamos que confirmasse o interesse em receber emails através do link abaixo.</p>
        // <br>
        // <a href="' . LINK_LOJA . '/?m=cliente&c=cliente&a=confirmar_newsletter&email=' . $email . '"><p style="font-size: 18px; font-family: Arial;">Confirme aqui para receber mais emails da loja.</p></a>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Adotamos esta política em respeito a todos os nossos clientes, evitando assim o recebimento de mensagens não desejáveis - SPAMs.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Nunca seus dados serão fornecidos à terceiros, garantindo a sua privacidade.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Atenciosamente, <br> ' . NOME_LOJA . ' </p>
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function confirmacaoPagamento($nomeCliente, $numeroPedido)
    {
        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">O pagamento de seu pedido número ' . $numeroPedido . ' foi confirmado pelo nosso sistema.</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Efetuaremos o envio no prazo estabelecido durante o processo de compra.</p>';

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">O pagamento de seu
        // pedido número ' . $numeroPedido . ' foi confirmado pelo nosso sistema.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Efetuaremos o envio
        // no prazo estabelecido durante o processo de compra.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Qualquer dúvida
        // estamos à disposição.</p>
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function pedidoEmAnalise($nomeCliente, $numeroPedido)
    {
        $body = '<div
        	style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        	<table cellspacing="0" cellpadding="10" border="0">
        		<tr>
        			<td width="120"><img alt="" style="width: 70%;"
        				src="cid:1001"></td>
        			<td width="600"><a href="#"><p
        						style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        		</tr>
        		<tr>
        			<td width="600" colspan="2"><div
        					style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        					<p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">Recebemos o seu pedido e estamos processando seu pagamento para verificar a sua Autenticidade, mas fique tranquilo que em breve seu pedido irá ser aprovado e enviado pela nossa equipe.</p>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">Desde já agradecemos a sua preferência e compreensão :D.</p>
        					<br>
        					<p style="font-size: 18px; font-family: Arial;">Qualquer dúvida
        						estamos à disposição.</p>
        				</div></td>
        		</tr>
        		<tr>
        			<td width="700"><img alt="" style="width: 45%;"
        				src="cid:1001"></td>
        			<td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        					ao consumidor ' . NOME_LOJA . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p>
        				<p style="font-size: 15px; font-family: Arial;">Email:
        					' . EMAIL_CONTATO . '</p></td>
        		</tr>
        	</table>
        </div>';

        return $body;
    }

    public function contaCliente($nomeCliente, $email, $senha)
    {
        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">O pagamento de seu pedido número foi confirmado pelo nosso sistema.</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Efetuaremos o envio no prazo estabelecido durante o processo de compra.</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Para acompanhar seu pedido em nosso site, basta realizar o login com os seu email e senha.</p>';
        $body .= '<p style="font-size: 15px; font-family: Arial; color: red;">É de extrema importância que confirme o seu endereço no nosso site, pois se estiver algum dado errado no endereço, não nos responsabilizaremos por qualquer constrangimento futuro.</p>';

        // $body = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p>
        // <p style="font-size: 18px; font-family: Arial;">O pagamento de seu
        // pedido número foi confirmado pelo nosso sistema.</p>
        // <p style="font-size: 18px; font-family: Arial;">Efetuaremos o envio
        // no prazo estabelecido durante o processo de compra.</p>
        // <p style="font-size: 18px; font-family: Arial;">Para Acompanhar seu Pedido em nosso site, basta realizar o login com os seguintes dados:.</p>
        // <ul>
        // <li>E-mail: ' . $email . '</li>
        // <li>Senha: ' . $senha . '</li>
        // </ul>
        // <p style="font-size: 18px; font-family: Arial;">Qualquer dúvida
        // estamos à disposição.</p>
        // </div></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2">
        // <p style="font-size: 15px; font-family: Arial; color: red;">Obs.: É de extrema importância que confirme o seu endereço no nosso site, pois se estiver algum dado errado no endereço, não nos responsabilizaremos por qualquer constrangimento futuro.</p>
        // <br>
        // </td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function pedidoEnviado($nomeCliente, $numeroPedido, $numeroRastreio)
    {
        $body = '<h2>Olá ' . $nomeCliente . '</h2>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Seu pedido número ' . $numeroPedido . ' acaba de ser enviado.</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">O número de rastreamento do seu pedido é ' . $numeroRastreio . '</p>';
        $body .= '<p style="font-size: 18px; font-family: Arial;">Você pode rastrear diretamente pelo site dos correios. Caso ainda não tenha a informação disponível, aguarde algumas horas e tente novamente.</p>';

        // $bodyy = '<div
        // style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 80%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <p style="font-size: 18px; font-family: Arial;">Olá ' . $nomeCliente . ',</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Seu pedido número ' . $numeroPedido . ' acaba de ser enviado.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">O número de rastreamento do seu pedido é ' . $numeroRastreio . ' Você pode rastrear diretamente pelo site dos correios. Caso ainda não tenha a informação disponível, aguarde algumas horas e tente novamente.</p>
        // <br>
        // <p style="font-size: 18px; font-family: Arial;">Qualquer dúvida
        // estamos à disposição.</p>
        // </div></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }

    public function pedidoCancelado($nomeCliente, $numeroPedido)
    {
        $body = '<h2>Olá, ' . $nomeCliente . '</h2>
                 <p>Informamos que seu pedido Nº ' . $numeroPedido . ' foi CANCELADO</p>
                 <p>Veja abaixo os possíveis motivos para o cancelamento de sua compra:</p>
                 <p>A compra de créditos não foi paga.</p>
                 <p>O número do seu Depósito Identificado não está correto ou não foi informado; por isso, o sistema não conseguiu identificar o pagamento de seu pedido. </p>
                 <p>Boleto Bancário: até as 19h50 (Brasília) da data de vencimento do seu boleto bancário.</p>
                 <p>A compra não foi paga no prazo de acordo com a forma de pagamento escolhida:.</p>
                 <p>Por favor, realize sua compra novamente no site: ' . LINK_LOJA . ' </p>';

        // $body .= '<body>' . '<div style="width: 100%; height: 100%; border: 2px solid #FAFAFA;">
        // <img src="cid:1001" style="width: 190px; height: 70px; display: block; margin-left: auto; margin-right: auto;"></img>
        // <hr style="border: 2px solid #d33665; opacity: 0.4;"/>
        // <div style="padding: 10px 10px 10px 10px;">
        // <h2>Olá, ' . $nomeCliente . '</h2>
        // <p>Informamos que seu pedido Nº ' . $numeroPedido . ' foi <b style="color: red;">CANCELADO</b>!</p>
        // <p>Veja abaixo os possíveis motivos para o cancelamento de sua compra:</p>
        // <ul><li> A compra de créditos não foi paga.</li>
        // <li> O número do seu Depósito Identificado não está correto ou não foi informado; por isso, o sistema não conseguiu identificar o pagamento de seu pedido. </li>
        // <li> Boleto Bancário: até as 19h50 (Brasília) da data de vencimento do seu boleto bancário.</li>
        // <li> A compra não foi paga no prazo de acordo com a forma de pagamento escolhida:.</li></ul>
        // <p>Por favor, realize sua compra novamente no site: ' . LINK_LOJA . ' </p>';
        // $body .= ' <center><p>Atenciosamente,</p></center>
        // <center><p>Equipe ' . NOME_LOJA . '</p></center>
        // </div>
        // </div></body>';

        return $body;
    }

    // Deprecared
    public function duvidas($nomeCliente, $email)
    {
        $body = '<h2><i>Prezado(a) ' . $nomeCliente . '</i></h2>

                Muito obrigado(a) por nos contactar. Vamos lá aos seguintes tópicos:
                
                <p style="font-size: 16px; font-family: Arial;">1º Qual o prazo de entrega:</p>
                
                 - Nosso prazo de entrega é canculado na nossa página, basta inserir o seu cep que nosso sistema irá calcular o frete imediatamente para você.
                
                <p style="font-size: 16px; font-family: Arial;">2º Quando o(a) cliente recebe o código de rastreamento: </p>
                
                 - Enviamos o código de rastreio por e-mail em até 3 dias úteis após a confirmação do pagamento. Lembrando que o Código de Rastreamento pode levar até 72 horas úteis para atualizar no banco de dados dos Correios para consulta.
                
                <p style="font-size: 16px; font-family: Arial;">3º Frete: </p>
                
                 - Compra de no mínimo R$ ' . ValidateUtil::setFormatMoney(VALOR_MINIMO_PARA_FRETE_GRATIS) . ' você ganha frete grátis.

                <p style="font-size: 16px; font-family: Arial;">4º Como corrigir o endereço que coloquei errado: </p>

                 - Para conrreção de endereço, basta acessar o site da nossa loja e entrar com seu email e senha. Você vai cair direto em uma área de pedidos, e na aba endereço você pode alterar seu endereço.
                    
                Lembrando que depois que o pedido for enviado, não há mais possibilidade de alteração de endereço nos correios.
                
                <p style="font-size: 16px; font-family: Arial;">Caso haja mais dúvidas ou sugestões, pode nos contactar. </p>
                
                <p style="font-size: 16px; font-family: Arial;">Atenciosamente,</p>
                
                <p style="font-size: 16px; font-family: Arial;">Equipe ' . NOME_LOJA . ' </p>';

        // $body = '<div style="border: 1px solid #e5e5e5; width: 750px; margin: 0 auto; padding: 10px;">
        // <table cellspacing="0" cellpadding="10" border="0">
        // <tr>
        // <td width="120"><img alt="" style="width: 70%;"
        // src="cid:1001"></td>
        // <td width="600"><a href="#"><p
        // style="font-size: 15px; font-family: Arial;">' . LINK_LOJA . '</p></a></td>
        // </tr>
        // <tr>
        // <td width="600" colspan="2"><div
        // style="border-top: 2px solid #e5e5e5; border-bottom: 2px solid #e5e5e5; padding-top: 25px; padding-bottom: 25px;">
        // <table>
        // <tr>
        // <td colspan="2"> <p style="font-size: 16px; font-family: Arial;"><i>Prezado(a) ' . $nomeCliente . ',

        // Muito obrigado(a) por nos contactar. Vamos lá aos seguintes tópicos:

        // <p style="font-size: 16px; font-family: Arial;">1º Qual o prazo de entrega:</p>

        //  - Nosso prazo de entrega é de 25 à 30 dias úteis.

        // <p style="font-size: 16px; font-family: Arial;">2º Quando o(a) cliente recebe o código de rastreamento: </p>

        //  - Enviamos o código de rastreio por e-mail em até 3 dias úteis após a confirmação do pagamento. Lembrando que o Código de Rastreamento pode levar até 72 horas úteis para atualizar no banco de dados dos Correios para consulta.

        // <p style="font-size: 16px; font-family: Arial;">3º Frete: </p>

        //  - O nosso frete é totalmente grátis.

        // <p style="font-size: 16px; font-family: Arial;">4º Como corrigir o endereço que coloquei errado: </p>

        //  - Para conrreção de endereço, basta acessar o site da nossa loja e entrar com seu email: ' . $email . ' e a senha: <b>eusoubela@56</b>. Você vai cair direto em uma área de pedidos, e na aba endereço você pode alterar seu endereço.
        // Lembrando que depois que o pedido for enviado, não há mais possibilidade de alteração de endereço nos correios.

        // <p style="font-size: 16px; font-family: Arial;">Caso haja mais dúvidas ou sugestões, pode nos contactar. </p>

        // <p style="font-size: 16px; font-family: Arial;">Atenciosamente,</p>

        // <p style="font-size: 16px; font-family: Arial;">Equipe ' . NOME_LOJA . ' </p>

        // </i></p>
        // </td>
        // </tr>
        // </table>
        // </div></td>
        // </tr>
        // <tr>
        // <td width="700"><img alt="" style="width: 45%;"
        // src="cid:1001"></td>
        // <td width="500"><p style="font-size: 15px; font-family: Arial;">Atendimento
        // ao consumidor ' . NOME_LOJA . '</p>
        // <p style="font-size: 15px; font-family: Arial;">Telefone: ' . TELEFONE_CONTATO . '</p>
        // <p style="font-size: 15px; font-family: Arial;">Email:
        // ' . EMAIL_CONTATO . '</p></td>
        // </tr>
        // </table>
        // </div>';

        return $body;
    }
}