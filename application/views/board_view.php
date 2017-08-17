
<?
	$GLOBALS['data'] = $data;

	function draw($parentId, $level) {
		foreach($GLOBALS['data'] as $msg) { 
			if ($msg['parent_id_message'] == $parentId): ?>
				<div class='comment' data-level='<?=$level;?>' data-id='<?=$msg[id_message];?>'>
					<div class="message">
						<div class="profile">
							<a href='<?=$msg[link];?>'><img class='avatar' src='<?=$msg[picture];?>'>
							<div class="name"><?=$msg['given_name'];?> <?=$msg['family_name'];?></div></a>
							<div class="formatTime time"><?=$msg['time'];?></div>
						</div>
						<div class="body">
							<div class="message_text"><?=$msg['message'];?></div>
							<?
							if(Model::isLogined()) {?>
								<div class="reply">Ответить</div>
							<?}
							?>
						</div>
					</div>

				<? draw($msg['id_message'], $level + 1); ?>
				</div>
			<? endif; 
		}
	}

	draw(null, 1);
	if(Model::isLogined()) {
		include '/block/block_addrecord.php';
	}
	else {
		include '/block/block_pleaselogin.php';
	}
?>
