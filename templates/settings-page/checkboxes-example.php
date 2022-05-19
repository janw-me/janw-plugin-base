<?php
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( ! defined( 'ABSPATH' ) ) {
	return; // WP not loaded.
}

/**
 * Variables are set in:
 *
 * @see \Janw\Plugin_Base\App\Settings::register_setting_checkboxes()
 *
 * @var string     $title       Title for all these boxes.
 * @var string     $option_name The overall option name.
 * @var string     $description Optional extra description for the whole group.
 * @var bool[]     $values      The current values of the checkbox.
 * @var string[][] $inputs      The instructions for the checkboxes.
 */
?>
<fieldset>
	<legend class="screen-reader-text"><?php echo esc_html( $title ); ?></legend>
	<?php foreach ( $inputs as $key => $input ) : ?>
		<?php $html_id = sanitize_title( "id-{$option_name}-{$key}" ); ?>
		<input
			type="checkbox"
			value="<?php echo esc_attr( $input['value'] ?? 'on' ); ?>"
			id="<?php echo esc_attr( $html_id ); ?>"
			name="<?php echo esc_attr( "{$option_name}[{$key}]" ); ?>"
			<?php checked( $values[ $key ] ?? false ); ?>
		/>
		<label for="<?php echo esc_html( $html_id ); ?>"><?php echo esc_html( $input['label'] ); ?></label><br/>
	<?php endforeach; ?>

	<?php if ( ! empty( $description ) ) : ?>
		<p class="description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>
</fieldset>
