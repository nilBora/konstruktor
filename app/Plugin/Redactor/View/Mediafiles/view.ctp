<h1><?php echo h($mediafile['Mediafile']['name']); ?></h1>

<p><small>Created: <?php echo $mediafile['Mediafile']['created']; ?></small></p>
<p><small>Type: <?php echo $mediafile['Mediafile']['type']; ?><br /> Size: <?php echo $mediafile['Mediafile']['size']; ?></small></p>
<p><small>Original: </small><br /><img src="<?php echo $mediafile['Mediafile']['filelink']; ?>" /></p>
<p><small>Thumbnail: </small><br /><img src="<?php echo $mediafile['Mediafile']['thumblink']; ?>" /></p>