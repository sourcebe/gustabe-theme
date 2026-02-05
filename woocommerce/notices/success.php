<?php
/**
 * GUSTABE TOAST NOTIFICATION (HTML ONLY)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! $notices ) {
	return;
}
?>

<div class="gustabe-toast-wrapper">
	<?php foreach ( $notices as $notice ) : ?>
		<div class="gustabe-toast-item" role="alert">
			<div class="toast-icon">
				<i class="huge huge-checkmark-circle-02"></i>
			</div>
			<div class="toast-content">
				<?php echo wp_kses_post( $notice['notice'] ); ?>
			</div>
			<button type="button" class="toast-close" onclick="this.parentElement.remove();">&times;</button>
		</div>
	<?php endforeach; ?>
</div>