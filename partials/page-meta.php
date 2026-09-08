<?php
use function \Opehuone\TemplateFunctions\get_favorite_article_button;
?>

<div class="single-post__date-row">
    <span>
        <?php
        // Check if post type is post (show only published date)
        if ( 'post' === get_post_type() ) {
            echo get_the_date();
        }

        // Check if post type is page (show always modified date with prefix text "Päivitetty")
        if ( 'page' === get_post_type() ) {
            echo 'Päivitetty ' . get_the_modified_date();
        }
        ?>
    </span>
    <?php
        // Display the favorite article button
        get_favorite_article_button();
    ?>
</div>