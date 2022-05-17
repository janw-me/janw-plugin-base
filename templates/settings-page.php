<?php
if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Disable Full site editing Settings', 'janw-plugin-base' ); ?></h1>
	<?php // optional information goes here. ?>
	<form action="<?php echo esc_attr( admin_url( 'options.php' ) ); ?>" method="POST">
		<?php settings_fields( 'janw-plugin-base' ); ?>
		<?php do_settings_sections( 'janw-plugin-base_page' ); ?>
		<?php submit_button( __( 'Save', 'janw-plugin-base' ) ); ?>
	</form>
</div>
