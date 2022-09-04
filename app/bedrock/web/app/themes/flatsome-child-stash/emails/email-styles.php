<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include(get_template_directory() . '-child-stash/common/email_styles.php');

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.

?>

#body_content_inner {
	font-size: <?php echo $font_size_base ?>;
	line-height: 140%;	
}

#body_content_inner p {
	margin: 10px 0;
}

#waitlist-wrapper .product-details img {
	max-width: 450px;
    display: block;
    margin: 0 auto 10px auto;
}

<?php
