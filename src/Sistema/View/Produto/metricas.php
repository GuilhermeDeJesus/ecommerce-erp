<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | Análise de Produtos</title>
<link href="public/admin/vendors/bootstrap/dist/css/bootstrap.min.css"
	rel="stylesheet">
<link href="public/admin/vendors/font-awesome/css/font-awesome.min.css"
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
								Gráficos de Produtos <small>Últimos Resultados</small>
							</h3>
						</div>
						<div class="title_right">
							<div
								class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
								<div class="input-group">
									<input type="text" class="form-control"
										placeholder="Search for..."> <span class="input-group-btn">
										<button class="btn btn-default" type="button">Go!</button>
									</span>
								</div>
							</div>
						</div>
					</div>
					<div id="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<?php foreach ($data['produtos'] as $p) {?>
							<h2 style="color: #212b36;"><?=$p['descricao'];?></h2>
							<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2>Histórico de Cartões e Boletos aprovados nos Últimos 30 dias</h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
												</li>
												<li class="dropdown"><a href="#" class="dropdown-toggle"
													data-toggle="dropdown" role="button" aria-expanded="false"><i
														class="fa fa-wrench"></i></a>
													<ul class="dropdown-menu" role="menu">
														<li><a href="#">Settings 1</a></li>
														<li><a href="#">Settings 2</a></li>
													</ul></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<div id="mainb_<?=$p['id'];?>" style="height: 350px;"></div>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2>Histórico de Cartões e Boletos aprovados no Mês</h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
												</li>
												<li class="dropdown"><a href="#" class="dropdown-toggle"
													data-toggle="dropdown" role="button" aria-expanded="false"><i
														class="fa fa-wrench"></i></a>
													<ul class="dropdown-menu" role="menu">
														<li><a href="#">Settings 1</a></li>
														<li><a href="#">Settings 2</a></li>
													</ul></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<div id="mainb_mes_<?=$p['id'];?>" style="height: 350px;"></div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2>Histórico de Pedidos Aprovados nos Últimos 30 dias</h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
												</li>
												<li class="dropdown"><a href="#" class="dropdown-toggle"
													data-toggle="dropdown" role="button" aria-expanded="false"><i
														class="fa fa-wrench"></i></a>
													<ul class="dropdown-menu" role="menu">
														<li><a href="#">Settings 1</a></li>
														<li><a href="#">Settings 2</a></li>
													</ul></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<div id="echart_donut_<?=$p['id'];?>" style="height: 350px;"></div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="x_panel">
										<div class="x_title">
											<h2>Histórico de Boletos Não Pagos Aprovados nos Últimos 30 dias</h2>
											<ul class="nav navbar-right panel_toolbox">
												<li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
												</li>
												<li class="dropdown"><a href="#" class="dropdown-toggle"
													data-toggle="dropdown" role="button" aria-expanded="false"><i
														class="fa fa-wrench"></i></a>
													<ul class="dropdown-menu" role="menu">
														<li><a href="#">Settings 1</a></li>
														<li><a href="#">Settings 2</a></li>
													</ul></li>
												<li><a class="close-link"><i class="fa fa-close"></i></a></li>
											</ul>
											<div class="clearfix"></div>
										</div>
										<div class="x_content">
											<div id="boletos_nao_pagos_<?=$p['id'];?>"
												style="height: 350px;"></div>
										</div>
									</div>
								</div>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="clearfix"></div>
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
	<script src="public/admin/vendors/echarts/map/js/world.js"></script>
	<script src="public/admin/build/js/custom.min.js"></script>

	<script>
    	var theme = {
			color: ['#3CB371', '#00BFFF', '#4682B4', 'fuchsia', 'gray', 'green', 
				'lime', 'maroon', 'navy', 'olive', 'orange', 'purple', 'red', 
				'silver', 'teal', 'white', 'yellow'],
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

    	<?php foreach ($data['produtos'] as $p) { ?>
            var echartDonut = echarts.init(document.getElementById('echart_donut_<?=$p['id'];?>'), theme);
            
            echartDonut.setOption({
              title: {
                text: '',
                subtext: ''
              },
              tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
              },
              calculable: true,
              legend: {
                x: 'center',
                y: 'bottom'
              },
              toolbox: {
                show: true,
                feature: {
                  magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                      funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'center',
                        max: 1548
                      }
                    }
                  },
                  restore: {
                    show: true,
                    title: "Restore"
                  },
                  saveAsImage: {
                    show: true,
                    title: "Save Image"
                  }
                }
              },
              series: [{
                name: 'Aprovados',
                type: 'pie',
                radius: ['35%', '55%'],
                itemStyle: {
                  normal: {
                    label: {
                      show: true
                    },
                    labelLine: {
                      show: true
                    }
                  },
                  emphasis: {
                    label: {
                      show: true,
                      position: 'center',
                      textStyle: {
                        fontSize: '14',
                        fontWeight: 'normal'
                      }
                    }
                  }
                },
                data: <?=json_encode($data['_pedidos_aprovados_por_estado'][$p['id']]); ?>
              }]
            });

            var echartDonut = echarts.init(document.getElementById('boletos_nao_pagos_<?=$p['id'];?>'), theme);
            
            echartDonut.setOption({
              title: {
                text: '',
                subtext: ''
              },
              tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
              },
              calculable: true,
              legend: {
                x: 'center',
                y: 'bottom'
              },
              toolbox: {
                show: true,
                feature: {
                  magicType: {
                    show: true,
                    type: ['pie', 'funnel'],
                    option: {
                      funnel: {
                        x: '25%',
                        width: '50%',
                        funnelAlign: 'center',
                        max: 1548
                      }
                    }
                  },
                  restore: {
                    show: true,
                    title: "Restore"
                  },
                  saveAsImage: {
                    show: true,
                    title: "Save Image"
                  }
                }
              },
              series: [{
                name: 'Total',
                type: 'pie',
                radius: ['35%', '55%'],
                itemStyle: {
                  normal: {
                    label: {
                      show: true
                    },
                    labelLine: {
                      show: true
                    }
                  },
                  emphasis: {
                    label: {
                      show: true,
                      position: 'center',
                      textStyle: {
                        fontSize: '14',
                        fontWeight: 'normal'
                      }
                    }
                  }
                },
                data: <?=json_encode($data['_boletos_nao_pagos_por_estado'][$p['id']]); ?>
              }]
            });

            var echartBar = echarts.init(document.getElementById('mainb_<?=$p['id'];?>'), theme);

            echartBar.setOption({
              title: {
                text: '',
                subtext: ''
              },
              tooltip: {
                trigger: 'axis'
              },
              legend: {
                data: ['sales', 'purchases']
              },
              toolbox: {
                show: false
              },
              calculable: false,
              xAxis: [{
                type: 'category',
                data: <?=$data['_estados']; ?>
              }],
              yAxis: [{
                type: 'value'
              }],
              series: [{
                name: 'Cartões Aprovados',
                type: 'bar',
                data: <?=json_encode($data['_cartoes_aprovado_por_estado'][$p['id']]); ?>,
              },
              {
                  name: 'Boletos Pagos',
                  type: 'bar',
                  data: <?=json_encode($data['_boletos_aprovado_por_estado'][$p['id']]); ?>,
                },
                {
                    name: 'Boletos Pendentes',
                    type: 'bar',
                    data: <?=json_encode($data['_boletos_nao_pagos_por_estado_bars'][$p['id']]); ?>,
                  }]
            });

            var echartBarMes = echarts.init(document.getElementById('mainb_mes_<?=$p['id'];?>'), theme);

            echartBarMes.setOption({
              title: {
                text: '',
                subtext: ''
              },
              tooltip: {
                trigger: 'axis'
              },
              legend: {
                data: ['sales', 'purchases']
              },
              toolbox: {
                show: false
              },
              calculable: false,
              xAxis: [{
                type: 'category',
                data: <?=json_encode(range(1, date("t", mktime(0, 0, 0, date("m"), '01', date("Y"))))); ?>
              }],
              yAxis: [{
                type: 'value'
              }],
              series: [{
                name: 'Aprovados',
                type: 'bar',
                data: <?=json_encode($data['_pedidos_aprovados_por_dia_mes_atual'][$p['id']]); ?>,
              }]
            });
    	<?php } ?>
            
    </script>

</body>
</html>