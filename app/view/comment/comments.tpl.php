<hr>

<h2>Kommentarer</h2>

<?php if (is_array($comments)) : ?>
<div class="comments">
<?php foreach ($comments as $comment) : ?>
<?php $comment = $comment->getProperties(); ?>

<div class="comment">
<div class="commentImgSpace">
<img src="<?php echo "http://www.gravatar.com/avatar/" . md5(strtolower(trim($comment['mail']))) . "?s=50"; ?>"></div>
<div class="commentContSpace">
<h4><?=$comment['name']?></h4>
<p><?=$comment['content']?></p></div>
<div class="commentInfoSpace">
<div class='links'><?=date("Y-m-d, H:i", strtotime($comment['timestamp']))?> |
<a href='mailto:<?=$comment['mail']?>'>mail</a> |
<a href="http://<?=$comment['web']?>" target="_blank">website</a> |
<a href="<?=$this->url->create('comments/remove/'.$comment['id'])?>">Ta bort kommentar</a> |
<a href="<?=$this->url->create('comments/edit/'.$comment['id'])?>">Ändra kommentar</a> | 
 IP: <?=$comment['ip']?>
</div></div>
</div>
<div class='clear'></div>
<hr>
<?php endforeach; ?>
</div>
<?php endif; ?>
