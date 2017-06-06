<?php 
	require_once('admin.class.php');

	$admin = admin::getById(1);

	print_r($admin);

	echo "************************************************* \n";

	$allAdmin = admin::getAll();

	print_r($allAdmin);



?>
