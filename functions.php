<?php
/**
 * Dolil functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Dolil
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Custom Rest API
 */

//====== Bazamulla ======

add_action(
	'rest_api_init',
	function () {
		register_rest_route(
			'bazar',
			'test22',
			array(
				'methods'             => 'GET',
				'callback'            => 'lastvalue',
				'permission_callback' => '__return_true'
			)
		);
	}
);

function lastvalue()
  {
      $args = array(
          'post_type' => 'market-value',
          'posts_per_page'   => -1
      );

      $posts = new WP_Query($args);
      $bazarmull  = [];
      if ($posts->have_posts()):
          while ($posts->have_posts()): $posts->the_post();

              $bazarmull[] = array(
                  'ID'    => get_the_ID(),
                  'title' => get_the_title(get_the_ID()),
                  'content' => get_post_field('post_content', get_the_ID()), 
                   'slug'  => get_post_field('post_name', get_the_ID()),
                   'year'  => get_post_meta(get_the_ID(), 'market_value_year_01', true),
                   'file' =>  get_post_meta(get_the_ID(), 'market_value_gdrive_01', true),
                   'year1'  => get_post_meta(get_the_ID(), 'market_value_year_02', true),
                   'file1' =>  get_post_meta(get_the_ID(), 'market_value_gdrive_02', true),
                   'year3' => get_post_meta(get_the_ID(), 'market_value_year_03', true),
                   'file3' =>  get_post_meta(get_the_ID(), 'market_value_gdrive_03', true),
                   'year4' => get_post_meta(get_the_ID(), 'market_value_year_04', true),
                   'file4' =>  get_post_meta(get_the_ID(), 'market_value_gdrive_04', true),
                   'year5' => get_post_meta(get_the_ID(), 'market_value_year_05', true),
                   'file5' =>  get_post_meta(get_the_ID(), 'market_value_gdrive_05', true),
                   'image' => get_the_post_thumbnail_url(get_the_ID(), 'medium')
             );
          endwhile;
      endif;

      $response = new WP_REST_Response($bazarmull);
      $response->set_status(200);
      $response->set_headers(array('Cache-Controls' => 'no-cache'));
      return $response;
  }


//====== Gazzets =========

 
 add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            'gazzet',
            'test19',
            array(
                'methods'             => 'GET',
                'callback'            => 'gazzetpost',
                'permission_callback' => '__return_true'
            )
        );
    }
);

function gazzetpost()
  {
      $args = array(
          'post_type' => 'gazettes',
          'posts_per_page'   => -1
      );

      $posts = new WP_Query($args);
      $gazettepost  = [];
      if ($posts->have_posts()):
          while ($posts->have_posts()): $posts->the_post();
             
            $post_terms = get_the_terms(get_the_ID(),'gazette_cat');

                  $gazettepost[] = array(
                  'ID'    => get_the_ID(),
                  'title' => get_the_title(get_the_ID()),
                  'content' => get_post_field('post_content', get_the_ID()),
                'gezette_link' => get_post_meta(get_the_ID(), 'gezette_link', true), 
                  'gezette_number'  => get_post_meta(get_the_ID(), 'gezette_number', true),
                  'gezette_source' => get_post_meta(get_the_ID(), 'gezette_source', true),
                  'gezette_source_title' =>get_post_meta(get_the_ID(), 'gezette_source_title', true),
                  'gezette_link_iframe' =>get_post_meta(get_the_ID(), 'gezette_link_iframe', true),
                   'category' => $post_terms
                   
              );
          endwhile;
      endif;

      $response = new WP_REST_Response($gazettepost);
      $response->set_status(200);
      $response->set_headers(array('Cache-Controls' => 'no-cache'));

      return $response;
  }
/*============ Manual ===========*/

add_action(
    'rest_api_init',
    function () {
        register_rest_route(
            'manual',
            'test33',
            array(
                'methods'             => 'GET',
                'callback'            => 'lastmanual',
                'permission_callback' => '__return_true'
            )
        );
    }
);

function lastmanual()
  {
      $args = array(
          'post_type' => 'manual',
          'posts_per_page'   => -1,
          'order' => 'ASC',
          );
      

      $posts = new WP_Query($args);
       
      $usermanual  = [];
      if ($posts->have_posts()):
          while ($posts->have_posts()): $posts->the_post();
             
          $post_terms = get_the_terms(get_the_ID(),'manual_cat');

              $usermanual[] = array(
                  'ID'    => get_the_ID(),
                  'title' => get_the_title(get_the_ID()),
                  'content' => get_the_content('post_content', get_the_ID()), 
                  'slug'  => get_post_field('post_name', get_the_ID()),
                  //'Note'  => get_field('_wp_footnotes', get_the_ID()),
                  'category' => $post_terms 
                 
              );
          endwhile;
      endif;

      $response = new WP_REST_Response($usermanual);
      $response->set_status(200);
      $response->set_headers(array('Cache-Controls' => 'no-cache'));

      return $response;
  }





/*============ Forms Search ===========*/ 


function froms_search() {
    register_rest_route('frontend', 'fromsearch', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'form_search_result'

    ));
}
add_action( 'rest_api_init', 'froms_search');


function form_search_result($data) {

$form_args = array(
      'post_type' => 'forms',
      'post_status' => 'publish',
      'posts_per_page' => -1, 
      'order' => 'ASC',
      's'    => sanitize_text_field($data['term'])
    ); 

      $froms = new WP_Query( $form_args );   

      $fromsearch = array();

      while($froms->have_posts()){

           // $froms_terms = get_the_terms(get_the_ID(),'forms_cat');

            $froms->the_post();
            array_push($fromsearch, array(

                'ID'    => get_the_ID(),
                'title' => get_the_title(get_the_ID()),
                'content' => get_the_content('post_content', get_the_ID()), 
                'premalink' => get_the_permalink(get_the_ID()),
                'date'  => get_the_date( 'j F, Y', get_the_ID()),
                'form_pdf_title' => get_post_meta(get_the_ID(), 'forms_gdrive_pdf', true),
                'form_docs_title' => get_post_meta(get_the_ID(), 'forms_gdrive_doc', true),
                'form_docs_title_bijoy' => get_post_meta(get_the_ID(), 'forms_gdrive_doc_bijoy', true),
                //'category' => $post_terms

            )); 
        } 

      $response = new WP_REST_Response($fromsearch);
      $response->set_status(200);
      $response->set_headers(array('Cache-Controls' => 'no-cache'));
      return $response;
      
}


/*========= Manual Tag show =============*/

class menual_get_terms
{
    public function __construct()
    {
        $version = '2';
        $namespace = 'wp/v' . $version;
        $base = 'all-terms';
        register_rest_route($namespace, '/' . $base, array(
            'methods' => 'GET',
            'callback' => array($this, 'get_all_terms'),
        ));
    }

    public function get_all_terms()
    {
        $return = array();
        $manual_args = get_terms( array(
            'taxonomy' => 'manual_tag',
            'orderby' => 'ID', // default: 'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => true, // default: true
        ) );
        foreach ($manual_args as $key => $taxonomy_name) {
            if($taxonomy_name = $_GET['term']){
            $return = get_terms($taxonomy_name);
         }
        }
        return new WP_REST_Response($return, 200);
    }
}

add_action('rest_api_init', function () {
    $all_terms = new menual_get_terms;
});




/*============ Manual Search ===========*/

 
 function manual_search() {
    register_rest_route('frontend', 'menualsearch', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'manual_ajax_search'
        //'args' => manual_get_search_args()
    ));



}
add_action( 'rest_api_init', 'manual_search');


function manual_ajax_search($data) {
   

      $manual_args = array(
      'post_type' => 'manual',
      'post_status' => 'publish',
      'posts_per_page' => -1, 
      'order' => 'ASC',
      'tax_query' => array(
          array(
             'taxonomy' => 'manual_tag',
             'field'    => 'slug',
             'terms'    => sanitize_text_field($data['term'])
          ),
      ),
    ); 

      $menual = new WP_Query($manual_args);   

      $menualsearch = array();

      while($menual->have_posts()){

            $menual->the_post();
            array_push($menualsearch, array(

                'ID'    => get_the_ID(),
                'title' => get_the_title(get_the_ID()),
                'content' => get_the_content('post_content', get_the_ID()), 
                'premalink' => get_the_permalink(get_the_ID())

            )); 
    } 


      $response = new WP_REST_Response($menualsearch);
      $response->set_status(200);
      $response->set_headers(array('Cache-Controls' => 'no-cache'));
      return $response;
      
}




