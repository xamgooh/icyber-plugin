<html>
<?php
the_post();

$title = get_the_title();
$site_logo = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');

$logo =  get_the_post_thumbnail_url($post->ID, 'medium');

$redirect_link = get_post_meta($post->ID, 'com_comporison_metadata_select_btn_link', true);
$redirect_box_bg = get_post_meta($post->ID, 'com_comporison_metadata_card_redirect_box_bg_color', true);
$redirect_bg_color = get_post_meta($post->ID, 'com_comporison_metadata_card_redirect_bg_color', true);

$redirect_title_label = get_option('comporisons_redirect_title_label');
$redirect_link_title = get_option('comporisons_redirect_link_title');
$redirect_link_message = get_option('comporisons_redirect_link_message');
$redirect_delay = get_option('comporisons_redirect_delay');

?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
    <script>
        setTimeout(function() {
            window.location.href = '<?= $redirect_link ?>'
        }, <?= $redirect_delay * 1000 ?>);
    </script>
    <style>
        body {
            background: <?= $redirect_bg_color ?>;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            text-transform: uppercase;
            color: white;
        }

        .com_redirection_popup .com_logos-client {
            background-color: transparent;
        }

        .com_redirection_popup .com_redirect_wrap {
            background-color: <?= $redirect_box_bg ?>;
        }
    </style>
</head>

<body>
    <div class="com_redirection_popup">
        <div class="com_redirect_wrap">
            <h1><?= get_option('comporisons_redirect_main_title') ?></h1>
            <h2><?= get_option('comporisons_redirect_title_label') ?></h2>
            <div class="com_redirection-wrap">
                <?php if($site_logo && is_array($site_logo)): ?>
                <img class="com_logos" src="<?= $site_logo[0] ?>" alt="<?= get_bloginfo('name') ?>">
                <?php endif; ?>
                <div class="com_redirect-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; display: block; shape-rendering: auto;" width="124px" height="74px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                        <path fill="none" stroke="#222" stroke-width="5" stroke-dasharray="174.48047119140625 82.10845703125" d="M24.3 30C11.4 30 5 43.3 5 50s6.4 20 19.3 20c19.3 0 32.1-40 51.4-40 C88.6 30 95 43.3 95 50s-6.4 20-19.3 20C56.4 70 43.6 30 24.3 30z" stroke-linecap="round" style="transform:scale(0.81);transform-origin:50px 50px">
                            <animate attributeName="stroke-dashoffset" repeatCount="indefinite" dur="1.8518518518518516s" keyTimes="0;1" values="0;256.58892822265625"></animate>
                        </path>
                    </svg>
                </div>
                <img class="com_logos com_logos-client" src="<?= $logo ?>" alt="<?= $title ?>">
            </div>
            <div class="com_instant_redirect">
                <?php if ($redirect_link_message) : ?>
                    <a href="<?= $redirect_link ?>"><?= $redirect_link_title ?></a> <?= get_option('comporisons_redirect_link_message') ?> <?= $title ?></h2>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>

</html>