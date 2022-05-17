<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}

/**
 * Variables are set in:
 *
 * @see \Janw\Plugin_Base\App\Settings::register_settings_textarea()
 *
 * @var string $label_for   input ID.
 * @var string $value       Value for the textarea.
 * @var string $name        Name for the textarea.
 * @var string $description Optional extra description.
 */
?>
<textarea
	id="<?php echo esc_attr( $label_for ); ?>"
	rows="<?php echo esc_attr( $rows ?? 8 ); ?>"
	cols="<?php echo esc_attr( $cols ?? 30 ); ?>"
	name="<?php echo esc_attr( $name ); ?>"
><?php echo esc_html( $value ); ?></textarea>

<?php if ( ! empty( $description ) ) : ?>
	<p class="description"><?php echo esc_html( $description ); ?></p>
<?php endif; ?>
