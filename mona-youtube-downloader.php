<?php
/*
*
* Plugin Name: Mona Youtube Downloader
* Plugin URI: http://mona-media.com/
* Description: Mona Youtube Downloader.
* Author: Mona Media
* Author URI: http://mona-media.com/
* Version: 1.3
* Text Domain: mona-youtube-downloader
*
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'Mona_Youtube_Downloader' ) ){
	class Mona_Youtube_Downloader {		
		/**
		* Class Construct
		*/
		public function __construct() {		
			$this->youtube_image = 'https://i3.ytimg.com/vi/';
			$this->youtube_api = 'https://youtube.com/get_video_info';
			$this->youtube_sample = 'https://www.youtube.com/watch?v=Xb9FXJy4SYU';
			
			//file
			if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ).'mona-youtube-api.php' ) )
				require_once( trailingslashit( plugin_dir_path( __FILE__ ) ).'mona-youtube-api.php' );
			
			if ( file_exists( trailingslashit( plugin_dir_path( __FILE__ ) ).'mona-youtube-downloader-widget.php' ) )
				require_once( trailingslashit( plugin_dir_path( __FILE__ ) ).'mona-youtube-downloader-widget.php' );	
			
			//widget
			add_action( 'widgets_init', array( $this, 'mona_create_widget' ) );	
			//shortcode
			add_shortcode( 'mona_youtube_downloader', array( $this, 'mona_shortcode_downloader' ) );	
			// ajax action			
			add_action( 'wp_ajax_mona_youtube_downloader', array( $this, 'mona_ajax_youtube_downloader' ) );	
			add_action( 'wp_ajax_nopriv_mona_youtube_downloader', array( $this, 'mona_ajax_youtube_downloader' ) );
		}	
		
		/**
		* Widget
		*/
		function mona_create_widget(){
			if ( class_exists( 'Mona_Youtube_Downloader_Widget' ) ){	
				register_widget('Mona_Youtube_Downloader_Widget');
			}
		}		
		
		/**
		* Shortcode
		*/
		function mona_shortcode_downloader( $atts = array() ){
			ob_start();
			
			extract( 
				shortcode_atts( 
					array(
						'text' => 'Get',
					), 
					$atts, 
					'mona_youtube_downloader'
				)			
			);
			
			$this->mona_render_downloader( $text );
			
			return ob_get_clean();
		}	
		
		/**
		* Functions
		*/
		function mona_get_youtube_video_type(){
			return array( 'video/webm', 'video/mp4' );
		}
		function mona_get_youtube_audio_type(){
			return array( 'audio/webm', 'audio/mp4' );
		}
		function mona_get_round_number( $number ){
			$number = $number * 10;
			
			if( (int) ( $number / 1000000000 ) > 0 ){
				$number = (int) ( $number / 1000000000 );
				$number = '~'.(double) ( $number / 10 ).'B';
				
			}else
			if( (int) ( $number / 1000000 ) > 0 ){
				$number = (int) ( $number / 1000000 );
				$number = '~'.(double) ( $number / 10 ).'M';
			}else
			if( (int) ( $number / 1000 ) > 0 ){
				$number = (int) ( $number / 1000 );
				$number = '~'.(double) ( $number / 10 ).'K';
			}
			
			return $number;
		}
		function mona_get_youtube_id( $link ){
			preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+(?=\?)|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $link, $id);
			if(!empty($id)) {
				return $id = $id[0];
			}
			
			return $link;
		}
		function mona_get_youtube_thumbnail( $data ){
			return isset( $data['iurl'] ) ? $data['iurl'] : $this->mona_get_youtube_thumbnail_default($data['video_id']);
		}
		function mona_get_youtube_thumbnail_default( $id ){
			return $this->youtube_image.$id.'/maxresdefault.jpg';
		}
		function mona_get_youtube_data( $link ){
			$id = $this->mona_get_youtube_id( $link );
			
			$response = Mona_Youtube_API::get($id, $this->youtube_api);
			
			// HTTP Code
			$response_code = @$response['response_code'];
			
			if ($response_code != 200) {
				$response_msg = @$response['response_msg'];
				
				return array(
					'return_flag' => false,
					'data' => array(
						'errorcode' => $response_code,
						'reason' => $response_msg,
					),	
				);
			}
			
			// Body
			$data = @$response['body'];
			
			parse_str($data , $details);
			
			if (@$details['status'] == 'fail') {
				
				return array(
					'return_flag' => false,
					'data' => $details,	
				);
			}
			
			$my_formats_array = explode(',' , $details['adaptive_fmts']);
			$avail_formats[] = '';
			$i = 0;
			$ipbits = $ip = $itag = $sig = $quality_label = '';
			$expire = time();

			foreach ($my_formats_array as $format) {
				parse_str($format);
				$avail_formats[$i]['itag'] = $itag;
				$avail_formats[$i]['quality'] = $quality_label;
				$type = explode(';', $type);
				$avail_formats[$i]['type'] = $type[0];
				$avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
				parse_str(urldecode($url));
				$avail_formats[$i]['expires'] = date("G:i:s T", $expire);
				$avail_formats[$i]['ipbits'] = $ipbits;
				$avail_formats[$i]['ip'] = $ip;
				$i++;
			}
			
			return array(
				'return_flag' => true,
				'video_id' => $details['video_id'],
				'author' => $details['author'],
				'title' => $details['title'],
				'iurl' => $this->mona_get_youtube_thumbnail( $details ),
				'view' => $this->mona_get_round_number( (double) $details['view_count'] ),
				'duration' => ( $details['length_seconds'] > 3600 ) ? gmdate( 'H:i:s', $details['length_seconds'] ) : gmdate( 'i:s', $details['length_seconds'] ),
				'videos' => $avail_formats,
				'details' => $details,
			);
		}
		function mona_get_youtube_video_data( $url ){
			$data_all = $this->mona_get_youtube_data( $url );
			
			if( !@$data_all['return_flag'] ){
				$error_code = @$data_all['data']['errorcode'];
				$error_msg = @$data_all['data']['reason'];
				
				return array(
					'return_flag' => false,
					'message' => sprintf(
						__( 'Sorry, error code: %s. Reason: %s' ),
						$error_code,
						$error_msg
					),
				);
			}
			
			$data = $data_all['videos'];
			
			//video
			$return_video = array();
			$ext_video = $this->mona_get_youtube_video_type();			
			if( count( $data ) > 0 ){
				foreach( $data as $item ){
					$video_type = $item['type'];
					$video_quality = $item['quality'];
					$video_quality_arg = explode( 'p', $video_quality );
					$video_url = $item['url'];
					if( in_array( $video_type, $ext_video ) ){
						$video_quality = (int) $video_quality_arg[0];
						$video_type = str_replace('video/','',$video_type);
						if( !isset( $return_video[$video_quality] ) ){
							$obj = array(
								'ext' => $video_type,
								'url' => $video_url,
							);
							if( isset( $video_quality_arg[1] ) && $video_quality_arg[1] != '' ){
								$obj['fps'] = $video_quality_arg[1];
							}
							$return_video[$video_quality] = $obj;			
						}
					}
				}
			}			
			$data_all['videos'] = $return_video;
			
			//audio
			$return_audio = array();
			$ext_audio = $this->mona_get_youtube_audio_type();	
			if( count( $data ) > 0 ){
				foreach( $data as $item ){
					$audio_type = $item['type'];
					if( in_array( $audio_type, $ext_audio ) ){
						$return_audio = $this->mona_retrieve_audio_item( $return_audio, $item );
					}
				}
			}
			$data_all['audios'] = $return_audio;			
			
			return array(
				'return_flag' => true,
				'data' => $data_all,
			);
		}
		function mona_retrieve_audio_item( $array = array(), $item ){
			$defination = $this->mona_get_audio_defination();
			
			$audio_itag = $item['itag'];
			$audio_url = $item['url'];
			if( isset( $defination[$audio_itag] ) ){
				array_push(
					$array,
					array(
						'ext' => $defination[$audio_itag]['ext'],
						'bitrate' => $defination[$audio_itag]['bitrate'],
						'url' => $audio_url,
					)
				);
			}
			
			return $array;
		}
		function mona_get_audio_defination(){
			return array(
				'139' => array(
					'ext' => 'm4a',
					'bitrate' => '48 Kbps',
				),
				'140' => array(
					'ext' => 'm4a',
					'bitrate' => '128 Kbps',
				),
				'141' => array(
					'ext' => 'm4a',
					'bitrate' => '256 Kbps',
				),
				'171' => array(
					'ext' => 'webm',
					'bitrate' => '128 Kbps',
				),
				'172' => array(
					'ext' => 'webm',
					'bitrate' => '256 Kbps',
				),
			);
		}
		
		/**
		* Render
		*/
		function mona_render_downloader( $text ){
			//css
			wp_enqueue_style( 'mona-youtube-downloader-style', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/style.css', array(), null, 'all' ); 			
			//js
			wp_enqueue_script( 'mona-simplePopup-script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/jquery.simplePopup.js', array( 'jquery' ), true );
			wp_enqueue_script( 'mona-youtube-downloader-script', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'js/script.js', array( 'jquery' ), true ); 
			$localize = array(
				'ajax' => esc_url( admin_url( 'admin-ajax.php' ) ),
			);
			wp_localize_script( 'mona-youtube-downloader-script', 'mona_ajax', $localize ); ?>
			<div class="mona-form">
				<input type="text" class="mona-input mona-youtube" placeholder="<?php echo __( 'Example' ); ?>: <?php echo $this->youtube_sample; ?>" autocomplete="off" />
				<a href="javascript:;" class="mona-button"><?php echo $text; ?></a>
			</div>			
		<?php }			
		
		/**
		* Ajax
		*/
		function mona_ajax_youtube_downloader(){
			$text = sanitize_text_field( @$_POST['text'] );
			
			if( $text != '' ){
				$data = $this->mona_get_youtube_video_data( $text );
				
				if( !@$data['return_flag'] ){
					$message = isset( $data['message'] ) ? $data['message'] : __( 'Video not found.' );
					wp_send_json_error( array( 'message' => $message ) );
				}
				
				$data = $data['data'];
				
				if( $data && count( $data ) > 0 ){
					wp_send_json_success( array( 'youtube_data' => $data ) );
				}
			}
			
			wp_send_json_error( array( 'message' => __( 'Video not found.' ) ) );
		}
	}
	new Mona_Youtube_Downloader();
	
	if ( !function_exists( 'mona_get_youtube_id' ) ){
		function mona_get_youtube_id( $string = '' ){
			$youtube_downloader = new Mona_Youtube_Downloader();
			
			return $youtube_downloader->mona_get_youtube_id( $string );
		}
	}
	
	if ( !function_exists( 'mona_get_youtube_thumbnail' ) ){
		function mona_get_youtube_thumbnail( $string = '' ){
			$youtube_downloader = new Mona_Youtube_Downloader();
			$string = mona_get_youtube_id( $string );
			
			return $youtube_downloader->mona_get_youtube_thumbnail_default( $string );
		}
	}
	
	if ( !function_exists( 'mona_get_youtube_data' ) ){
		function mona_get_youtube_data( $link = '' ){
			$youtube_downloader = new Mona_Youtube_Downloader();
			
			return $youtube_downloader->mona_get_youtube_data( $link );
		}
	}
	
	if ( !function_exists( 'mona_get_youtube_video_data' ) ){
		function mona_get_youtube_video_data( $link = '' ){
			$youtube_downloader = new Mona_Youtube_Downloader();
			
			return $youtube_downloader->mona_get_youtube_video_data( $link );
		}
	}
}