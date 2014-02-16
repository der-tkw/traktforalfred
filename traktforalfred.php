<?php

require('workflows.php');
$w = new Workflows();
$apikey = $w->get('apikey', 'settings.plist');
$mode = $argv[1];
$query = $argv[2];
$query = str_replace(' ', '+', $query);
$debugEnabled = false;

$id;
$operation;

$showPrefix = 's:';
$moviePrefix = 'm:';
$trendsPrefix = 't:';

if (!$apikey) {
	$w->result('', '', 'Error', 'API key has not been set yet. Set it with the command \'apikey\'.', 'icons/error.png');
} else {
	if (strpos($query, $showPrefix) === 0) {
		// this is a show
		$queryArray = explode(":", $query);
		$id = $queryArray[1];
		$operation = $queryArray[2];
		
		switch ($operation) {
			case 'summary':
				display_show_summary();
				break;
			case 'epguide':
				display_show_epguide();
				break;
			case 'cast':
				display_show_cast();
				break;
			case 'watchlist':
				watchlist_item('show/watchlist', 'tvdb_id', intval($id), 'shows', $showPrefix, 'The show has been added to your watchlist!');
				break;
			case 'unwatchlist':
				watchlist_item('show/unwatchlist', 'tvdb_id', intval($id), 'shows', $showPrefix, 'The show has been removed from your watchlist!');
				break;
		}
	} else if (strpos($query, $moviePrefix) === 0) {
		// this is a movie
		$queryArray = explode(":", $query);
		$id = $queryArray[1];
		$operation = $queryArray[2];
		
		switch ($operation) {
			case 'summary':
				display_movie_summary();
				break;
			case 'cast':
				display_movie_cast();
				break;
			case 'watchlist':
				watchlist_item('movie/watchlist', 'imdb_id', $id, 'movies', $moviePrefix, 'The movie has been added to your watchlist!');
				break;
			case 'unwatchlist':
				watchlist_item('movie/unwatchlist', 'imdb_id', $id, 'movies', $moviePrefix, 'The movie has been removed from your watchlist!');
				break;
		}
	} else if (strpos($query, $trendsPrefix) === 0) {
		// this is a trend
		$queryArray = explode(":", $query);
		$trendMode = $queryArray[1];
		
		switch ($trendMode) {
			case 'shows':
				display_show_trends();
				break;
			case 'movies':
				display_movie_trends();
				break;
		}
	} else {
		switch($mode) {
			case 'trends':
				display_trend_options();
				break;
			case 'shows':
				search_shows();
				break;
			case 'movies':
				search_movies();
				break;
		}
	}
}

echo $w->toxml();

/**
 * Display trending options
 */
function display_trend_options() {
	global $apikey, $w, $trendsPrefix;
	$w->result('showtrends', '', 'Display trending shows ...', '', 'icons/trend.png', 'no', $trendsPrefix.'shows');
	$w->result('movietrends', '', 'Display trending movies ...', '', 'icons/trend.png', 'no', $trendsPrefix.'movies');
}

/**
 * List all trending movies
 */
function display_movie_trends() {
	global $apikey, $w;
	$url = "http://api.trakt.tv/movies/trending.json/$apikey";
	$movies = $w->request($url);
	$movies = json_decode($movies);
	
	if (is_valid($movies)) {
		$w->result('movietrends', '', 'Back ...', '', 'icons/back.png', 'no', ' ');
		print_movies($movies);
	}
}

/**
 * List all trending shows
 */
function display_show_trends() {
	global $apikey, $w;
	$url = "http://api.trakt.tv/shows/trending.json/$apikey";
	$shows = $w->request($url);
	$shows = json_decode($shows);
	
	if (is_valid($shows)) {
		$w->result('showtrends', '', 'Back ...', '', 'icons/back.png', 'no', ' ');
		print_shows($shows);
	}
}

/**
 * Search for shows
 */
function search_shows() {
	global $apikey, $w, $query;
	$url = "http://api.trakt.tv/search/shows.json/$apikey?query=$query";
	$shows = $w->request($url);
	$shows = json_decode($shows);
	
	if (is_valid($shows)) {
		print_shows($shows);
				
		if (count($w->results()) == 0) {
			$w->result( 'info', '', 'No results', 'Please widen your search.', 'icons/info.png', 'no');
		}
	}
}

/**
 * Search for movies
 */
function search_movies() {
	global $apikey, $w, $query;
	$url = "http://api.trakt.tv/search/movies.json/$apikey?query=$query";
	$movies = $w->request($url);
	$movies = json_decode($movies);
	
	if (is_valid($movies)) {
		print_movies($movies);
				
		if (count($w->results()) == 0) {
			$w->result( 'info', '', 'No results', 'Please widen your search.', 'icons/info.png', 'no');
		}
	}
}

/**
 * Display a show summary
 */
function display_show_summary() {
	global $apikey, $w, $id, $showPrefix;
	$url = "http://api.trakt.tv/show/summary.json/$apikey/$id/extended";
	$options = get_post_options();
	$show = $w->request($url, $options);
	$show = json_decode($show);
	
	if (is_valid($show)) {
		$count = count_episodes($show);
		$maincast = get_main_cast($show);
		$latestEp = get_latest_episode($show);
		$trailer = str_replace(' ', '+', $show->title.' trailer');
		
		$w->result('summary', '', $show->title.' ('.$show->year.')', 'Runtime: '.$show->runtime.'min, Rating: '.$show->ratings->percentage.'%', 'icon.png');
		if (isset($latestEp)) {
			$w->result('epguide', $latestEp->url, 'Latest Episode: '.$latestEp->season.'x'.sprintf("%02d", $latestEp->episode).': '.$latestEp->title, 'Aired: '.explode("T", $latestEp->first_aired_iso)[0].', Rating: '.$latestEp->ratings->percentage.'%', 'icons/date.png');
		}
		if ($count[0] > 0) {
			$specials;
			if ($count[1] > 0) {
				$specials = ' (Plus '.$count[1].' Special Episodes)';
			}
			$w->result('summary', '', 'Show Episode List ...', 'Total Episodes: '.$count[0].$specials, 'icons/episodes.png', 'no', $showPrefix.$show->tvdb_id.':epguide');
		}
		if (isset($maincast)) {
			$w->result('summary', '', 'Show Cast ...', $maincast.', ...', 'icons/cast.png', 'no', $showPrefix.$show->tvdb_id.':cast');
		}
		$w->result('summary', '', 'Network: '.$show->network.', Status: '.$show->status, 'Air Day: '.$show->air_day.', Air Time: '.$show->air_time, 'icons/network.png');
		if (!empty($options)) {
			if ($show->in_watchlist) {
				$w->result('unwatchlist', '', 'Remove from watchlist', '', 'icons/watchlistremove.png', 'no', $showPrefix.$show->tvdb_id.':unwatchlist');
			} else {
				$w->result('watchlist', '', 'Add to watchlist', '', 'icons/watchlistadd.png', 'no', $showPrefix.$show->tvdb_id.':watchlist');
			}
		}
		if (!empty($show->certification)) {
			$w->result('certification', '', $show->certification, 'Certification', 'icons/certification.png');
		}
		$w->result('summary', '', $show->stats->watchers.' Watchers, '.$show->stats->plays.' Plays, '.$show->stats->scrobbles.' Scrobbles', 'Stats', 'icons/stats.png');
		$w->result('summary', $show->url, 'View on trakt.tv', '', 'icons/external.png');
		$w->result('summary', "http://www.imdb.com/title/$show->imdb_id/", 'View on IMDB', '', 'icons/external.png');
		$w->result('summary', "https://www.youtube.com/results?search_query=$trailer", 'Search for a trailer on YouTube', '', 'icons/external.png');
	}
}

/**
 * Display a movie summary
 */
function display_movie_summary() {
	global $apikey, $w, $id, $moviePrefix;
	$url = "http://api.trakt.tv/movie/summary.json/$apikey/$id";
	$options = get_post_options();
	$movie = $w->request($url, $options);
	$movie = json_decode($movie);
	
	if (is_valid($movie)) {		
		$maincast = get_main_cast($movie);
		$w->result('summary', '', $movie->title.' ('.$movie->year.')', 'Runtime: '.$movie->runtime.'min, Rating: '.$movie->ratings->percentage.'%', 'icon.png');
		if (!empty($movie->released)) {
			date_default_timezone_set('UTC');
			$w->result('summary', '', date("Y-m-d", $movie->released), 'Release Date', 'icons/date.png');
		}
		if (isset($maincast)) {
			$w->result('summary', '', 'Show Cast ...', $maincast.', ...', 'icons/cast.png', 'no', $moviePrefix.$movie->imdb_id.':cast');
		}
		if (!empty($options)) {
			if ($movie->in_watchlist) {
				$w->result('unwatchlist', '', 'Remove from watchlist', '', 'icons/watchlistremove.png', 'no', $moviePrefix.$movie->imdb_id.':unwatchlist');
			} else {
				$w->result('watchlist', '', 'Add to watchlist', '', 'icons/watchlistadd.png', 'no', $moviePrefix.$movie->imdb_id.':watchlist');
			}
		}
		if (!empty($movie->certification)) {
			$w->result('certification', '', $movie->certification, 'Certification', 'icons/certification.png');
		}
		$w->result('summary', '', $movie->stats->watchers.' Watchers, '.$movie->stats->plays.' Plays, '.$movie->stats->scrobbles.' Scrobbles', 'Stats', 'icons/stats.png');
		$w->result('summary', $movie->url, 'View on trakt.tv', '', 'icons/external.png');
		$w->result('summary', "http://www.imdb.com/title/$movie->imdb_id/", 'View on IMDB', '', 'icons/external.png');
		if (!empty($movie->trailer)) {
			$w->result('summary', $movie->trailer, 'Watch trailer on YouTube', '', 'icons/external.png');
		}
	}
}

/**
 * Add or remove a show/movie to/from your watchlist
 */
function watchlist_item($apiName, $idName, $idValue, $fieldName, $prefix, $okMessage) {
	global $apikey, $w, $id;
	$url = "http://api.trakt.tv/$apiName/$apikey";
	
	$item = array($idName => $idValue);
	$additional = array($fieldName => array($item));
	$options = get_post_options($additional);
	
	$watchlist = $w->request($url, $options);
	$watchlist = json_decode($watchlist);
	
	$w->result('watchlist', '', 'Back ...', '', 'icons/back.png', 'no', $prefix.$id.':summary');
	if (is_valid($watchlist)) {
		$w->result('watchlist', '', $okMessage, '', 'icon.png', 'no', $prefix.$id.':summary');
	}
}

/**
 * Show the epguide of the current show
 */
function display_show_epguide() {
	global $apikey, $w, $id, $showPrefix;
	$url = "http://api.trakt.tv/show/summary.json/$apikey/$id/extended";
	$show = $w->request($url);
	$show = json_decode($show);
	
	if (is_valid($show)) {
		$w->result('epguide', '', 'Back ...', '', 'icons/back.png', 'no', $showPrefix.$id.':summary');
		foreach($show->seasons as $season):
			foreach($season->episodes as $episode):
				$w->result('epguide', $episode->url, $season->season.'x'.sprintf("%02d", $episode->episode).': '.$episode->title, 'Aired: '.explode("T", $episode->first_aired_iso)[0].', Rating: '.$episode->ratings->percentage.'%', 'icons/episode.png');
			endforeach;
		endforeach;
	}
	$w->sortresults('title', false);
}

/**
 * Display the show cast
 */
function display_show_cast() {
	global $apikey, $w, $id, $showPrefix;
	$url = "http://api.trakt.tv/show/summary.json/$apikey/$id/extended";
	$show = $w->request($url);
	$show = json_decode($show);
	
	if (is_valid($show)) {
		$w->result('cast', '', 'Back ...', '', 'icons/back.png', 'no', $showPrefix.$id.':summary');
		foreach($show->people->actors as $actor):
			$w->result('cast', '', $actor->character, $actor->name, 'icons/actor.png', 'no');
		endforeach;
	}
}

/**
 * Display the movie cast
 */
function display_movie_cast() {
	global $apikey, $w, $id, $moviePrefix;
	$url = "http://api.trakt.tv/movie/summary.json/$apikey/$id";
	$movie = $w->request($url);
	$movie = json_decode($movie);
	
	if (is_valid($movie)) {
		$w->result('cast', '', 'Back ...', '', 'icons/back.png', 'no', $moviePrefix.$id.':summary');
		foreach($movie->people->actors as $actor):
			$w->result('cast', '', $actor->character, $actor->name, 'icons/actor.png', 'no');
		endforeach;
		foreach($movie->people->directors as $director):
			$w->result('cast', '', $director->name, 'Director', 'icons/othercast.png', 'no');
		endforeach;
		foreach($movie->people->writers as $writer):
			$w->result('cast', '', $writer->name, 'Writer', 'icons/othercast.png', 'no');
		endforeach;
		foreach($movie->people->producers as $producer):
			$w->result('cast', '', $producer->name, 'Producer', 'icons/othercast.png', 'no');
		endforeach;
	}
}

/**
 * Print the specified movies.
 */
function print_movies($movies) {
	global $w, $moviePrefix;
	foreach($movies as $movie):
		$w->result('movie', $movies->imdb_id, $movie->title, 'Rating: '.$movie->ratings->percentage.'% | Year: '.$movie->year.' | Genres: '.implode(", ", $movie->genres), 'icon.png', 'no', $moviePrefix.$movie->imdb_id.':summary');
	endforeach;
}

/**
 * Print the specified shows.
 */
function print_shows($shows) {
	global $w, $showPrefix;
	foreach($shows as $show):
		$w->result('show', $show->tvdb_id, $show->title, 'Rating: '.$show->ratings->percentage.'% | Year: '.$show->year.' | Network: '.$show->network.' | Genres: '.implode(", ", $show->genres), 'icon.png', 'no', $showPrefix.$show->tvdb_id.':summary');
	endforeach;
}

/**
 * Get a list of top 2 cast
 */
function get_main_cast($item) {
	$result = array();
	$cnt = 0;
	foreach($item->people->actors as $actor):
		if ($cnt < 2) {
			array_push($result, $actor->character.' ('.$actor->name.')');
			$cnt++;
		}
	endforeach;
	
	if (!empty($result)) {
		return implode(", ", $result);
	}
}

/**
 * Count episodes
 */
function count_episodes($show) {
	$counts = array();
	$normalCnt = 0;
	$specialCnt = 0;
	foreach($show->seasons as $season):
		if ($season->season > 0) {
			foreach($season->episodes as $episode):
				$normalCnt++;
			endforeach;
		} else {
			foreach($season->episodes as $episode):
				$specialCnt++;
			endforeach;
		}
	endforeach;
	array_push($counts, $normalCnt);
	array_push($counts, $specialCnt);
	return $counts;
}

/**
 * Find the latest episode
 */
function get_latest_episode($show) {
	date_default_timezone_set('UTC');
	$today = new DateTime("now");
	$latestEpisode;
	$diff = 2147483647;
	foreach($show->seasons as $season):
		if ($season->season > 0) {
			foreach($season->episodes as $episode):
				if (!isset($episode->first_aired_iso)) {
					continue;
				}
				$epdate = new DateTime(explode("T", $episode->first_aired_iso)[0]);
				$interval = $today->diff($epdate);
				// only continue if interval is negative (in the past)
				if ($interval->invert == 1 && $interval->days <= $diff) {
					$diff = $interval->days;
					$latestEpisode = $episode;
				}
			endforeach;
		}
	endforeach;
	if (isset($latestEpisode)) {
		return $latestEpisode;
	}
}

/**
 * Get the post options. Returns array with some POST options and the username and password if not empty. 
 * Otherwise an empty array will be returned.
 
 * @param $additional - additional POST fields
 */
function get_post_options($additional=null) {
	global $w;
	$username = $w->get('username', 'settings.plist');
	$password = $w->get('password', 'settings.plist');
	
	if (empty($username) || empty($password)) {
		return array();
	}
	
	$post = array('username' => $username, 'password' => $password);
	if ($additional) {
		foreach( $additional as $k => $v ):
			$post[$k] = $v;
		endforeach;
	}
	
	$options = array(CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $post);
	return $options;
}

/**
 * Check if the specified json is valid.
 *
 * @param $json - the json that should be checked
 * @return bool - true in case the json is valid, false otherwise
 */
function is_valid($json) {
	global $w;
	if (isset($json->status) && $json->status == 'failure') {
		$w->result('error', '', 'Error', $json->error, 'icons/error.png', 'no');
		return false;
	}
	return true;
}

/**
 * Log something to a file.
 */
function debuglog($what) {
	global $w, $debugEnabled;
	date_default_timezone_set('UTC');
	$fileName = 'debug.log';
	if ($debugEnabled) {
		$w->write(date('Y-m-d G:i:s').' -- ', $fileName);
		$w->write($what,  $fileName);
		$w->write(PHP_EOL, $fileName);
	}
}

?>