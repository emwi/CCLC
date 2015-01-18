<article class="article1">

<?php
    if (isset($content)) {
        echo $content;
    }
    ?>

    <?php if(isset($byline)) : ?>
        <footer class="byline">
            <?=$byline?>
        </footer>
    <?php endif; ?>
 
</article> 
