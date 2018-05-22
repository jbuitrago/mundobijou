<section id="sliderHome">
    <div id="carousel-slider" class="carousel carousel-slider-wrapper slide" data-ride="carousel">
        <!-- Carousel indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-slider" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-slider" data-slide-to="1"></li>
            <li data-target="#carousel-slider" data-slide-to="2"></li>
            <li data-target="#carousel-slider" data-slide-to="3"></li>
            <li data-target="#carousel-slider" data-slide-to="4"></li>
        </ol>
        <!-- Carousel nav -->
        <a class="carousel-control left" href="#carousel-slider" data-slide="prev"> <i class="fa fa-angle-left"></i> </a> <a class="carousel-control right" href="#carousel-slider" data-slide="next"> <i class="fa fa-angle-right"></i> </a>
        <!-- Carousel items -->
        <div class="carousel-inner">
        <?php   $cant=1;
                foreach($banner as $b)
                {
                     $component_link = parse_url($b->link);
                    if($cant==1){
                    ?>
                        <div class="item  active" id="carousel-slide-<?php echo $cant;?>" style="background-image: url(<?= base_url(); ?>uploads/<?php echo $b->file;?>)"><a href="<?php echo (isset($component_link['scheme'])) ? $b->link : 'http://' . $b->link; ?>"  target="_<?php echo $b->link_accion;?>" >
                            <div class="carousel-overlay">
                                <div class="carousel-item-content">
                                    <div class="container">
                                        <div class="animated fadeInDown delay-1">
                                            <h1 class="raleway blanco"style="padding: 15px;background-color: black;"><?php echo $b->titulo;?></h1>
                                        </div>
                                        <div class="animated fadeInUp delay-2">
                                            <p><?php echo $b->descripcion;?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
             <?php $cant++; } else { ?>
                        <div class="item" id="carousel-slide-<?php echo $cant;?>" style="background-image: url(<?= base_url(); ?>uploads/<?php echo $b->file;?>)"><a href="<?php echo (isset($component_link['scheme'])) ? $b->link : 'http://' . $b->link; ?>"  target="_<?php echo $b->link_accion;?>" >
                           <div class="carousel-overlay">
                               <div class="carousel-item-content">
                                   <div class="container">
                                       <div class="animated fadeInDown delay-1">
                                           <h1 class="raleway blanco"style="padding: 15px;background-color: black;"><?php echo $b->titulo;?></h1>
                                       </div>
                                       <div class="animated fadeInUp delay-2">
                                           <p><?php echo $b->descripcion;?></p>
                                       </div>
                                   </div>
                               </div>
                           </div>
                           </a> 
                       </div>
                     <?php $cant++;}} ?>
        </div>
        <!--carousel inner-->

    </div>
</section>
