<?php
	$theme = Array();
	
	// Theme name
	$theme['name'] = 'Twitter';
	// Description (you can use Tinyboard markup here)
	$theme['description'] = 'Simple news (tweets) listing via Twitter (twitter.com).';
	$theme['version'] = 'v0.1';
	
	// Theme configuration	
	$theme['config'] = Array();
	
	$theme['config'][] = Array(
		'title' => 'title',
		'name' => 'title',
		'type' => 'text',
		'comment' => 'Site title'
	);
	
	$theme['config'][] = Array(
		'title' => 'subtitle',
		'name' => 'subtitle',
		'type' => 'text',
		'comment' => 'Slogan (optional)'
	);
	
	$theme['config'][] = Array(
		'title' => 'html_file',
		'name' => 'html_file',
		'type' => 'text',
		'default' => $config['file_index'],
		'comment' => 'HTML file (eg. "index.html")'
	);

	$theme['config'][] = Array(
		'title' => 'twitter_screen_name',
		'name' => 'twitter_screen_name',
		'type' => 'text',
		'comment' => 'Name Of Twitter Account'
	);

	$theme['config'][] = Array(
		'title' => 'consumer_key',
		'name' => 'consumer_key',
		'type' => 'text',
		'comment' => 'Twitter Consumer Key (API Key) this data creates at https://apps.twitter.com/'
	);
	
	$theme['config'][] = Array(
		'title' => 'consumer_secret',
		'name' => 'consumer_secret',
		'type' => 'text',
		'comment' => 'Consumer Secret (API Secret)'
	);

	$theme['config'][] = Array(
		'title' => 'access_token',
		'name' => 'access_token',
		'type' => 'text',
		'comment' => 'Twitter Access Token'
	);
	
	$theme['config'][] = Array(
		'title' => 'access_token_secret',
		'name' => 'access_token_secret',
		'type' => 'text',
		'comment' => 'Twitter Access Token Secret'
	);
	
	$theme['config'][] = Array(
		'title' => 'tweets_count',
		'name' => 'tweets_count',
		'type' => 'text',
		'default' => '5',
		'comment' => 'Count Of Tweets (20 max)'
	);
	
	// Unique function name for building everything
	$theme['build_function'] = 'twitter_build';
	$theme['install_callback'] = 'twitter_install';

	if (!function_exists('twitter_install')) {
		function twitter_install($settings) {
			if (!is_numeric($settings['tweets_count']) || $settings['tweets_count'] < 0 || $settings['tweets_count'] > 21)
				return Array(false, '<strong>' . utf8tohtml($settings['no_recent']) . '</strong> is not a non-negative integer.');
		}
	}