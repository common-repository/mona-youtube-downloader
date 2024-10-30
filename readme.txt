=== Mona Youtube Downloader ===
Contributors: thietkewebsite, diancom1202 
Tags: youtube, downloader, youtube api, download video, download from youtube, social network, get link youtube, get link video, video downloader, mona media, get video, get video download, download video youtube
Requires at least: 4.4
Tested up to: 5.0.2
Stable tag: 1.3
Requires PHP: 7.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This simple but powerful plugin will help you increase your siteâ€™s traffic by provide your users a very helpful function: download video from youtube

You can use it as Widget and Shortcode also. Include every where you want.


== Description ==

Check out live demo here: <a href="https://mona-media.com/project/mona-youtube-downloader-wordpress-plugin/">https://mona-media.com/project/mona-youtube-downloader-wordpress-plugin/</a>


This simple but powerful plugin will help you increase your siteâ€™s traffic by provide your users a very helpful function: download video from youtube.
You can use it as Widget and Shortcode also. Include every where you want.

Major features in Mona Youtuber Downloader include:

* Download multi video's quality
* Simple but beautiful look 
* Can be use at Shortcode and Widget
* Custom functions for further development
	

Follow us at <a href="https://mona-media.com/">Mona Media</a> 


= Our plugins =
We also making premium plugin in case that you guys love this plugin. Comment and review it if you want me to put it premium with more kool function.

Some of our plugins you may like:
- Making Kool Reaction voting system for you post/page <a href=â€œhttps://codecanyon.net/item/mona-reaction-voting-system-with-customizable-reactions-wordpress-plugin/15352246â€>https://codecanyon.net/item/mona-reaction-voting-system-with-customizable-reactions-wordpress-plugin/15352246</a>.
- Pokedex - pokemon knowledge base: <a href=â€œhttps://codecanyon.net/item/mona-pokedex-pokemon-go-knowledge-base-wordpress-plugin/17568438â€>https://codecanyon.net/item/mona-pokedex-pokemon-go-knowledge-base-wordpress-plugin/17568438</a>.
- Adding Emoji: <a href=â€œhttps://codecanyon.net/item/mona-emoji-custom-funny-emojiemoticon-on-your-postpage-wordpress-plugin/16222462â€>https://codecanyon.net/item/mona-emoji-custom-funny-emojiemoticon-on-your-postpage-wordpress-plugin/16222462</a>.
- Powerful analytic system for your website: <a href=â€œhttps://codecanyon.net/item/mona-analytics-the-most-powerful-all-in-one-wordpress-analytics-plugin/17254577â€>https://codecanyon.net/item/mona-analytics-the-most-powerful-all-in-one-wordpress-analytics-plugin/17254577</a>.

PS: For custom functions, recommend to use at ajax action for better performance.


== Installation ==

1. Upload the `mona-youtube-downloader` directory to the `/wp-content/plugins/` directory via FTP
2. Alternatively, upload the `mona-youtube-downloader.zip` file to the 'Plugins->Add New' page in your WordPress admin
area
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Use **[mona_youtube_downloader]** shortcode where you want to display Youtube search input

1, 2, 3: You're done!
	
= Custom functions for further development =
1. **mona_get_youtube_id**: 
	Parameter: Youtube URL (string) (Required)
	Return: (string) Youtube ID
2. **mona_get_youtube_thumbnail**: 
	Parameter: Youtube URL (string) (Required)
	Return: (string) Youtube Thumbnail Image
3. **mona_get_youtube_data**: 
	Parameter: Youtube URL (string) (Required)
	Return: (mixed) Youtube raw data
4. **mona_get_youtube_video_data**: 
	Parameter: Youtube URL (string) (Required)
	Return: (mixed) Youtube video data


== Frequently Asked Questions ==

=How can I display Youtube search input?=
- For widget simply drag and drop **[Mona] Youtube Downloader** widget to any where you want.
- For shortcode, put this **[mona_youtube_downloader]** to any where you want.

=What is custom functions for further development?=
- Return Youtube ID from URL: **mona_get_youtube_id**
	Parameter: Youtube URL (string) (Required)
	Return: (string) Youtube ID
- Return Youtube Thumbnail image from URL: **mona_get_youtube_thumbnail**
	Parameter: Youtube URL (string) (Required)
	Return: (string) Youtube Thumbnail Image
- Return Raw data from Youtube API from URL: **mona_get_youtube_data**
	Parameter: Youtube URL (string) (Required)
	Return: (mixed) Youtube raw data
- Return Video data from Youtube API from URL: **mona_get_youtube_video_data**
	Parameter: Youtube URL (string) (Required)
	Return: (mixed) Youtube video data

=Is it free?=
Yes it free.

== Screenshots ==

1. Popup modal.


== Changelog ==

= 1.3 =
* HTTP API support

= 1.2 =
* Refactor functions
* Custom functions for further development

= 1.1 =
* Improve functions for new updating from Youtube

= 1.0 =
* Initial Release