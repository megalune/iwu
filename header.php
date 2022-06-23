<?php

$current_user = wp_get_current_user();

if ( 0 == $current_user->ID ) {
    // Not logged in.
	header('Location: /wp-login.php');
	exit;
} else {
    // Logged in.
    $user = $current_user->user_login;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> data-template="<?php global $template; echo basename($template); ?>">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<?php wp_head(); ?>
<link rel="icon" type="image/svg+xml" href="/favicon.svg">
<link rel="icon" type="image/png" href="/favicon.png">
</head>
<body <?php body_class(); ?> data-user="<?php echo $current_user->user_login; ?>">
<?php wp_body_open(); ?>