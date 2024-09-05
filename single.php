<?php /*DEAFULT PAGE TEMPLATE*/ ?>

<?php get_header(); ?>

            <div class="page-blocks">
              <?php 
                if ( ! post_password_required() ) :
                    // Your custom code should here
                    get_template_part('template-parts/page/content','page');
                    the_acf_loop();
                else :
                  ?>
                  <div class="section section--fullscreen">
                    <div class="container">
                      <?php echo get_the_password_form(); ?>
                    </div>
                  </div>
                  <?php
                endif;
              ?>
            </div>
   
<?php get_footer(); ?>