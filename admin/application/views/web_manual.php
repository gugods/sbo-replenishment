<style>
	html,body {
		background: #000000;
		padding: 0px;
		margin: 0px;
	}
	.box-image {
		margin-top: 50px;
		clear: both;
	}
	.image {
		max-width: 100%;
		height: auto;
	}
</style>
	<video width="100%" poster="<?php echo base_url('themes/default/assets/videos/video-thumb.jpg'); ?>" controls>
		<source src="<?php echo base_url('themes/default/assets/videos/sboplus-app.mp4'); ?>" type="video/mp4">
		Your browser does not support the video tag.
	</video>
<div class="box-image">
<?php 
for($i=1;$i<18;$i++) {
	$file_name = 'sbo-app-'.sprintf('%02d',$i).'.jpg';
?>
<img src="<?php echo base_url('themes/default/assets/images/'.$file_name); ?>" class="image" />
<?php } ?>
</div>
