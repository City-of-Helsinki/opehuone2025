<?php

// Get seconds until midnight
function seconds_until_midnight(): int {
    return strtotime('tomorrow midnight') - time();
}

// Check if an e-mail was already notified today
function has_already_been_notified_today( $email ): bool {
    $key = 'notified_' . md5( strtolower( $email ) . '_' . date('Y-m-d') );
    return ( false !== get_transient( $key ) );
}

// Mark email as notified today
function mark_as_notified_today( $email ): void {
    $key = 'notified_' . md5( strtolower( $email ) . '_' . date('Y-m-d') );
    set_transient( $key, true, seconds_until_midnight() );
}

// Filter WP comment notification recipients
add_filter('comment_notification_recipients', function( $emails, $comment_id ) {
    $comment = get_comment( $comment_id );
    if ( ! $comment ) {
        return [];
    }

    $post_id = $comment->comment_post_ID;

    // Get ACF emails
    $email1 = get_field( 'responsible_person_1', $post_id );
    $email2 = get_field( 'responsible_person_2', $post_id );

    $recipients = array_filter( [ $email1, $email2 ] );

    // Only send to emails not yet notified today
    $recipients = array_filter( $recipients, function( $email ) {
        return ! has_already_been_notified_today( $email );
    });

    // Mark these recipients as notified
    foreach ( $recipients as $email ) {
        mark_as_notified_today( $email );
    }

    return $recipients;
}, 10, 2 );