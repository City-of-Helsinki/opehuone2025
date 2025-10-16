<article class="content">
    <div class="content__container hds-container">
        <?php get_template_part( 'partials/breadcrumbs' ); ?>
        <div class="opehuone-grid">
            <section>
                <h1 class="single-post__title"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?>
                    <p><?php echo get_the_excerpt(); ?></p>
                <?php endif; ?>
                <div class="single-post__details-box">
                    <?php
                    get_template_part( 'partials/sidebar/training-cornerlabels' );
                    get_template_part( 'partials/sidebar/training-date' );
                    get_template_part( 'partials/sidebar/training-last-registration-date' );
                    get_template_part( 'partials/sidebar/training-type' );
                    get_template_part( 'partials/sidebar/training-more-info' );
                    get_template_part( 'partials/sidebar/training-registration-link' );
                    ?>
                </div>

                <?php
                if ( has_post_thumbnail() ) : ?>
                    <div class="single-post__featured-image"><?php the_post_thumbnail('full' ); ?></div>
                <?php endif; ?>

                <?php the_content(); ?>

                <?php get_template_part( 'partials/sidebar/training-theme-tags' );  ?>
            </section>
            <aside>
                <?php get_template_part( 'partials/sidebar/training-archive-upcoming-registration-deadline' ); ?>
            </aside>
        </div>
    </div>
</article>
