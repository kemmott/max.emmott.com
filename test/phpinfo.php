<?php

function show_form() {
	?>
	<form action="<?php echo $_SERVER["PHP_SELF"] ?>" method=post>
	howdy, <input type="text" name="name" value=""> <input type=submit name="stage" value="process">
	</form>
	<?php
}

function process_form() {
	?>
	howdy, <?php echo $_REQUEST["name"] ?>
	<?php
}

if (empty($_REQUEST["stage"])) {
	show_form();
} else {
	process_form();
}

echo "<p>\nbrowser: ". $_SERVER["HTTP_USER_AGENT"] . "</p>\n";

?>
<?phpinfo()?> 