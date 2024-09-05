</div>

<?php get_template_part( 'template-parts/footer/footer-booking' ); ?>

<?php get_template_part( 'template-parts/api/popups/popups' ); ?>

<?php wp_footer(); ?>
<?php 
$scripts = get_field('footer_scripts', 'option');
if($scripts) echo $scripts; 
?>
</body>
</html>
