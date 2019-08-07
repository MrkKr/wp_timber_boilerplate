<?php
/**
 * Timber Site Core functions
 */

class MySiteCore extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'themeSupports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'registerPostTypes' ) );
		add_action( 'init', array( $this, 'registerTaxonomies' ) );
		add_action( 'init', array( $this, 'loadAssets' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function registerPostTypes() {

	}
	/** This is where you can register custom taxonomies. */
	public function registerTaxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['menu'] = new Timber\Menu();
		$context['site'] = $this;
		
		return $context;
	}

	public function themeSupports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/**
   * Enqueue scripts
   */
  public function loadAssets()
  {
    // Register the filter
    add_filter('wp_enqueue_scripts', function () {
      // styles
      wp_register_style('main-css', get_template_directory_uri() . '/dist/css/style.min.css', false, null);
      wp_enqueue_style('main-css');

      // scripts
      wp_register_script('main-scripts', get_template_directory_uri() . '/dist/js/script.min.js', array('jquery'), null, true);
      wp_enqueue_script('main-scripts');

      //ajax
      wp_localize_script( 'main-scripts', 'ajax', array(
        'url' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce('ajaxnonce')
      ) );

      // path to theme
      $theme_path = array( 'templateUrl' => get_template_directory_uri() );
      //after wp_enqueue_script
      wp_localize_script( 'main-scripts', 'theme_path', $theme_path );
    }, 10);
  }

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}

}

new MySiteCore();
