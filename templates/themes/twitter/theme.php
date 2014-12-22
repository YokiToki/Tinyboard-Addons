<?php
	require 'info.php';
	
	function twitter_build($action, $settings, $board) {
		// Possible values for $action:
		//	- all (rebuild everything, initialization)
		//	- boards (board list changed)
		//	- post (a post has been made)
		//	- post-thread (a thread has been made)
		
		$b = new Twitter();
		$b->build($action, $settings);
	}
	
	// Wrap functions in a class so they don't interfere with normal Tinyboard operations
	class Twitter {

		public function build($action, $settings) {
			global $config;
			
			
			if ($action == 'all' || $action == 'boards' || $action == 'post' || $action == 'post-thread')
				file_write($config['dir']['home'] . $settings['html_file'], $this->homepage($settings));

		}
		
		// Build news page
		public function homepage($settings) {
			global $config, $board;

			$tweets = array();

			require_once("lib/OAuth.php");
			require_once("lib/twitteroauth.php");
			
			$connection = new TwitterOAuth($settings['consumer_key'],$settings['consumer_secret'],$settings['access_token'],$settings['access_token_secret']);
 			$twitter = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$settings['twitter_screen_name']."&count=".$settings['tweets_count']);

			if(!empty($twitter)) {

				$i=0;
				$count = count($twitter) < $settings['tweets_count'] ? count($twitter) : $settings['tweets_count'];

				while ($i < $count) {
					$tweets[$i]['id'] = $twitter[$i]->id_str;
					$tweets[$i]['body'] = preg_replace('/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', '<a href="${0}" target="_blank">${0}</a>', $twitter[$i]->text);
					$tweets[$i]['body'] = preg_replace('/@[A-z0-9]+/i', '<a href="https://twitter.com/${0}" target="_blank">${0}</a>', $tweets[$i]['body']);
					$tweets[$i]['body'] = preg_replace('/\\/@/i', '/', $tweets[$i]['body']);
					$tweets[$i]['body'] = preg_replace('/\#[A-zА-я0-9]+/iu', '<a href="https://twitter.com/hashtag/${0}?src=hash" target="_blank">${0}</a>', $tweets[$i]['body']);
					$tweets[$i]['body'] = preg_replace('/\\/#/i', '/', $tweets[$i]['body']);
					$tweets[$i]['screen_name'] = $twitter[$i]->user->screen_name;
					$tweets[$i]['time'] = strtotime($twitter[$i]->created_at);
					$tweets[$i]['profile_url'] = "https://twitter.com/" . $tweets[$i]['screen_name'] . "/";					
					$tweets[$i]['tweet_url'] = $tweets[$i]['profile_url'] . "status/" . $tweets[$i]['id'];
					$tweets[$i]['media_url_https'] = !empty($twitter[$i]->entities->media[0]->media_url_https) ? $twitter[$i]->entities->media[0]->media_url_https : "";

					$i++;
				}

			}
			
			return Element('themes/twitter/index.html', Array(
				'settings' => $settings,
				'config' => $config,
				'boardlist' => createBoardlist(),
				'tweets' => $tweets,
			));

		}
	};
	
?>
