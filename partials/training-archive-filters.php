<?php
use function Opehuone\TemplateFunctions\display_archive_multi_select_filters;
use function Opehuone\TemplateFunctions\display_archive_multi_select_checkbox_filters;
use function \Opehuone\TemplateFunctions\get_user_cornerlabels_with_added_default_value;

$user_cornerlabels   = get_user_cornerlabels_with_added_default_value();
?>

<h2 class="training-archive__section-title">
	<?php esc_html_e( 'Hae koulutuksia', 'helsinki-universal' ); ?>
</h2>
<form class="training-archive-filtering">
	<div class="training-archive__filters">
		<?php
		$cornerlabels = [
			[
				'name'     => esc_html__( 'Koulutusaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
		];
		$training_themes = [
			[
				'name'     => esc_html__( 'Koulutusteema', 'helsinki-universal' ),
				'taxonomy' => 'training_theme',
			],
		];
		display_archive_multi_select_checkbox_filters( $cornerlabels, $user_cornerlabels );
		display_archive_multi_select_filters( $training_themes );
		?>
		<button id="archive-submit-button" type="button" class="training-archive__filters-submit">
			<?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
		</button>
	</div>
</form>
