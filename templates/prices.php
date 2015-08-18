<div class="wrap">
    <h2>Prices <a href="" class="add-new-h2">Add New</a></h2>
    <table class="widefat">
    <thead>
        <tr>
            <th>ID</th>
            <th>Language From</th>       
            <th>Translate To</th>
            <th>Word Price</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>Language From</th>       
            <th>Translate To</th>
            <th>Word Price</th>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach($val as $price): ?>
        <tr>
            <td><?php echo $price->id; ?></td>
            <td style="max-width:100px;" class="editable_language_from" id="<?php echo $price->id; ?>"><?php echo ucfirst($price->from_lang); ?></td>
            <td style="max-width:100px;" class="editable_language_to" id="<?php echo $price->id; ?>"><?php echo ucfirst($price->to_lang); ?></td>
            <td style="max-width:60px;" class="editable_price" id="<?php echo $price->id; ?>"><?php echo $price->word_price; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>