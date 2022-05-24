<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}
$save_notices = get_settings_errors();
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Disable Full site editing Settings', 'janw-plugin-base' ); ?></h1>
	<?php if ( 0 !== count( $save_notices ) ) : ?>
		<?php foreach ( $save_notices as $notice ) : ?>
			<div class="notice notice-<?php echo esc_attr( $notice['type'] ); ?> is-dismissible">
				<p><?php echo wp_kses_post( $notice['message'] ); ?></p>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php // optional information goes here. ?>
	<form action="<?php echo esc_attr( admin_url( 'options.php' ) ); ?>" method="POST">
		<?php settings_fields( 'janw-plugin-base' ); ?>
		<?php do_settings_sections( 'janw-plugin-base_page' ); ?>
		<p class="submit">
			<?php submit_button( __( 'Save', 'janw-plugin-base' ) ); ?>
		</p>
	</form>
</div>
