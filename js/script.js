jQuery(document).ready(function($){
	$('body').on('click', '.mona-button', function(){
		var container = $(this).closest('.mona-form');
		var text = container.find('.mona-input.mona-youtube').val();
		
		if( text.length > 0 ){
			$.ajax({
				url: mona_ajax.ajax,
				dataType: 'json',
				type: 'POST',
				data: {
					action: 'mona_youtube_downloader',
					text: text,
				},
				success: function( ret ) {	
					if( ret.success ){		
						if( ret.data != undefined && ret.data != null ){
							var data = ret.data;
							
							if( data.youtube_data != undefined && data.youtube_data != null ){
								var youtube_data = data.youtube_data;console.log(youtube_data);
								
								if( youtube_data.videos != undefined && youtube_data.videos != null ){
									var html = '';
									
									mona_leecher_popup();
									
									//description
									html = '<div class="mona-video-description"><div class="mona-video-pic"><img src="'+youtube_data.iurl+'" /></div>';
									html += '<div class="mona-video-info"><h3>'+youtube_data.title+'</h3>';
									html += '<span><strong>'+youtube_data.author+'</strong> / '+youtube_data.duration+' / '+youtube_data.view+'</span><div class="clearfix"></div>';
									html += '</div></div>';
									$('#mona-youtube-downloader-popup').append( html );
									
									//video
									var videos = youtube_data.videos;
									$('#mona-youtube-downloader-popup').find('.mona-video-info').append('<div class="video-info-feat"><h4>Full video</h4><ul class="mona-video-items"></ul></div>');
									$.each(videos, function(key, value) {
										html = '<li class="mona-video-item"><a href="'+value.url+'" title="Right-click and &quot;Save link as..&quot;" download="'+youtube_data.title+' ('+key+'p).mp4"><div class="mona-video-item-quality"><span>'+key+'p</span></div>';
										html += '<div class="mona-video-item-url">';
										if( value.fps != undefined && value.fps != null ){
											html += value.fps+' fps';
										}
										html += '.'+value.ext+'</div></a></li>';
										$('body').find('#mona-youtube-downloader-popup').find('ul.mona-video-items').append(html);
									});
									//audio
									var audios = youtube_data.audios;
									$('#mona-youtube-downloader-popup').find('.mona-video-info').append('<div class="video-info-feat"><h4>Audio only</h4><ul class="mona-video-items mona-audio-items"></ul></div>');
									$.each(audios, function(key, value) {
										html = '<li class="mona-video-item"><div class="mona-video-item-quality"><a href="'+value.url+'" title="Right-click and &quot;Save link as..&quot;"><span>'+value.bitrate+'</span></div>';
										html += '<div class="mona-video-item-url">.'+value.ext+'</div></a></li>';
										$('body').find('#mona-youtube-downloader-popup').find('ul.mona-audio-items').append(html);
									});									
									
									// popup
									$('body').find('#mona-youtube-downloader-popup').simplePopup({
										centerPopup: false,
									});
									$('.simplePopupBackground').fadeIn('fast');
									$('#mona-youtube-downloader-popup').show();
								} 
							}
						}
						
						return false;
					}else{
						var html = '';
									
						mona_leecher_popup();
						
						html = '<div class="mona-video-description">Error: '+ret.data.message+'</div>';
						$('#mona-youtube-downloader-popup').append( html );
						
						// popup
						$('body').find('#mona-youtube-downloader-popup').simplePopup({
							centerPopup: false,
						});
						$('.simplePopupBackground').fadeIn('fast');
						$('#mona-youtube-downloader-popup').show();
						
						return false;
					}
				},
			});
		}
	});
	function mona_leecher_popup(){
		html = '<div id="mona-youtube-downloader-popup" class="mona-popup" style="display: none;"></div>';
		
		if( $('#mona-youtube-downloader-popup').length < 1 ){
			$('body').append( html );
		}
		$('#mona-youtube-downloader-popup').html('');
	}
});