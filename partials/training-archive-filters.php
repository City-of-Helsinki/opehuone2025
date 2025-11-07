<?php
use function Opehuone\TemplateFunctions\display_archive_multi_select_filters;
?>

<h2 class="training-archive__section-title">
	<?php esc_html_e( 'Hae koulutuksia', 'helsinki-universal' ); ?>
</h2>
<form class="training-archive-filtering">
	<div class="training-archive__filters">
		<?php
		$filters = [
			[
				'name'     => esc_html__( 'Koulutusaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
			[
				'name'     => esc_html__( 'Koulutusteema', 'helsinki-universal' ),
				'taxonomy' => 'training_theme',
			],
		];

        display_archive_multi_select_filters( $filters );
		?>
		<button id="archive-submit-button" type="button" class="training-archive__filters-submit">
			<?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
		</button>
	</div>
</form>
