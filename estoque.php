<?php
$url = "https://www.catalogodecalcados.com.br/resultado_estoque.php?referencia=21.004A&revendedor=2022G880&tamanho=&est=a48c4739f0f50ee00aa99f4f88eb54b7&rand=37325";

$data = [];
$data['referencia'] = '21.004A';
$data['revendedor'] = '2022G880';
$data['tamanho'] = '';
$data['est'] = '8781d89e164afa2740ca044fba1115ee';
$data['rand'] = '56765';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.catalogodecalcados.com.br/resultado_estoque.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HEADER, false);
$response = curl_exec($ch);
var_export($response);
