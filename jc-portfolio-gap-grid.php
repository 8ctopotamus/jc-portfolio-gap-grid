<?php
/*
  Plugin Name: JC Portfolio Gap Grid
  Plugin URI:  https://icshelpsyou.com
  Description: Portfolio grid with gap.
  Version:     1.0
  Author:      ICS, LLC
  Author URI:  https://icshelpsyou.com
  License:     GPL2
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$pluginSlug = 'jc-portfolio-gap-grid';

function jc_portfolio_gap_grid_scripts_styles() {
  global $pluginSlug;
  wp_register_style($pluginSlug, plugins_url('/css/' . $pluginSlug . '.css',  __FILE__ ));
  wp_register_script($pluginSlug . '-excerpt-formatter', plugins_url('/js/excerpt-formatter.js',  __FILE__ ), array('jquery'), false, true );
}
add_action('wp_enqueue_scripts', 'jc_portfolio_gap_grid_scripts_styles');

function jc_portfolio_grid_shortcode_func($atts) {
  global $pluginSlug;

  // if multiple instances on a page...
  static $first_call = true;

  // ...only load the css once
  if ($first_call) {
    wp_enqueue_style($pluginSlug);
    wp_enqueue_script($pluginSlug . '-excerpt-formatter');
    $first_call = false;
  }

  $portfolioCat = $atts['category'] ? $atts['category'] : null;
  $number = $atts['number'] ? $atts['number'] : 9;

  $query = new WP_QUERY(array(
    'post_type' => 'portfolio',
    'posts_per_page' => $number,
    'tax_query' => array(
      array(
        'taxonomy' => 'portfolio_cat',
        'field' => 'slug',
        'terms' => $portfolioCat
      )
    )
  ));

  if ( $query->have_posts() ):
    $html = '<div class="jc-portfolio-gap-grid">';
    while( $query->have_posts() ):
      $query->the_post();
      $html .= '<div class="jc-portfolio-grid-item">
        <figure>
          <img src="' . get_the_post_thumbnail_url() . '" alt="' . get_the_title() . '" />
        </figure>
        <div class="jc-portfolio-grid-item-description">
          <a href="' . get_the_permalink() . '">
            <h3 class="jc-portfolio-grid-item-title">'. get_the_title() .'</h3>
          </a>
          <div class="jc-portfolio-grid-item-excerpt">' . get_the_excerpt() . '</div>
          <a href="' . get_the_permalink() . '" class="jc-portfolio-grid-item-button">View Home</a>
        </div>
      </div>';
    endwhile;
    $html .= '</div>';
  else:
    $html .= 'No projects found.';
  endif;

  return $html;
}
add_shortcode('jc-portfolio-grid', 'jc_portfolio_grid_shortcode_func');



?>
