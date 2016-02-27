<?php

use PhangoApp\PhaView\View;
use PhangoApp\PhaRouter\Routes;

function HomeShopView($title, $arr_product, $arr_photo)
{

    
    View::$js[]='jquery.min.js';
    View::$js[]='owl-carousel/owl.carousel.min.js';
    View::$js[]='show_big_image2.js';
    View::$js_module['shopindex'][]='nivoslider/jquery.nivo.slider.pack.js';
    
    //PhangoVar::$arr_cache_css['shopindex'][]='style.css';
    
    View::$css[]='owl.carousel.css';
    View::$css[]='owl.theme.css';
    
    View::$css_module['shopindex'][]='everindex.css';
    View::$css_module['shopindex'][]='nivo-slider.css';
    View::$css_module['shopindex'][]='default.css';

    ob_start();
    
    ?>
    <script language="javascript">
        $(document).ready(function(){
        
            $('#slider').nivoSlider({
                pauseTime: 6000,
                
                afterChange: function() {
                
                    $('#slider .nivo-caption').children('.title_slide').animate({ top: '+=300px'}, 'slow');
                    $('#slider .nivo-caption').children('.cont_slide').fadeIn('slow');
                    //alert($('#slider .nivo-caption').attr('class'));
                }, 
                
                beforeChange: function () {
                    
                    //$('.title_slide').css( 'top', '-300px');
                    $('#slider .nivo-caption').children('.title_slide').animate({ top: '-=300px'}, 'slow');
                    $('#slider .nivo-caption').children('.cont_slide').fadeOut('slow');
                    
                },
                
                afterLoad: function() {
                
                    //alert($('#slide_0').children('.title_slide').attr('class'));
                    $('#slider .nivo-caption').children('.title_slide').animate({ top: '+=300px'}, 'slow');
                    $('#slider .nivo-caption').children('.cont_slide').fadeIn('slow');
                    //alert($('#slider .nivo-caption').attr('class'));
                },
            
            });
            
            //Now the image carousel...
            
            /*$("#owl-demo").owlCarousel({
 
                autoPlay: 3000, //Set AutoPlay to 3 seconds
                
                items : 4,
                itemsDesktop : [1199,3],
                itemsDesktopSmall : [979,3],
                scrollPerPage : true,
                paginationNumbers: true,
            
            });*/
            
            //$('.image_product').ShowBigImage();
        
        });
    </script>
    <?php
    
    View::$header[]=ob_get_contents();
    
    ob_end_clean();

    ?>
    <div id="container_slider">
        <div id="slider" class="nivoSlider theme-default">
            <img src="<?php echo Routes::$root_url; ?>/slider/image1.jpg" title="#slide_0" />
            <img src="<?php echo Routes::$root_url; ?>/slider/image2.jpg" title="#slide_1" />
        </div>
        <div id="slide_0" class="nivo-html-caption">
            <div class="title_slide">
                Camisetas
            </div>
            <div class="cont_slide">
                Camisetas divertidas y frikis para niños
            </div>
        </div>
        <div id="slide_1" class="nivo-html-caption">
                <div class="title_slide">
                    Diversión
                </div>
                <div class="cont_slide">
                    Camisetas divertidas y frikis para niños
                </div>
            </div>
    </div>
    <h1>Últimas novedades</h1>
    <div class="last_news">
    <?php
    
    foreach($arr_product as $product)
    {
    
        //echo $product['date'].'<p>';
        ?>
        <div class="column">
        <?php
        
        echo View::load_view([$product, $arr_photo[$product['IdProduct']]], 'shop/productlist');
    
        ?>
        </div>
        <?php
    
    }
    ?>
    </div>
    <?php

}

?>