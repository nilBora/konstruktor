<h1>Media files</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Type</th>
        <th>Title</th>
        <th>Thumbnail</th>
        <th>Action</th>
        <th>Created</th>
    </tr>

<?php foreach ($mediafiles as $mediafile): ?>
    <tr>
        <td><?php echo $mediafile['Mediafile']['id']; ?></td>
        <td><?php echo $mediafile['Mediafile']['type']; ?></td>
        <td>
            <?php
                echo $this->Html->link(
                    $mediafile['Mediafile']['name'],
                    array('action' => 'view', $mediafile['Mediafile']['id'])
                );
            ?>
        </td>
        <td><img src="<?php echo $mediafile['Mediafile']['thumblink']; ?>" /></td>
        <td>
            <?php
                echo $this->Form->postLink(
                    'Delete',
                    array('action' => 'delete', $mediafile['Mediafile']['id']),
                    array('confirm' => 'Are you sure?')
                );
            ?>
        </td>
        <td>
            <?php echo $mediafile['Mediafile']['created']; ?>
        </td>
    </tr>
<?php endforeach; ?>

</table>