<?php
use Krypitonite\Util\DateUtil;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Relátorio</title>

<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/iCheck/skins/flat/green.css"
	rel="stylesheet">
<link href="public/admin/build/css/custom.min.css" rel="stylesheet">
</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php require_once 'src/Sistema/View/menu.php';?>
			<div class="right_col" role="main">
				<div class="">
					<div class="page-title">
						<div class="title_left">
							<h3>
								Relatório <small> de pedidos</small>
							</h3>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="row">
						<div class="col-md-12">
							<div class="x_panel">
								<div class="x_title">
									<!-- 									<h2>New Partner Contracts Consultancy</h2> -->
									<ul class="nav navbar-right panel_toolbox">
										<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
										</li>
										<li class="dropdown"><a href="#" class="dropdown-toggle"
											data-toggle="dropdown" role="button" aria-expanded="false"><i
												class="fa fa-wrench"></i></a>
											</li>
										<li><a class="close-link"><i class="fa fa-close"></i></a></li>
									</ul>
									<div class="clearfix"></div>
								</div>

								<div class="x_content">
									<div class="col-xs-2">
										<ul class="nav nav-tabs tabs-left">
											<li class="active" style="border: 0px;"><a href="#ano"
												data-toggle="tab">Ano</a></li>
											<li><a href="#mes-passado" data-toggle="tab"
												onclick="atualizarChart();">Mês passado</a></li>
											<li><a href="#mes-atual" data-toggle="tab"
												onclick="atualizarChart();">Esse mês</a></li>
											<li><a href="#ultimos-7-dias" data-toggle="tab"
												onclick="atualizarChart();">Últimos 7 dias</a></li>
										</ul>
									</div>
									<div class="col-md-10 col-sm-10 col-xs-12">
										<div class="tab-content">
											<div class="tab-pane active" id="ano">
												<div>
													<ul class="stats-overview">
														<li><span class="name"> Vendas neste período </span> <span
															class="value text-success"> R$ <?=$data['total_faturamento_anual'];?> </span></li>
														<li><span class="name"> Média de vendas mensais </span> <span
															class="value text-success"> R$ <?=$data['mendia_vendas_menais_anual'];?> </span></li>
														<li class="hidden-phone"><span class="name"> Pedidos
																feitos </span> <span class="value text-success"> <?=$data['total_pedidodos_aprovado_anual'];?> </span></li>
													</ul>
													<br />

													<div id="grafico-ano" style="height: 350px;"></div>
													<section class="panel">
														<div class="x_title">

															<div class="clearfix"></div>
														</div>
														<div class="panel-body">
															<h4 class="green">Detalhamento de vendas</h4>
															<br>

															<div class="project_detail">

																<p class="title">
																	<i class="fa fa-facebook"></i> Facevook Ads
																</p>
																<p>R$ <?=$data['gasto_facebook_anual'];?></p>

																<p class="title">
																	<i class="fa fa-money"></i> Lucro Líquido
																</p>
																<p>R$ <?=$data['lucro_liquido_anual'];?></p>
																<p class="title">
																	<i class="fa fa-thumbs-o-down"></i> Chargebacks
																</p>
																<p>R$ <?=$data['chargebacks_anual'];?></p>	
																<p class="title">
																	<i class="fa fa-user"></i> Novos Clientes
																</p>
																<p><?=$data['clientes_cadastrados_anual'];?></p>

																<p class="title">
																	<i class="fa fa-truck"></i> Frete Correios
																</p>
																<p>R$ <?=$data['custo_frete_anual'];?></p>

																<p class="title">
																	<i class="fa fa-shopping-cart"></i> Itens Comprados
																</p>
																<p><?=$data['itens_comprados_anual'];?></p>
															</div>
															<br />
															<div class="text-center mtop20">
																<!-- 																<a href="#" class="btn btn-sm btn-primary">Imprimir</a> -->
															</div>
														</div>

													</section>
												</div>
											</div>
											<div class="tab-pane" id="mes-passado">
												<div>
													<ul class="stats-overview">
														<li><span class="name"> Vendas neste período </span> <span
															class="value text-success"> R$ <?=$data['total_faturamento_mes_passado'];?> </span></li>
														<li><span class="name"> Média de vendas diárias </span> <span
															class="value text-success"> R$ <?=$data['mendia_vendas_menais_mes_passado'];?> </span></li>
														<li class="hidden-phone"><span class="name"> Pedidos
																feitos </span> <span class="value text-success"> <?=$data['total_pedidodos_aprovado_mes_passado'];?> </span></li>
													</ul>
													<br />

													<div id="grafico-mes-passado" style="height: 350px;"></div>
													<section class="panel">
														<div class="x_title">

															<div class="clearfix"></div>
														</div>
														<div class="panel-body">
															<h4 class="green">Detalhamento de vendas</h4>
															<br>

															<div class="project_detail">

																<p class="title">
																	<i class="fa fa-facebook"></i> Facevook Ads
																</p>
																<p>R$ <?=$data['gasto_facebook_mes_passado'];?></p>

																<p class="title">
																	<i class="fa fa-money"></i> Lucro Líquido
																</p>
																<p>R$ <?=$data['lucro_liquido_mes_passado'];?></p>
																<p class="title">
																	<i class="fa fa-thumbs-o-down"></i> Chargebacks
																</p>
																<p>R$ <?=$data['chargebacks_mes_passado'];?></p>	
																<p class="title">
																	<i class="fa fa-user"></i> Novos Clientes
																</p>
																<p><?=$data['clientes_cadastrados_mes_passado'];?></p>

																<p class="title">
																	<i class="fa fa-truck"></i> Frete Correios
																</p>
																<p>R$ <?=$data['custo_frete_mes_passado'];?></p>

																<p class="title">
																	<i class="fa fa-shopping-cart"></i> Itens Comprados
																</p>
																<p><?=$data['itens_comprados_mes_passado'];?></p>
															</div>
															<br />
															<div class="text-center mtop20">
																<!-- 																<a href="#" class="btn btn-sm btn-primary">Imprimir</a> -->
															</div>
														</div>

													</section>
												</div>
											</div>
											<div class="tab-pane" id="mes-atual">
												<div>
													<ul class="stats-overview">
														<li><span class="name"> Vendas neste período </span> <span
															class="value text-success"> R$ <?=$data['total_faturamento_mes_atual'];?> </span></li>
														<li><span class="name"> Média de vendas diárias </span> <span
															class="value text-success"> R$ <?=$data['mendia_vendas_menais_mes_atual'];?> </span></li>
														<li class="hidden-phone"><span class="name"> Pedidos
																feitos </span> <span class="value text-success"> <?=$data['total_pedidodos_aprovado_mes_atual'];?> </span></li>
													</ul>
													<br />

													<div id="grafico-mes-atual" style="height: 350px;"></div>
													<section class="panel">
														<div class="x_title">

															<div class="clearfix"></div>
														</div>
														<div class="panel-body">
															<h4 class="green">Detalhamento de vendas</h4>
															<br>

															<div class="project_detail">

																<p class="title">
																	<i class="fa fa-facebook"></i> Facevook Ads
																</p>
																<p>R$ <?=$data['gasto_facebook_mes_atual'];?></p>

																<p class="title">
																	<i class="fa fa-money"></i> Lucro Líquido
																</p>
																<p>R$ <?=$data['lucro_liquido_mes_atual'];?></p>
																<p class="title">
																	<i class="fa fa-thumbs-o-down"></i> Chargebacks
																</p>
																<p>R$ <?=$data['chargebacks_mes_atual'];?></p>	
																<p class="title">
																	<i class="fa fa-user"></i> Novos Clientes
																</p>
																<p><?=$data['clientes_cadastrados_mes_atual'];?></p>

																<p class="title">
																	<i class="fa fa-truck"></i> Frete Correios
																</p>
																<p>R$ <?=$data['custo_frete_mes_atual'];?></p>

																<p class="title">
																	<i class="fa fa-shopping-cart"></i> Itens Comprados
																</p>
																<p><?=$data['itens_comprados_mes_atual'];?></p>
															</div>
															<br />
															<div class="text-center mtop20">
																<!-- 																<a href="#" class="btn btn-sm btn-primary">Imprimir</a> -->
															</div>
														</div>

													</section>
												</div>
											</div>
											<div class="tab-pane" id="ultimos-7-dias">
												<div>
													<ul class="stats-overview">
														<li><span class="name"> Vendas neste período </span> <span
															class="value text-success"> R$ <?=$data['total_faturamento_ultimos_7_dias'];?> </span></li>
														<li><span class="name"> Média de vendas diárias </span> <span
															class="value text-success"> R$ <?=$data['mendia_vendas_menais_ultimos_7_dias'];?> </span></li>
														<li class="hidden-phone"><span class="name"> Pedidos
																feitos </span> <span class="value text-success"> <?=$data['total_pedidodos_aprovado_ultimos_7_dias'];?> </span></li>
													</ul>
													<br />

													<div id="grafico-ultimos-7-dias" style="height: 350px;"></div>
													<section class="panel">
														<div class="x_title">

															<div class="clearfix"></div>
														</div>
														<div class="panel-body">
															<h4 class="green">Detalhamento de vendas</h4>
															<br>
															<div class="project_detail">
																<p class="title">
																	<i class="fa fa-facebook"></i> Facevook Ads
																</p>
																<p>R$ <?=$data['gasto_facebook_ultimos_7_dias'];?></p>

																<p class="title">
																	<i class="fa fa-money"></i> Lucro Líquido
																</p>
																<p>R$ <?=$data['lucro_liquido_ultimos_7_dias'];?></p>
																<p class="title">
																	<i class="fa fa-thumbs-o-down"></i> Chargebacks
																</p>
																<p>R$ <?=$data['chargebacks_ultimos_7_dias'];?></p>	
																<p class="title">
																	<i class="fa fa-user"></i> Novos Clientes
																</p>
																<p><?=$data['clientes_cadastrados_ultimos_7_dias'];?></p>

																<p class="title">
																	<i class="fa fa-truck"></i> Frete Correios
																</p>
																<p>R$ <?=$data['custo_frete_ultimos_7_dias'];?></p>

																<p class="title">
																	<i class="fa fa-shopping-cart"></i> Itens Comprados
																</p>
																<p><?=$data['itens_comprados_ultimos_7_dias'];?></p>
															</div>
															<br />
															<div class="text-center mtop20">
																<!-- 																<a href="#" class="btn btn-sm btn-primary">Imprimir</a> -->
															</div>
														</div>

													</section>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<footer>
				<div class="pull-right">
				<?=NOME_LOJA;?> - Todos os direitos reservados <a
						href="https://<?=LINK_LOJA;?>"></a>
				</div>
				<div class="clearfix"></div>
			</footer>
		</div>
	</div>

	<script src="public/admin/vendors/jquery/dist/jquery.min.js"></script>
	<script src="public/admin/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="public/admin/vendors/fastclick/lib/fastclick.js"></script>
	<script src="public/admin/vendors/nprogress/nprogress.js"></script>
	<script src="public/admin/vendors/echarts/dist/echarts.min.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>
	<script>
	
	  function numberToReal(numero) {
	     var numero = numero.toFixed(2).split('.');
	     numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
	     return numero.join(',');
	  }
	  
      var theme = {
          color: [
              '#34495E', '#BDC3C7', '#3498DB',
              '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
          ],

          title: {
              itemGap: 8,
              textStyle: {
                  fontWeight: 'normal',
                  color: '#408829'
              }
          },

          dataRange: {
              color: ['#1f610a', '#97b58d']
          },

          toolbox: {
              color: ['#408829', '#408829', '#408829', '#408829']
          },

          tooltip: {
              backgroundColor: 'rgba(0,0,0,0.5)',
              axisPointer: {
                  type: 'line',
                  lineStyle: {
                      color: '#408829',
                      type: 'dashed'
                  },
                  crossStyle: {
                      color: '#408829'
                  },
                  shadowStyle: {
                      color: 'rgba(200,200,200,0.3)'
                  }
              }
          },

          dataZoom: {
              dataBackgroundColor: '#eee',
              fillerColor: 'rgba(64,136,41,0.2)',
              handleColor: '#408829'
          },
          grid: {
              borderWidth: 0
          },

          categoryAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },

          valueAxis: {
              axisLine: {
                  lineStyle: {
                      color: '#408829'
                  }
              },
              splitArea: {
                  show: true,
                  areaStyle: {
                      color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                  }
              },
              splitLine: {
                  lineStyle: {
                      color: ['#eee']
                  }
              }
          },
          timeline: {
              lineStyle: {
                  color: '#408829'
              },
              controlStyle: {
                  normal: {color: '#408829'},
                  emphasis: {color: '#408829'}
              }
          },

          k: {
              itemStyle: {
                  normal: {
                      color: '#68a54a',
                      color0: '#a9cba2',
                      lineStyle: {
                          width: 1,
                          color: '#408829',
                          color0: '#86b379'
                      }
                  }
              }
          },
          map: {
              itemStyle: {
                  normal: {
                      areaStyle: {
                          color: '#ddd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  },
                  emphasis: {
                      areaStyle: {
                          color: '#99d2dd'
                      },
                      label: {
                          textStyle: {
                              color: '#c12e34'
                          }
                      }
                  }
              }
          },
          force: {
              itemStyle: {
                  normal: {
                      linkStyle: {
                          strokeColor: '#408829'
                      }
                  }
              }
          },
          chord: {
              padding: 4,
              itemStyle: {
                  normal: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  },
                  emphasis: {
                      lineStyle: {
                          width: 1,
                          color: 'rgba(128, 128, 128, 0.5)'
                      },
                      chordStyle: {
                          lineStyle: {
                              width: 1,
                              color: 'rgba(128, 128, 128, 0.5)'
                          }
                      }
                  }
              }
          },
          gauge: {
              startAngle: 225,
              endAngle: -45,
              axisLine: {
                  show: true,
                  lineStyle: {
                      color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                      width: 8
                  }
              },
              axisTick: {
                  splitNumber: 10,
                  length: 12,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              axisLabel: {
                  textStyle: {
                      color: 'auto'
                  }
              },
              splitLine: {
                  length: 18,
                  lineStyle: {
                      color: 'auto'
                  }
              },
              pointer: {
                  length: '90%',
                  color: 'auto'
              },
              title: {
                  textStyle: {
                      color: '#333'
                  }
              },
              detail: {
                  textStyle: {
                      color: 'auto'
                  }
              }
          },
          textStyle: {
              fontFamily: 'Arial, Verdana, sans-serif'
          }
      };

      var chartAnual = echarts.init(document.getElementById('grafico-ano'), theme);

      chartAnual.setOption({
        title: {
          x: 'center',
          y: 'top',
          text: '',
          textStyle: {
            fontSize: 15,
            fontWeight: 'normal'
          }
        },
        tooltip: {
          trigger: 'axis'
        },
        toolbox: {
          show: true,
          feature: {
            dataView: {
              show: true,
              readOnly: false,
              title: "Detalhes",
              lang: [
                "Ver detalhes",
                "Fechar",
                "Atualizar",
              ],
            },
            restore: {
              show: false,
              title: 'Restore'
            },
            saveAsImage: {
              show: false,
              title: 'Salvar'
            }
          }
        },
        calculable: true,
        legend: {
          data: ['sales', 'purchases'],
          y: 'bottom'
        },
        xAxis: [{
          type: 'category',
          name: '<?=date('Y');?>',
          data: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Agos', 'Set', 'Out', 'Nov', 'Dez']
        }],
        yAxis: [{
          type: 'value',
          axisLabel: {
            formatter: 'R$ {value}'
          }
        }],
        series: [{
          name: 'Faturamento',
          type: 'bar',
          tooltip: {
              trigger: 'item',
              formatter: function(params) {
                  	return params.name +'<br> ' + numberToReal(params.value);
              }
          },
          data: <?=$data['faturamento_anual'];?>
        }]
      });

      var chartMesPassado = echarts.init(document.getElementById('grafico-mes-passado'), theme);
      chartMesPassado.setOption({
        title: {
          x: 'center',
          y: 'top',
          text: '',
          textStyle: {
            fontSize: 15,
            fontWeight: 'normal'
          }
        },
        tooltip: {
          trigger: 'axis'
        },
        toolbox: {
          show: true,
          feature: {
            dataView: {
              show: true,
              readOnly: true,
              title: "Detalhes",
              lang: [
                "Ver detalhes",
                "Fechar",
                "Atualizar",
              ],
            },
            restore: {
              show: false,
              title: 'Restore'
            },
            saveAsImage: {
              show: false,
              title: 'Salvar'
            }
          }
        },
        calculable: true,
        legend: {
          data: ['sales', 'purchases'],
          y: 'bottom'
        },
        xAxis: [{
          scale: true,
          type: 'category',
          name: '<?=ucfirst(DateUtil::monthLiteralShort((date('m') - 1))).date('/Y');?>',
          data: <?=trim($data['_dias_mes_passado']);?>
        }],
        yAxis: [{
          scale: false,
          axisLabel: {
              formatter: 'R$ {value}'
          }
        }],
        series: [{
          name: 'Faturamento',
          type: 'bar',
          tooltip: {
              trigger: 'item',
              formatter: function(params) {
                  	return params.name + '<br> ' + numberToReal(params.value);
              }
          },
          data: <?=$data['faturamento_mes_passado'];?>
        }]
      });

      var chartMesAtual = echarts.init(document.getElementById('grafico-mes-atual'), theme);
      chartMesAtual.setOption({
        title: {
          x: 'center',
          y: 'top',
          text: '',
          textStyle: {
            fontSize: 15,
            fontWeight: 'normal'
          }
        },
        tooltip: {
          trigger: 'axis'
        },
        toolbox: {
          show: true,
          feature: {
            dataView: {
              show: true,
              readOnly: true,
              title: "Detalhes",
              lang: [
                "Ver detalhes",
                "Fechar",
                "Atualizar",
              ],
            },
            restore: {
              show: false,
              title: 'Restore'
            },
            saveAsImage: {
              show: false,
              title: 'Salvar'
            }
          }
        },
        calculable: true,
        legend: {
          data: ['sales', 'purchases'],
          y: 'bottom'
        },
        xAxis: [{
          scale: true,
          type: 'category',
          name: '<?=ucfirst(DateUtil::monthLiteralShort(date('m'))).date('/Y');?>',
          data: <?=trim($data['_dias_mes_atual']);?>
        }],
        yAxis: [{
          scale: false,
          axisLabel: {
          	formatter: 'R$ {value}'
          }        
        }],
        series: [{
          name: 'Faturamento',
          type: 'bar',
          tooltip: {
              trigger: 'item',
              formatter: function(params) {
                  	return params.name + '<br> ' + numberToReal(params.value);
              }
          },
          data: <?=$data['faturamento_mes_atual'];?>
        }]
      });

      var chartUltimos7Dias = echarts.init(document.getElementById('grafico-ultimos-7-dias'), theme);
      chartUltimos7Dias.setOption({
        title: {
          x: 'center',
          y: 'top',
          text: '',
          textStyle: {
            fontSize: 15,
            fontWeight: 'normal'
          }
        },
        tooltip: {
          trigger: 'axis'
        },
        toolbox: {
          show: true,
          feature: {
            dataView: {
              show: true,
              readOnly: true,
              title: "Detalhes",
              lang: [
                "Ver detalhes",
                "Fechar",
                "Atualizar",
              ],
            },
            restore: {
              show: false,
              title: 'Restore'
            },
            saveAsImage: {
              show: false,
              title: 'Salvar'
            }
          }
        },
        calculable: true,
        legend: {
          data: ['sales', 'purchases'],
          y: 'bottom'
        },
        xAxis: [{
          scale: true,
          type: 'category',
          name: '',
          data: <?=trim($data['_dias_ultimos_7_dias']);?>
        }],
        yAxis: [{
          scale: false,
          type: 'value',
          axisLabel: {
          	formatter: 'R$ {value}'
          }  
        }],
        series: [{
          name: 'Faturamento',
          type: 'bar',
          tooltip: {
              trigger: 'item',
              formatter: function(params) {
                  	return params.name + '<br> ' + numberToReal(params.value);
              }
          },
          data: <?=$data['faturamento_ultimos_7_dias'];?>
        }]
      });
      
      function atualizarChart(){
    	  setInterval(function() {
    		  chartMesPassado.resize();
    		  chartAnual.resize();
    		  chartMesAtual.resize();
    		  chartUltimos7Dias.resize();
  		}, 100);
      }
    </script>
</body>
</html>