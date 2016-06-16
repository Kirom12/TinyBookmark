<?php if (isset($_SESSION['auth'])): ?>
	<div class="disconnect">
		<a href="<?php echo BASE_URL . "/?a=disconnect"?>" title="Disconnect">X</a>
	</div>
<?php endif ?>
<div class="bookmarks">
	<?php foreach ($bookmarks['category'] as $category): ?>
		<div class="bookmarks-category">
			<div class="bookmarks-category-title">
				<?php echo $category['@attributes']['name'] ?>
			</div>
			<div class="bookmarks-links">
				<?php foreach ($category['link'] as $link): ?>
					<a href="<?php echo $link['url'] ?>" target="_blank">
						<div class="bookmarks-links-element">
							<img src="<?php echo $link['img'] ?>" alt="">
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
			$(this).find('.bookmarks-links-element-name').stop().animate({'height':'toggle','opacity':'toggle'},200);
		},
		function() {
			$(this).find('.bookmarks-links-element-name').stop().animate({'height':'toggle','opacity':'toggle'},200);
		}
	);
</script>