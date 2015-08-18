<style type="text/css">
.alert-success {
    color: #3c763d;
    background-color: #dff0d8;
    border-color: #d6e9c6;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
.alert-info {
    color: #31708f;
    background-color: #d9edf7;
    border-color: #bce8f1;
}
.alert-danger {
    color: #a94442;
    background-color: #f2dede;
    border-color: #ebccd1;
}
</style>
<div class="wrap">
	<div class="container">
		<div class="alert alert-<?php echo $class; ?>" role="<?php echo $class; ?>">
	      <?php echo $message; ?>
	    </div>
	</div>
</div>