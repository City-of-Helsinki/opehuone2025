<?php
use function Opehuone\TemplateFunctions\display_archive_multi_select_filters;
?>

<h2 class="posts-archive__section-title">
	<?php esc_html_e( 'Hae uutisia suodattamalla', 'helsinki-universal' ); ?>
</h2>
<form class="posts-archive-filtering">
	<div class="posts-archive__filters">
		<?php
		$filters = [
			[
				'name'     => esc_html__( 'Koulutusaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
			[
				'name'     => esc_html__( 'Kategoria', 'helsinki-universal' ),
				'taxonomy' => 'category',
			],
			[
				'name'     => esc_html__( 'Aihe', 'helsinki-universal' ),
				'taxonomy' => 'post_theme',
			],
		];

        display_archive_multi_select_filters( $filters );
        ?>
    <button id="archive-submit-button" type="button" class="posts-archive__filters-submit">
        <?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
    </button>
	</div>
</form>
