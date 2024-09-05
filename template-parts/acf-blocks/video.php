<?php
/**
 * Block Name: Video Block
 *
 */


$style  = get_sub_field('style');
//video
$videoType = get_sub_field('video_type');
//video html
$videoFile = get_sub_field('video_file');
$videoPreview = get_sub_field('video_poster');
//video oembed
$videoOembed = get_sub_field('video_link');

//Block options
$options = get_acf_block_options();
?>
<section 
    <?php if($options['id'] != ''): ?> id="<?php echo $id; ?>" <?php endif; ?>
    class="section videoBlock <?php echo $options['class']; ?>" 
    <?php if($options['style'] != ''): ?> style="<?php echo $options['style']; ?>" <?php endif; ?>
>
        <?php if($style == 'full'): ?>
            <?php if($videoType == 'html' && $videoFile): ?>
                <div class="videoBlock__video">
                    <video class="video video--html5" controls="false" <?php if($videoPreview): ?>data-poster="<?php echo $videoPreview['url']; ?>" <?php endif; ?>>
                        <source src="<?php echo $videoFile['url']; ?>" type="video/mp4" />
                    </video>
                </div>
            <?php else: ?>
                <div class="videoBlock__video">
                    <div class="video video--oembed">
                        <?php echo $videoOembed; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="container">
					<?php if($videoType == 'html' && $videoFile): ?>
						<div class="videoBlock__video">
							<video class="video video--html5" controls="false" <?php if($videoPreview): ?>data-poster="<?php echo $videoPreview['url']; ?>" <?php endif; ?>>
								<source src="<?php echo $videoFile['url']; ?>" type="video/mp4" />
							</video>
						</div>
					<?php else: ?>
						<div class="videoBlock__video">
							<div class="video video--oembed">
								<?php echo $videoOembed; ?>
							</div>
						</div>
					<?php endif; ?>
            </div>
        <?php endif; ?>
        
</section>