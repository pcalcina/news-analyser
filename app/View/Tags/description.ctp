<table>
    <thead>
        <tr>
            <th>Tag</th>
            <th>Descrição</th>
        </tr>
    </thead>
    
    <tbody>
        <?php foreach ($tags as $tag): ?>
            <tr style='padding:10px; padding-top:10px; margin-top:10px; margin:10px'>
                <td id="tag-<?php echo h($tag['Tag']['tag_id']); ?>" style="margin:1px; padding:1px; color:#191970; width:15%;" ><b><?php echo h($tag['Tag']['name']); ?></b></td>
                <td> <?php echo h($tag['Tag']['description']); ?> </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
