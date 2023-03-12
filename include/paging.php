<?php
	$cur_url = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	if ($cur_page > 1 && $cur_page <= $num_page) {
		$new_url_first = CommonFunc::rebuildUrl($cur_url, 'page', 1);
		$new_url_prev = CommonFunc::rebuildUrl($cur_url, 'page', $cur_page-1);
	}
	if ($cur_page < $num_page && $cur_page >= 1) {
		$new_url_next = CommonFunc::rebuildUrl($cur_url, 'page', $cur_page+1);
		$new_url_last = CommonFunc::rebuildUrl($cur_url, 'page', $num_page);
	}
?>

			<?php if ($cur_page > 1 && $cur_page <= $num_page) { ?>
				<div id="pg_first"><a href="<?php echo $new_url_first; ?>"><<</a></div>
				<div id="pg_prev"><a href="<?php echo $new_url_prev; ?>"><</a></div>
				<div id="pg_prev"><a href="<?php echo $new_url_prev; ?>"><?php echo $cur_page-1; ?></a></div>
			<?php } ?>
			<?php if ($cur_page >= 1 && $cur_page <= $num_page) { ?>	
				<div id="pg_cur"><?php echo $cur_page; ?></div>
			<?php } ?>
			<?php if ($cur_page < $num_page && $cur_page >= 1) { ?>
				<div id="pg_next"><a href="<?php echo $new_url_next; ?>"><?php echo $cur_page+1; ?></a></div>
				<div id="pg_next"><a href="<?php echo $new_url_next; ?>">></a></div>
				<div id="pg_last"><a href="<?php echo $new_url_last; ?>">>></a></div>
			<?php } ?>

<?php

?>