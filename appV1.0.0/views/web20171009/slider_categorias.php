<div id="slider" class="owl-carousel owl-theme">

    <?php
    foreach ($categorias as $c):
        if ($c->destacada == "si"):
            ?> 
            <div class="item"> <a href="<?= base_url(); ?>productos/<?php echo $c->id; ?>"> <img src="<?= base_url(); ?>uploads/<?php echo $c->foto; ?>" alt="" class="img-responsive"> </a> </div>
                <?php endif;
            endforeach;
            ?>
    <!--
 <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/accesorios.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/anillos.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/aros.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/cadenas.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/conjuntos.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/dijes.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/estuches.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/ofertas.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/pulseras.jpg" alt="" class="img-responsive"> </a> </div>
    <div class="item"> <a href="#"> <img src="<?= base_url(); ?>assets/web/img/categorias/rosarios.jpg" alt="" class="img-responsive"> </a> </div>
    -->
</div>
