<?php
use function \Opehuone\TemplateFunctions\get_favorite_article_button;
?>

<div class="single-post__date-row">
    <span>
        <?php
        echo get_the_date();
        $published = get_the_date( 'U' ); // Unix timestamp of publish date
        $modified  = get_the_modified_date( 'U' ); // Unix timestamp of modified date

        // Display modified date string if it differs from the published date
        if ( $modified !== $published ) {
            echo '<span class="single-post__date-row-modified-date">Päivitetty ' . get_the_modified_date() . '</span>';
        }

        ?>
    </span>
    <?php
        // Display the favorite article button
        get_favorite_article_button();
    ?>
</div>