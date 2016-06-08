<div class="bookmarks">
	<?php foreach ($bookmarks['category'] as $category): ?>
		<div class="bookmarks-category">
			<?php echo $category['@attributes']['name'] ?>
		</div>

		<div class="bookmarks-links">
			<?php foreach ($category['link'] as $link): ?>
				<a href="<?php echo $link['url'] ?>" target="_blank">
					<div class="bookmarks-links-element" style="background: #FFF url(<?php echo $link['img'] ?>); background-size: 100px;">
						<div class="bookmarks-links-element-name">
							<?php echo $link['name'] ?>
						</div>
					</div></a>
			<?php endforeach ?>
		</div>
	<?php endforeach ?>
</div>

<script>
	$('.bookmarks-links-element').hover(
		function() {
			$(this).children().stop().animate({'height':'toggle','opacity':'toggle'},200);
		},
		function() {
			$(this).children().stop().animate({'height':'toggle','opacity':'toggle'},200);
		}
	);
</script>