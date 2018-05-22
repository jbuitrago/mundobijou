</div>
<!-- /#content --> 
<!-- *** FOOTER ***
_________________________________________________________ -->

<div id="footer">
    <div class="container">
        <div class="col-md-3 col-sm-12">
            <h4>Seguinos</h4>
            <p class="social"> <a href="https://www.facebook.com/JoyasAceroMundoBijou/" target="_blank"><i class="fa fa-facebook-square"></i></a></p>
            <p class="social"> <a href="https://www.instagram.com/mundobijouargentina/" target="_blank"><i class="fa fa-instagram"></i></a></p>
        </div>
        <!-- /.col-md-3 -->
        <div class="col-md-6 col-sm-12 text-center">
            <h4>Nuestros Productos</h4>
            <div class="col-sm-6">
                <ul>

                    <?php
                    $cat = 0;
                    foreach ($categorias as $c) {
                        if ($cat == 6) {
                            $cat = 0;
                            ?>
                        </ul>
                    </div>
                    <div class="col-sm-6">
                        <ul>
                            <li><a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>"><?php echo $c->nombre; ?></a> </li>
                        <?php } else {
                            $cat++;
                            ?>

                            <li><a href="<?= site_url(str_replace(' ', '-', $c->nombre) . '-' . $c->id); ?>"><?php echo $c->nombre; ?></a> </li>
                        <?php }
                    }
                    ?>
                    <?php if($mostrar_categoria_combos>0):?><li><a href="<?= base_url(); ?>combos">Combos</a> </li> <?php endif;?>
                </ul>
            </div>
            <!-- /.col-md-3 --

            <div class="col-sm-6 ">
                <ul>
                    <li><a href="{#link/productos}">Estuches</a> </li>
                    <li><a href="{#link/productos}">Pulseras</a> </li>
                    <li><a href="{#link/productos}">Rosarios</a> </li>
                    <li><a href="{#link/productos}">Ofertas</a> </li>
                    <li><a href="{#link/productos}">Accesorios</a> </li>
                </ul>
                <hr class="hidden-md hidden-lg hidden-sm">
            </div>
            <!-- /.col-md-3 --> 

        </div>
        <div class="col-md-3 col-sm-12"> <img src="<?= base_url(); ?>assets/web/img/logo_footer.png" class="img-responsive" alt="Mundo Bijou - Joyas en Acero Quirúrgico"/> </div>
        <!-- /.col-md-3 --> 
    </div>
    <!-- /.container --> 
</div>
<!-- /#footer --> 

<!-- *** FOOTER END *** --> 

<!-- *** COPYRIGHT ***
_________________________________________________________ -->

<div id="copyright">
    <div class="container">
        <div class="col-md-12">
            <p class="pull-left">&copy; 2017 Mundo Bijou - Todos los derechos reservados.</p>
            <p class="pull-right"><a href="http://qr.afip.gob.ar/?qr=CyjvXtssoALl7sZUfFTxaw,," target="_F960AFIPInfo"><img src="http://www.afip.gob.ar/images/f960/DATAWEB.jpg" width="36" height="49" border="0"></a></p>
        </div>
    </div>
</div>
<!-- /#copyright --> 

<!-- *** COPYRIGHT END *** -->

</div>
<!-- /#all -->