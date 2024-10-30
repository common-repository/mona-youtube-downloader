<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists( 'Mona_Youtube_API' ) ){
	class Mona_Youtube_API {		
		/**
		* Class Construct
		*/
		public function __construct() {	
			$this->hd = 1;
			$this->asv = 2;
			$this->fmt = 18;
		}
		
		public function get($youtube_id = '', $youtube_api = ''){
			$response = wp_remote_request(
				$youtube_api, 
				array(
					'method' => 'GET', 
					'httpversion' => '1.1',
					'body' => array(
						'video_id' => $youtube_id, 
						'hd' => $this->hd, 
						'asv' => $this->asv, 
						'fmt' => $this->fmt,
					),
				)
			);
			
			// HTTP Code
			$response_code = wp_remote_retrieve_response_code($response);
			
			if ($response_code != 200) {
				$response_msg = wp_remote_retrieve_response_message($response_code);
				
				return array(
					'result' => false,
					'response_code' => $response_code,
					'response_msg' => $response_msg,
				);
			}
			
			return array(
				'result' => true,
				'response_code' => $response_code,	
				'body' => wp_remote_retrieve_body($response),	
			);
		}
	
	}
}