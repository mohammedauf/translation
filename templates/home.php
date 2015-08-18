<div class="wrap">
    <h2>Languages<a href="<?php echo admin_url('admin.php?page=word-count-add-language'); ?>" class="add-new-h2">Add New</a></h2>
    <table class="widefat">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>       
            <th>Flag</th>
            <th>View</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Flag</th>
        <th>View</th>
        </tr>
    </tfoot>
    <tbody>
        <?php foreach($val as $language): ?>
        <tr>
            <td><?php echo $language->id; ?></td>
            <td><a href="<?php echo admin_url('admin.php?page=word-count-prices&langid=' . $language->id); ?>"><?php echo $language->name; ?></a></td>
            <td><img src="<?php echo plugins_url( '../flags/' . $language->flag . '.png', __FILE__ ); ?>" /></td>
            <td>
                  <a href="<?php echo admin_url('admin.php?page=word-count-prices&target=from&langid=' . $language->id); ?>">View Prices From</a>
                | <a href="<?php echo admin_url('admin.php?page=word-count-prices&target=to&langid=' . $language->id); ?>">View Prices To</a>
                | <a href="<?php echo admin_url('admin.php?page=word-count-prices&delete=true&langid=' . $language->id); ?>"  onclick="if (!confirm('Are you sure to delete?')) return false;">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
</div>