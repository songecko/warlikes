<div class="item-list clearfix">
	<?php foreach ($pager->getCurrentPageResults() as $user): ?>
		<?php echo $view->render('Main/Partial/user_photo.php', array('user' => $user)) ?>
	<?php endforeach; ?>
</div>
<?php if($pager->haveToPaginate()): ?>
<?php echo $pagination; ?>
<?php endif; ?>