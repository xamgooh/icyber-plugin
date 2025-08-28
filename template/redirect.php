<?php
/**
 * Comparison Plugin - Redirect Fallback Template
 * This is a simplified fallback for users without JavaScript
 */

// Ensure we have a post
if (!isset($post) || !$post) {
    wp_redirect(home_url());
    exit;
}

// Get the redirect URL from post meta
$redirect_link = get_post_meta($post->ID, 'com_comporison_metadata_select_btn_link', true);

// If no redirect link found, go to homepage
if (empty($redirect_link)) {
    wp_redirect(home_url());
    exit;
}

// Perform the redirect
wp_redirect($redirect_link, 302);
exit;

// Nothing should execute after the redirect
?>
