<?php
/*
Plugin Name: Mingus Jmpress
Plugin URI: http://www.mingus.co/wp-plugins/mingusjmpress/
Description: Customizable jmpress plugin for wordpress
Version: 0.1
Author: Cem Yıldız
Author URI: http://mingus.co
License: GPLv2 or later
*/
DEFINE ('PLUGIN_DIR', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) . '/' );
DEFINE ('PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ )  . '/' );
/**
* 
*/
include PLUGIN_DIR_PATH.'html-helper.php';
include PLUGIN_DIR_PATH.'mingusjmpress-tools.php';
include PLUGIN_DIR_PATH.'mingusjmpress-admin.php';

class MingusJmpress{
	protected static $_instance = null;
	private $html;
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function __construct() {
		add_action('init', array($this,'init') );
		add_action('init', array($this,'register_session'));


		//add_action('the_content', array($this,'content_before_action'));
		//add_action('the_content', array($this,'content_after_action'));
		wp_register_style( 'userStylesheet', plugins_url('style.css', __FILE__) );

		wp_register_script( 'userScripts', plugins_url('jmpress.all.min.js', __FILE__) );
		//wp_register_script( 'userScripts_inputmask', plugins_url('js/jquery.inputmask.bundle.js', __FILE__) );
		
		add_action('get_header', array($this,'beforeHeaders'));

	}
	function init() {
		new MingusJmpress_Admin();
		
		add_shortcode( 'mingusjmpress', array($this,'mingusjmpress_shortcode') );
	}
	function beforeHeaders(){
		switch (get_post_type()) {			
			case 'temp':
				wp_redirect( get_permalink($_POST['mingusform-post']) ); exit;
				break;
		}
	}
	function mingusjmpress_shortcode($params){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'userScripts' );
		wp_enqueue_style( 'userStylesheet' );
		$html = new MingusHtml();
		$post = get_post();

		$parentMeta = get_post_meta($post->ID);
		$layoutStyle = 'background-color:'.$parentMeta['jmpress-background-color'][0].';';
		$layoutStyle .= 'color:'.$parentMeta['jmpress-font-color'][0].';';
		$layoutStyle .= $parentMeta['jmpress-style'][0];
		echo $html->h(2,'asdfasdf'.$post->ID);
		$args = array(
			'posts_per_page'   => -1,
			'offset'           => 0,
			'orderby'          => 'menu_order',
			'order'            => 'ASC',
			'post_type'		   =>get_post_type(),
			'post_parent'      => $post->ID,
			'post_status'      => 'publish');
		$slides = get_posts($args);
		$result = '';
		$i = 0;


		$jmKeys = MingusJmpress_Tools::jmKeys();
		$jmStyleKeys = MingusJmpress_Tools::jmStyleKeys();

		foreach ($slides as $slide) {
			$meta = get_post_meta($slide->ID);
			$jmdata = array();
			foreach ($jmKeys as $key) {
				if($meta['jmpress-'.$key][0]!=''){
					$jmdata[$key] = $meta['jmpress-'.$key][0];
				}
			}
			$jmdata['class'] = 'step '.$meta['jmpress-class'][0];
			$jmdata['style'] = '';

			foreach ($jmStyleKeys as $key) {
				if($meta['jmpress-'.$key][0]!=''){
					$jmdata['style'] .= $key.':'.$meta['jmpress-'.$key][0].';';
				}
			}
			$result .= $html->div($slide->post_content,$jmdata);
			$i++;
		}
		//$result .= $html->div('',array('class'=>'step','data-x'=>'1200','data-y'=>'-1200','data-scale'=>'10'));
		echo $html->div($html->div($result,array('id'=>'jmpress-container')),array('id'=>'jmpress-layout','style'=>$layoutStyle));
		echo $html->gen('script',"
			var setPercentageData = function(el){
				$ = jQuery;
				var arr = ['x','y','z'];
				for(i in arr){
					var x = arr[i];
					try{
						if(el.attr('data-'+x).indexOf('%')>0){
							var w = $('#jmpress-container').width();
							var r = parseFloat(el.attr('data-'+x))/100;
							var t = Math.floor(w*r);
							el.attr('data-'+x,t);
						}
					}catch(err) {
					}
				}
			};
			var setStepSizes = function(){
				var w = Math.floor(jQuery( window ).width()*0.8);
				var h = Math.floor(jQuery( window ).height()*0.8);
				jQuery('#jmpress-container .step').css('max-width',w+'px');
				jQuery('#jmpress-container .step').css('max-height',h+'px');

				jQuery('#jmpress-container .boxed').css('width',w+'px');
				jQuery('#jmpress-container .boxed').css('height',h+'px');
			};
			jQuery(document).ready(function(){
				$ = jQuery;
				//jQuery(function() {
					//jQuery('#jmpress-container').jmpress();
				//});
				
				$('#jmpress-layout').appendTo('body');
				$('div',$('#jmpress-container')).each(function(){
					setPercentageData($(this));
				});
				jQuery('#jmpress-container').jmpress();
				jQuery( window ).resize(function() {
					setStepSizes();
				});
				setStepSizes();
			});
			");

	}
	
	function register_session(){
	    if( !session_id() )
	        session_start();
	}
}
function  MingusJmpress_() {
	return MingusJmpress::instance();
}
// Global for backwards compatibility.
$GLOBALS['MingusJmpress'] = MingusJmpress_();