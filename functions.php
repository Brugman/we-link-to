<?php

if ( !defined( 'ABSPATH' ) )
    exit;

function wlt_textdomain()
{
    return 'we-link-to';
}

function wlt_if_long_trim( $link )
{
    $max_length = 140;

    if ( strlen( $link ) > $max_length )
        return substr( $link, 0, $max_length ).'...';

    return $link;
}

function wlt_remove_url_start( $link )
{
    $link = str_replace( 'https://www.', '', $link );
    $link = str_replace( 'http://www.', '', $link );
    $link = str_replace( 'https://', '', $link );
    $link = str_replace( 'http://', '', $link );

    return $link;
}

function wlt_is_external( $link )
{
    $link = wlt_remove_url_start( $link );

    if ( substr( $link, 0, 1 ) == '/' )
        return '';

    $site_url = wlt_remove_url_start( WLT_SITEURL );

    if ( strpos( $link, $site_url ) !== 0 )
        return 'External';

    return '';
}

function wlt_get_link_data()
{
    $results = [];

    global $wpdb;

    $needle = 'href';

    $post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->prefix}posts WHERE ( post_content LIKE '%".$needle."%' OR post_excerpt LIKE '%".$needle."%' ) AND post_status = 'publish' AND post_type NOT IN ( 'revision', 'oembed_cache' )" );

    foreach ( $post_ids as $post_id )
    {
        $post_content = get_post( $post_id )->post_content;

        preg_match_all( '/href=\"([^\"]*)\".*>.*<\/a>/i', $post_content, $matches );

        if ( empty( $matches[1] ) )
            continue;

        if ( !isset( $results[ $post_id ] ) )
            $results[ $post_id ] = [];

        foreach ( $matches[1] as $link )
            if ( !in_array( $link, $results[ $post_id ] ) )
                $results[ $post_id ][] = $link;
    }

    krsort( $results );

    return $results;
}

function wlt_page_run()
{
    $link_data = wlt_get_link_data();
?>
<div class="wrap wlt-wrapper">

    <h1><?php _e( 'We Link To', wlt_textdomain() ); ?></h1>

<?php if ( !empty( $link_data ) ): ?>
    <table class="wp-list-table widefat fixed striped" style="width: auto; margin-top: 16px;">
        <thead>
            <tr>
                <td><?php _e( 'Edit', wlt_textdomain() ); ?></td>
                <td><?php _e( 'View', wlt_textdomain() ); ?></td>
                <td><?php _e( 'Title', wlt_textdomain() ); ?></td>
                <td><?php _e( 'External', wlt_textdomain() ); ?></td>
                <td><?php _e( 'Link', wlt_textdomain() ); ?></td>
            </tr>
        </thead>
        <tbody>
<?php foreach ( $link_data as $post_id => $links ): ?>
<?php foreach ( $links as $k => $link ): ?>
            <tr>
<?php if ( $k == 0 ): ?>
                <td><a href="<?=get_edit_post_link( $post_id );?>"><?php _e( 'Edit', wlt_textdomain() ); ?></a></td>
                <td><a href="<?=get_permalink( $post_id );?>" target="_blank"><?php _e( 'View', wlt_textdomain() ); ?></a></td>
                <td><?=get_the_title( $post_id );?></td>
<?php else: ?>
                <td></td>
                <td></td>
                <td></td>
<?php endif; ?>
                <td><?=wlt_is_external( $link );?></td>
                <td><a href="<?=$link;?>" target="_blank"><?=wlt_if_long_trim( $link );?></a></td>
            </tr>
<?php endforeach; // $links ?>
<?php endforeach; // $link_data ?>
        </tbody>
    </table>
<?php else: // $link_data is empty ?>
    <p><?php _e( 'No links were found.', wlt_textdomain() ); ?></p>
<?php endif; // $link_data ?>

</div><!-- wrap -->
<?php
}

function wlt_controller()
{
    wlt_page_run();
}

