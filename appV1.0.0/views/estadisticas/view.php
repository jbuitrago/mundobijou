 <div class="row-one">   
     
   <div class="col-md-5 charts-grids widget" style="width: 100%;">
       <form method="post" action="<?php echo site_url("estadisticas/index")?>">
          <table border="0" cellpadding="5" cellspacing="5" style="">
            <tbody><tr>
                    <td>Fecha inicio:</td>
                    <td>
                        <div id="datetimepicker0" class="input-append date">

                            <input data-format="yyyy-MM-dd" type="text" name="fechaInicio" id="fecha_inicio"  value="<?php echo (isset($_POST['fechaInicio']))? $_POST['fechaInicio']: ''; ?>" class="form-control" style="width: 120px;float:left"></input>
                            <span class="add-on" style="float:right;">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </td>

                    <td>Fecha fin:</td>
                    <td>
                        <div id="datetimepicker1" class="input-append date">

                            <input data-format="yyyy-MM-dd" type="text" name="fechaFin" id="fecha_fin" value="<?php echo (isset($_POST['fechaFin']))? $_POST['fechaFin']: ''; ?>" class="form-control" style="width: 120px;float:left"></input>
                            <span class="add-on" style="float:right;">
                                <i data-time-icon="icon-time" data-date-icon="icon-calendar" class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </td>
                    <td> <button type="submit" id="btnSave" class="btn btn-primary">Filtrar</button></td>
                </tr>
            </tbody>
        </table>
       </form>
       <div class="clearfix"> </div>
    </div>  
     
    <div class="clearfix"> </div>
     
    <div class="charts">
        <div class="col-md-5 charts-grids widget" style="width: 49%;">
            <!--<h4 class="title">Ventas vs Cobros</h4>-->
            <div id="container" height="300" width="400"> </div>
        </div>
        <div class="col-md-5 charts-grids widget states-mdl" style="width: 49%;margin-right: 0">

            <div id="container2" height="300" width="400"> </div>
        </div>
        <div class="clearfix"> </div><br>

        <div class="col-md-5 charts-grids widget" style="width: 49%;">
            <!--<h4 class="title">Ventas vs Cobros</h4>-->
            <div id="container3" height="300" width="400"> </div>
        </div>
        <div class="col-md-5 charts-grids widget states-mdl" style="width: 49%;margin-right: 0">

            <div id="container4" height="300" width="400"> </div>
        </div>

    </div>
 </div>     
<div class="clearfix"> </div>
<!--
<div class="row">
    <div class="col-lg-6 col-sm-6" style="height: 290px;">
        <div class="well"><div id="container" style="min-width: 310px; height: 250px; margin: 0 auto"></div></div>
    </div>

    <div class="col-lg-6 col-sm-6" style="height: 290px;">
        <div class="well"><div id="container2" style="min-width: 310px; height: 250px; margin: 0 auto"></div></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-sm-6"  style="height: 290px;">
        <div class="well"> <div id="container3" style="min-width: 310px; height: 250px; margin: 0 auto"></div></div>
    </div>
    <div class="col-lg-6 col-sm-6"  style="height: 290px;">
        <div class="well" style="height: 100%; "> <div id="container-speed" style="min-width: 310px; height: 200px; "></div>
        </div>
    </div>
</div>    -->

<?php

    /* PARTICIPACION SERVICIOS */
    $total_productos_vendidos = 0;

    $productos_vendidos = array();

    foreach ($productos_mas_venidos as $key => $val) {

        $total_productos_vendidos = $total_productos_vendidos + $val->cant;

        $productos_vendidos[] = array($val->nombre, $val->cant);
    }

    $cant1 = count($productos_vendidos);

    for ($i = 0; $i < $cant1; $i++) {

        $productos_vendidos[$i][1] = ($productos_vendidos[$i][1] * 100) / $total_productos_vendidos;
    }


    /* PARTICIPACION VENDEDORES VENDIDO */
    $vendedores_vendido = array();

    $total_vendido = 0;

    foreach ($participacion_vendedores_vendido as $key => $val) {

        $total_vendido = $total_vendido + $val->total;

        $vendedores_vendido[] = array($val->usuario, $val->total, $val->total);
    }

    $cant2 = count($vendedores_vendido);

    for ($i = 0; $i < $cant2; $i++) {

        $vendedores_vendido[$i][1] = ($vendedores_vendido[$i][1] * 100) / $total_vendido;
    }

    /* GRAFICO PARTICIPACION VENDEDORES COBRADO */
    $vendedores_cobrado = array();

    $total_cobrado = 0;

    foreach ($participacion_vendedores_cobrado as $key => $val) {

        $total_cobrado = $total_cobrado + $val->total;

        $vendedores_cobrado[] = array($val->usuario, $val->total, $val->total);
    }

    $cant3 = count($vendedores_cobrado);

    for ($i = 0; $i < $cant3; $i++) {

        $vendedores_cobrado[$i][1] = ($vendedores_cobrado[$i][1] * 100) / $total_cobrado;
    }
    
    $meses = array();
    
    for($i=1;$i<=12;$i++){
        $meses[$i] = 0; 
    }

    foreach($a_cobrar as $c){
        
        $meses[$c->fecha] = $c->m;
        
    }
    
    ?>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    <script type="text/javascript">

        var save_method; //for save method string
        var table;
        <?php $controller = 'estadisticas'; ?>

        $(function () {
        $('#container3').highcharts({
                chart: {
                type: 'pie',
                    options3d: {
                    enabled: false,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: 'Participacion servicios'
                },
                tooltip: {
                    pointFormat: '{series.name}</span>: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: <b>${point.y:.1f}</b> <br> {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                        name: 'Participacion servicios',
                        data: [
                            <?php
                            for ($i = 0; $i < $cant1; $i++) {
                                echo "['" . $productos_vendidos[$i][0] . "', " . $productos_vendidos[$i][1] . " ],";
                            }
                            ?>
                        ]
                }]
        });
        
     
        $('#container').highcharts({
            chart: {
            type: 'pie',
                options3d: {
                enabled: false,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: 'Participacion vendedores sobre lo vendido'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>${point.y:.1f}</b> <br> <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: <b>${point.y:.1f}</b> <br> {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
            type: 'pie',
                name: 'Valores',
                data: [
                <?php for ($i = 0; $i < $cant2; $i++): ?>
                    {
                    name: '<?= $vendedores_vendido[$i][0] ?>',
                            y: <?= $vendedores_vendido[$i][2]; ?>
                    },
                <?php endfor; ?>
                ]
            }]
        });
        $('#container2').highcharts({
            chart: {
            type: 'pie',
                    options3d: {
                    enabled: false,
                        alpha: 45,
                        beta: 0
                    }
            },
            title: {
                text: 'Participacion vendedores sobre lo cobrado'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>${point.y:.1f}</b> <br> <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: <b>${point.y:.1f}</b> <br> {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
            type: 'pie',
                name: 'Valores',
                data: [
                    <?php for ($i = 0; $i < $cant2; $i++): ?>
                    {
                    name: '<?= $vendedores_cobrado[$i][0] ?>',
                    y: <?= $vendedores_cobrado[$i][2]; ?>
                    },
                    <?php endfor; ?>
                ]
            }]
        });
        
        
        $('#container4').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Cobros'
                },
                xAxis: {
                    categories: [
                        'En',
                        'Feb',
                        'Mar',
                        'Abr',
                        'May',
                        'Jun',
                        'Jul',
                        'Ago',
                        'Sep',
                        'Oct',
                        'Nov',
                        'Dic'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Monto ($)'
                    }
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>${point.y:.1f}</b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'A Cobrar',
                        data: [<?php echo implode(",", $meses); ?>]

                    }]
            });
            
            $('#datetimepicker0').datetimepicker();
        $('#datetimepicker1').datetimepicker();
        
    });

    </script>