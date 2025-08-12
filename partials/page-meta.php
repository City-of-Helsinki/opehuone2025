<div class="single-post__date-row">
    <span>
        <?php
        echo get_the_date();
        $published = get_the_date( 'U' ); // Unix timestamp of publish date
        $modified  = get_the_modified_date( 'U' ); // Unix timestamp of modified date

        // echo string: "| Päivitetty date", if modifed date differs from publish date
        if ( $modified !== $published ) {
            echo ' | Päivitetty ' . get_the_modified_date();
        }

        ?>
    </span>
</div>