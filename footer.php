<div class="my-5 text-center">
	<?php
	$arr = array();
	$arr['twitter'] = get_theme_mod('twitter');
	$arr['facebook'] = get_theme_mod('facebook');
	$arr['linkedin'] = get_theme_mod('linkedin');
	$arr['instagram'] = get_theme_mod('instagram');
	foreach ($arr as $value) {
		$Domain = parse_url($value);
		$twitterWww = str_ireplace('www.', '', $Domain);
		$twitter = str_ireplace('.com', '', $twitterWww['host']); ?>
		<a href="<?php echo $value; ?>"><i class=" h2 bi bi-<?= $twitter; ?>"></i></a>
	<?php
	}
	?>
</div>