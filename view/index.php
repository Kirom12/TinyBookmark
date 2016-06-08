<div class="bookmarks">
	<?php if (isset($bookmarks['category'][0])): ?>
		<?php $bks = $bookmarks['category'] ?>
	<?php else: ?>
		<?php $bks = $bookmarks ?>
	<?php endif ?>
	<?php foreach ($bks as $category): ?>
		<div class="bookmarks-category">
			<div class="bookmarks-category-title">
				<?php echo $category['@attributes']['name'] ?>
			</div>

			<div class="bookmarks-links">
				<?php foreach ($category['link'] as $link): ?>
					<a href="<?php echo $link['url'] ?>" target="_blank">
						<div class="bookmarks-links-element" style="background: url(<?php echo $link['img'] ?>); background-size: 100px;">
							<div class="bookmarks-links-element-name">
								<?php echo $link['name'] ?>
							</div>
						</div></a>
				<?php endforeach ?>
			</div>
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