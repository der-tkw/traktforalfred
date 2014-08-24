<?php

date_default_timezone_set('UTC');
require('workflows.php');
$w = new Workflows();

$baseurl = 'https://api.trakt.tv/';
$apikey = '19986d72b1d08e3aab27d25fe2d46eca8b0152cd';
$mode = $argv[1];
$query = $argv[2];
$query = str_replace(' ', '+', $query);
$debugEnabled = false;

$id = null;
$operation = null;
$season = null;
$episode = null;
$rating = null;

$showPrefix = 's:';
$moviePrefix = 'm:';
$trendsPrefix = 't:';
$watchlistPrefix = 'w:';
$episodePrefix = 'e:';
$libraryPrefix = 'l:';
$recommendationPrefix = 'r:';

$ratingOptions = array(
    'Totally ninja! (10)' => 10,
    'Superb (9)' => 9,
    'Great (8)' => 8,
    'Good (7)' => 7,
    'Fair (6)' => 6,
    'Meh (5)' => 5,
    'Poor (4)' => 4,
    'Bad (3)' => 3,
    'Terrible (2)' => 2,
    'Weak sauce :( (1)' => 1
);

if (strpos($query, $showPrefix) === 0) {
    // this is a show
    $queryArray = explode(':', $query);
    $id = $queryArray[1];
    $operation = $queryArray[2];

    switch ($operation) {
        case 'summary':
            print_show_summary();
            break;
        case 'epguide':
            print_show_epguide();
            break;
        case 'cast':
            print_show_cast();
            break;
        case 'options':
            print_show_options();
            break;
        case 'watchlist':
        case 'unwatchlist':
            handle_show_option("show/$operation");
            break;
        case 'rate':
            if (count($queryArray) === 4) {
                $rating = $queryArray[3];
                handle_rating('show', $showPrefix.$id.':options');
            } else {
                print_rating_options($showPrefix.$id.':summary', $showPrefix.$id.':rate:');
            }
            break;
        case 'unrate':
            $rating = '0';
            handle_rating('show', $showPrefix.$id.':options');
            break;
    }
} else if (strpos($query, $moviePrefix) === 0) {
    // this is a movie
    $queryArray = explode(':', $query);
    $id = $queryArray[1];
    $operation = $queryArray[2];

    switch ($operation) {
        case 'summary':
            print_movie_summary();
            break;
        case 'cast':
            print_movie_cast();
            break;
        case 'options':
            print_movie_options();
            break;
        case 'watchlist':
        case 'unwatchlist':
        case 'seen':
        case 'unseen':
        case 'library':
        case 'unlibrary':
            handle_movie_option("movie/$operation");
            break;
        case 'rate':
            if (count($queryArray) === 4) {
                $rating = $queryArray[3];
                handle_rating('movie', $moviePrefix.$id.':options');
            } else {
                print_rating_options($moviePrefix.$id.':summary', $moviePrefix.$id.':rate:');
            }
            break;
        case 'unrate':
            $rating = '0';
            handle_rating('movie', $moviePrefix.$id.':options');
            break;
        case 'checkin':
            handle_checkin('movie', $moviePrefix.$id.':options');
            break;
        case 'cancelcheckin':
            handle_cancelcheckin('movie', $moviePrefix.$id.':options');
            break;
    }
} else if (strpos($query, $episodePrefix) === 0) {
    // this is an episode
    $queryArray = explode(':', $query);
    $id = $queryArray[1];
    $season = $queryArray[2];
    $episode = $queryArray[3];
    $operation = $queryArray[4];

    switch ($operation) {
        case 'summary':
            print_episode_summary();
            break;
        case 'options':
            print_episode_options();
            break;
        case 'watchlist':
        case 'unwatchlist':
        case 'seen':
        case 'unseen':
        case 'library':
        case 'unlibrary':
            handle_episode_option("show/episode/$operation");
            break;
        case 'rate':
            if (count($queryArray) === 6) {
                $rating = $queryArray[5];
                handle_rating('episode', $episodePrefix.$id.':'.$season.':'.$episode.':options');
            } else {
                print_rating_options($episodePrefix.$id.':'.$season.':'.$episode.':summary',
                    $episodePrefix.$id.':'.$season.':'.$episode.':rate:');
            }
            break;
        case 'unrate':
            $rating = '0';
            handle_rating('episode', $episodePrefix.$id.':'.$season.':'.$episode.':options');
            break;
        case 'checkin':
            handle_checkin('show', $episodePrefix.$id.':'.$season.':'.$episode.':options');
            break;
        case 'cancelcheckin':
            handle_cancelcheckin('show', $episodePrefix.$id.':'.$season.':'.$episode.':options');
            break;
    }
} else if (strpos($query, $trendsPrefix) === 0) {
    // this is a trend
    $queryArray = explode(':', $query);
    $trendMode = $queryArray[1];

    switch ($trendMode) {
        case 'shows':
            print_show_trends();
            break;
        case 'movies':
            print_movie_trends();
            break;
    }
} else if (strpos($query, $watchlistPrefix) === 0) {
    // this is a watchlist
    $queryArray = explode(':', $query);
    $watchlistMode = $queryArray[1];

    switch ($watchlistMode) {
        case 'shows':
            print_show_watchlist();
            break;
        case 'movies':
            print_movie_watchlist();
            break;
        case 'episodes':
            print_episode_watchlist();
            break;
    }
} else if (strpos($query, $libraryPrefix) === 0) {
    // this is a library
    $queryArray = explode(':', $query);
    $libraryType = $queryArray[1];

    switch ($libraryType) {
        case 'watchedshows':
            print_show_library('watched');
            break;
        case 'collectedshows':
            print_show_library('collection');
            break;
        case 'watchedmovies':
            print_movie_library('watched');
            break;
        case 'collectedmovies':
            print_movie_library('collection');
            break;
    }
} else if (strpos($query, $recommendationPrefix) === 0) {
    // this is a recommendation
    $queryArray = explode(':', $query);
    $recommendationPrefix = $queryArray[1];

    switch ($recommendationPrefix) {
        case 'shows':
            print_show_recommendations();
            break;
        case 'movies':
            print_movie_recommendations();
            break;
    }
} else {
    switch ($mode) {
        case 'trends':
            print_trend_options();
            break;
        case 'watchlists':
            print_watchlist_options();
            break;
        case 'libraries':
            print_library_options();
            break;
        case 'recommendations':
            print_recommendation_options();
            break;
        case 'shows':
            search_shows();
            break;
        case 'movies':
            search_movies();
            break;
        case 'upcoming':
            print_upcoming_shows();
            break;
        case 'unwatched':
            print_unwatched_episodes();
            break;
        case 'checkin':
            print_checkin_episodes();
            break;
    }
}

echo $w->toxml();

/**
 * Request trakt api
 *
 * @param $url - the url
 * @param $payload - the optional POST body payload
 *
 * @return mixed|object - result object
 */
function request_trakt($url, $payload = null) {
    global $w, $baseurl;
    $url = $baseurl.$url;
    $options = get_post_options($payload);
    _debug('REQUEST URL: '.$url);
    //_debug('REQUEST OPTIONS: '.print_r($options, true));
    $response = $w->request($url, $options);

    if (empty($response)) {
        $w->result('', '', 'Error', 'Trakt returned some invalid data. Please try again.', 'icons/error.png', 'no');
    } else {
        $result = json_decode($response);
        //_debug('REQUEST RESULT: '.print_r($result));
        if (json_last_error() != JSON_ERROR_NONE) {
            // adapt to trakt error objects and set error to curl error output
            $result = (object)array('status' => 'failure', 'error' => $response);
        }
        return $result;
    }
}

/**
 * Get the current movie
 */
function get_movie() {
    global $id, $apikey;
    return request_trakt("movie/summary.json/$apikey/$id");
}

/**
 * Get the current show
 */
function get_show() {
    global $id, $apikey;
    return request_trakt("show/summary.json/$apikey/$id/extended");
}

/**
 * Get the current episode
 */
function get_episode() {
    global $id, $apikey, $season, $episode;
    return request_trakt("show/episode/summary.json/$apikey/$id/$season/$episode");
}

/**
 * Print trending options
 */
function print_trend_options() {
    global $w, $trendsPrefix;
    $w->result('showtrends', '', 'Display trending shows ...', '', 'icons/trend.png', 'no', $trendsPrefix.'shows');
    $w->result('movietrends', '', 'Display trending movies ...', '', 'icons/trend.png', 'no', $trendsPrefix.'movies');
}

/**
 * List all trending movies
 */
function print_movie_trends() {
    global $apikey;
    $movies = request_trakt("movies/trending.json/$apikey");

    if (is_valid($movies)) {
        print_back('movietrends', ' ');
        print_movies($movies);
    }
}

/**
 * List all trending shows
 */
function print_show_trends() {
    global $apikey;
    $shows = request_trakt("shows/trending.json/$apikey");

    if (is_valid($shows)) {
        print_back('showtrends', ' ');
        print_shows($shows);
    }
}

/**
 * Print rating options
 *
 * @param $back - the back target
 * @param $targetPrefix - the target prefix
 */
function print_rating_options($back, $targetPrefix) {
    global $w, $ratingOptions;

    print_back('rate', $back);
    foreach ($ratingOptions as $label => $rating) {
        $w->result('rate', '', $label, '', 'icons/rating.png', 'no', $targetPrefix.$rating);
    }
}

/**
 * Print upcoming shows
 */
function print_upcoming_shows() {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $days = request_trakt("user/calendar/shows.json/$apikey/$username");

        if (is_valid($days)) {
            $cnt = 0;
            foreach ($days as $day) {
                $cnt = $cnt + count($day->episodes);
            }
            if ($cnt === 0) {
                print_info('Add some shows to your collection.', 'No upcoming shows.');
            } else {
                print_count($cnt);
                foreach ($days as $day) {
                    foreach ($day->episodes as $eps) {
                        print_episode($eps->show, $eps->episode);
                    }
                }
            }
        }
    }
}

/**
 * Print unwatched episodes
 */
function print_unwatched_episodes() {
    if (is_authenticated()) {
        $unwatchedEpisodes = get_unwatched_episodes();
        $cnt = count($unwatchedEpisodes);

        if ($cnt === 0) {
            print_info('', 'No unwatched episodes.');
        } else {
            // sort episodes by air date
            usort($unwatchedEpisodes, function ($a, $b) {
                return $a[1]->first_aired > $b[1]->first_aired ? -1 : 1;
            });
            print_count($cnt);
            foreach ($unwatchedEpisodes as $unwatchedEpisode) {
                print_episode($unwatchedEpisode[0], $unwatchedEpisode[1]);
            }
        }
    }
}

/**
 * Print episodes available for checkin
 */
function print_checkin_episodes() {
    global $episodePrefix;

    if (is_authenticated()) {
        $unwatchedEpisodes = get_unwatched_episodes();
        $cnt = count($unwatchedEpisodes);

        if ($cnt === 0) {
            print_info('', 'No unwatched episodes.');
        } else {
            // sort episodes by air date
            usort($unwatchedEpisodes, function ($a, $b) {
                return $a[1]->first_aired > $b[1]->first_aired ? -1 : 1;
            });
            print_count($cnt);
            foreach ($unwatchedEpisodes as $unwatchedEpisode) {
                print_episode($unwatchedEpisode[0], $unwatchedEpisode[1],
                    $episodePrefix.$unwatchedEpisode[0]->imdb_id.':'.$unwatchedEpisode[1]->season.':'.
                    $unwatchedEpisode[1]->number.':checkin');
            }
        }
    }
}

/**
 * Get all unwatched episodes (unsorted)
 *
 * @return array - unwatched episodes
 */
function get_unwatched_episodes() {
    global $apikey, $w;
    $result = array();
    $username = $w->get('username', 'settings.plist');
    $unwatchedEpisodes = request_trakt("user/progress/watched.json/$apikey/$username");

    if (is_valid($unwatchedEpisodes)) {
        $now = new DateTime();
        foreach ($unwatchedEpisodes as $unwatchedEpisode) {
            // skip shows with no next episode as well as episodes that are airing in the future
            if ($unwatchedEpisode->next_episode === false
                || $unwatchedEpisode->next_episode->first_aired > $now->getTimestamp()
            ) {
                continue;
            }
            array_push($result, array($unwatchedEpisode->show, $unwatchedEpisode->next_episode));
        }
    }
    return $result;
}

/**
 * Print options
 *
 * @param $type - the type (episode/show/movie)
 * @param $item - the item to get the user information from
 * @param $back - the back target
 * @param $targetPrefix - the target prefix
 * @param $showWatchlist - show watchlist option (defaults to true)
 * @param $showCollection - show collection option (defaults to true)
 * @param $showWatched - show watched option (defaults to true)
 * @param $showRate - show rate option (defaults to true)
 * @param $showCheckin - show checkin option (defaults to true)
 */
function print_options($type, $item, $back, $targetPrefix, $showWatchlist = true, $showCollection = true,
                       $showWatched = true, $showRate = true, $showCheckin = true) {
    global $w;
    print_back('back', $back);

    if (is_valid($item)) {
        if ($showCheckin) {
            if (is_checked_in($type, $item)) {
                $w->result('cancelcheckin', '', 'Cancel Checkin', '', 'icons/cancelcheckin.png', 'no',
                    $targetPrefix.':cancelcheckin');
            } else {
                $w->result('checkin', '', 'Checkin', '', 'icons/checkin.png', 'no', $targetPrefix.':checkin');
            }
        }
        if ($showWatchlist) {
            if ($item->in_watchlist) {
                $w->result('unwatchlist', '', 'Remove from watchlist', '', 'icons/watchlistremove.png', 'no',
                    $targetPrefix.':unwatchlist');
            } else {
                $w->result('watchlist', '', 'Add to watchlist', '', 'icons/watchlistadd.png', 'no',
                    $targetPrefix.':watchlist');
            }
        }
        if ($showCollection) {
            if ($item->in_collection) {
                $w->result('unlibrary', '', 'Remove from library', '', 'icons/libraryremove.png', 'no',
                    $targetPrefix.':unlibrary');
            } else {
                $w->result('library', '', 'Add to library', '', 'icons/libraryadd.png', 'no', $targetPrefix.':library');
            }
        }
        if ($showWatched) {
            if ($item->watched) {
                $w->result('unseen', '', 'Remove seen flag', '', 'icons/seenremove.png', 'no', $targetPrefix.':unseen');
            } else {
                $w->result('seen', '', 'Mark as seen', '', 'icons/seenadd.png', 'no', $targetPrefix.':seen');
            }
        }
        if ($showRate) {
            if ($item->rating_advanced === 0) {
                $w->result('rate', '', 'Rate', '', 'icons/rating.png', 'no', $targetPrefix.':rate');
            } else {
                $w->result('unrate', '', 'Remove rating', '', 'icons/rating.png', 'no', $targetPrefix.':unrate');
            }
        }
    }
}

/**
 * Print show options
 */
function print_show_options() {
    global $apikey, $showPrefix, $id;
    $show = request_trakt("show/summary.json/$apikey/$id");

    if (is_valid($show)) {
        print_options('show', $show, $showPrefix.$id.':summary', $showPrefix.$id, true, false, false, true, false);
    }
}

/**
 * Print movie options
 */
function print_movie_options() {
    global $apikey, $moviePrefix, $id;
    $movie = request_trakt("movie/summary.json/$apikey/$id");

    if (is_valid($movie)) {
        print_options('movie', $movie, $moviePrefix.$id.':summary', $moviePrefix.$id);
    }
}

/**
 * Print episode options
 */
function print_episode_options() {
    global $apikey, $episodePrefix, $id, $season, $episode;
    $ep = request_trakt("show/episode/summary.json/$apikey/$id/$season/$episode");

    if (is_valid($ep->episode)) {
        print_options('episode', $ep->episode, $episodePrefix.$id.':'.$season.':'.$episode.':summary',
            $episodePrefix.$id.':'.$season.':'.$episode);
    }
}

/**
 * Print watchlist options
 */
function print_watchlist_options() {
    global $w, $watchlistPrefix;
    $w->result('watchlist', '', 'Display your show watchlist ...', '', 'icons/watchlist.png', 'no',
        $watchlistPrefix.'shows');
    $w->result('watchlist', '', 'Display your movie watchlist ...', '', 'icons/watchlist.png', 'no',
        $watchlistPrefix.'movies');
    $w->result('watchlist', '', 'Display your episode watchlist ...', '', 'icons/watchlist.png', 'no',
        $watchlistPrefix.'episodes');
}

/**
 * Print library options
 */
function print_library_options() {
    global $w, $libraryPrefix;
    $w->result('library', '', 'Display watched shows ...', '', 'icons/library.png', 'no',
        $libraryPrefix.'watchedshows');
    $w->result('library', '', 'Display collected shows ...', '', 'icons/library.png', 'no',
        $libraryPrefix.'collectedshows');
    $w->result('library', '', 'Display watched movies ...', '', 'icons/library.png', 'no',
        $libraryPrefix.'watchedmovies');
    $w->result('library', '', 'Display collected movies ...', '', 'icons/library.png', 'no',
        $libraryPrefix.'collectedmovies');
}

/**
 * Print recommendation options
 */
function print_recommendation_options() {
    global $w, $recommendationPrefix;
    $w->result('recommendation', '', 'Display recommended shows ...', '', 'icons/recommendation.png', 'no',
        $recommendationPrefix.'shows');
    $w->result('recommendation', '', 'Display recommended movies ...', '', 'icons/recommendation.png', 'no',
        $recommendationPrefix.'movies');
}

/**
 * Print show recommendations
 */
function print_show_recommendations() {
    global $apikey;

    if (is_authenticated()) {
        $shows = request_trakt("recommendations/shows/$apikey");
        if (is_valid($shows)) {
            print_back('showrecommendation', ' ');
            print_count(count($shows));
            print_shows($shows);
            is_empty('recommendation list');
        }
    }
}

/**
 * Print movie recommendations
 */
function print_movie_recommendations() {
    global $apikey;

    if (is_authenticated()) {
        $shows = request_trakt("recommendations/movies/$apikey");
        if (is_valid($shows)) {
            print_back('movierecommendation', ' ');
            print_count(count($shows));
            print_shows($shows);
            is_empty('recommendation list');
        }
    }
}

/**
 * Print movie watchlist
 */
function print_movie_watchlist() {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $movies = request_trakt("user/watchlist/movies.json/$apikey/$username");

        if (is_valid($movies)) {
            print_back('moviewatchlist', ' ');
            print_count(count($movies));
            print_movies($movies);
            is_empty('watchlist');
        }
    }
}

/**
 * Print movie library
 *
 * @param $apiName - the name of the api (must bei either 'collection' or 'watched')
 */
function print_movie_library($apiName) {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $movies = request_trakt("user/library/movies/$apiName.json/$apikey/$username");

        if (is_valid($movies)) {
            print_back('movielibrary', ' ');
            print_count(count($movies));
            print_movies($movies);
            is_empty('library');
        }
    }
}

/**
 * Print movie watchlist
 */
function print_show_watchlist() {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $shows = request_trakt("user/watchlist/shows.json/$apikey/$username");

        if (is_valid($shows)) {
            print_back('showwatchlist', ' ');
            print_count(count($shows));
            print_shows($shows);
            is_empty('watchlist');
        }
    }
}

/**
 * Print movie watchlist
 *
 * @param $apiName - the name of the api (must bei either 'collection' or 'watched')
 */
function print_show_library($apiName) {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $shows = request_trakt("user/library/shows/$apiName.json/$apikey/$username");

        if (is_valid($shows)) {
            print_back('showlibrary', ' ');
            print_count(count($shows));
            print_shows($shows);
            is_empty('library');
        }
    }
}

/**
 * Print episode watchlist
 */
function print_episode_watchlist() {
    global $apikey, $w;

    if (is_authenticated()) {
        $username = $w->get('username', 'settings.plist');
        $eps = request_trakt("user/watchlist/episodes.json/$apikey/$username");

        if (is_valid($eps)) {
            print_back('episodewatchlist', ' ');
            print_count(count($eps));
            print_episodes($eps);
            is_empty('watchlist');
        }
    }
}

/**
 * Search for shows
 */
function search_shows() {
    global $apikey, $query;
    $shows = request_trakt("search/shows.json/$apikey?query=$query");

    if (is_valid($shows)) {
        print_count(count($shows));
        print_shows($shows);
        is_empty_search();
    }
}

/**
 * Search for movies
 */
function search_movies() {
    global $apikey, $query;
    $movies = request_trakt("search/movies.json/$apikey?query=$query");

    if (is_valid($movies)) {
        print_count(count($movies));
        print_movies($movies);
        is_empty_search();
    }
}

/**
 * Print a show summary
 */
function print_show_summary() {
    global $w, $id, $showPrefix, $episodePrefix;
    $show = get_show();

    if (is_valid($show)) {
        $count = count_episodes($show);
        $maincast = get_main_cast($show);
        $latestEp = get_latest_episode($show);

        $title = $show->title;
        // add year only if the titles doesn't already end with a year (just a simple/lazy check for closing parenthesis)
        if (!(strcmp(substr($title, strlen($title) - 1), ')') === 0)) {
            $title = $title.' ('.$show->year;
        } else {
            // remove trailing parenthesis
            $title = rtrim($title, ')');
        }
        // add end year in case the show ended
        if (isset($latestEp) && $show->status === 'Ended') {
            $title = $title.'-'.date('Y', $latestEp->first_aired);
        } else {
            // no end year available, just add a hyphen and space to indicate progress
            $title = $title.'- ';
        }
        // close parenthesis in any case
        $title = $title.')';

        $w->result('summary', '', $title,
            handle_multiple_information(array('Runtime' => $show->runtime, 'Rating' => $show->ratings->percentage,
                'First Aired' => date('Y-m-d', $show->first_aired))), 'icon.png', 'no');
        if (isset($latestEp)) {
            $w->result('epguide', '',
                'Latest Episode: '.$latestEp->season.'x'.sprintf('%02d', $latestEp->episode).': '.$latestEp->title,
                handle_multiple_information(array('Air Date' => date('Y-m-d', $latestEp->first_aired),
                    'Rating' => $latestEp->ratings->percentage)), 'icons/date.png', 'no',
                $episodePrefix.$id.':'.$latestEp->season.':'.$latestEp->episode.':summary');
        }
        if (!empty($show->certification)) {
            $w->result('certification', '', $show->certification, 'Certification', 'icons/certification.png', 'no');
        }
        if ($count[0] > 0) {
            $specials = '';
            if ($count[1] > 0) {
                $specials = ' (Plus '.$count[1].' Special Episodes)';
            }
            $w->result('summary', '', 'Show Episode List ...', 'Total Episodes: '.$count[0].$specials,
                'icons/episodes.png', 'no', $showPrefix.$id.':epguide');
        }
        if (isset($maincast)) {
            $w->result('summary', '', 'Show Cast ...', $maincast.', ...', 'icons/cast.png', 'no',
                $showPrefix.$id.':cast');
        }
        if (is_authenticated()) {
            $w->result('summary', '', 'Show Options ...', 'Watchlist/Rate', 'icons/options.png', 'no',
                $showPrefix.$id.':options');
        }
        $w->result('summary', '',
            handle_multiple_information(array('Network' => $show->network, 'Status' => $show->status)),
            handle_multiple_information(array('Air Day' => $show->air_day, 'Air Time' => $show->air_time)),
            'icons/network.png', 'no');
        $w->result('summary', '',
            $show->stats->watchers.' Watchers, '.$show->stats->plays.' Plays, '.$show->stats->scrobbles.' Scrobbles',
            'Stats', 'icons/stats.png', 'no');
        $w->result('summary', $show->url, 'View on trakt.tv', '', 'icons/external.png');
        $w->result('summary', 'http://www.imdb.com/title/'.$id, 'View on IMDB', '', 'icons/external.png');
        $w->result('summary',
            'https://www.youtube.com/results?search_query='.str_replace(' ', '+', $show->title.' trailer'),
            'Search for a trailer on YouTube', '', 'icons/external.png');
    }
}

/**
 * Print a movie summary
 */
function print_movie_summary() {
    global $w, $moviePrefix;
    $movie = get_movie();

    if (is_valid($movie)) {
        $maincast = get_main_cast($movie);
        $w->result('summary', '', $movie->title.' ('.$movie->year.')',
            handle_multiple_information(array('Runtime' => $movie->runtime, 'Rating' => $movie->ratings->percentage,
                'Genres' => implode(', ', $movie->genres))), 'icon.png', 'no');
        if (!empty($movie->released)) {
            $w->result('summary', '', date('Y-m-d', $movie->released), 'Release Date', 'icons/date.png', 'no');
        }
        if (!empty($movie->certification)) {
            $w->result('summary', '', $movie->certification, 'Certification', 'icons/certification.png', 'no');
        }
        if (isset($maincast)) {
            $w->result('summary', '', 'Show Cast ...', $maincast.', ...', 'icons/cast.png', 'no',
                $moviePrefix.$movie->imdb_id.':cast');
        }
        if (is_authenticated()) {
            $w->result('summary', '', 'Show Options ...', 'Checkin/Watchlist/Library/Seen/Rate', 'icons/options.png',
                'no', $moviePrefix.$movie->imdb_id.':options');
        }
        $w->result('summary', '',
            $movie->stats->watchers.' Watchers, '.$movie->stats->plays.' Plays, '.$movie->stats->scrobbles.' Scrobbles',
            'Stats', 'icons/stats.png', 'no');
        $w->result('summary', $movie->url, 'View on trakt.tv', '', 'icons/external.png');
        $w->result('summary', "http://www.imdb.com/title/$movie->imdb_id", 'View on IMDB', '', 'icons/external.png');
        if (!empty($movie->trailer)) {
            $w->result('summary', $movie->trailer, 'Watch trailer on YouTube', '', 'icons/external.png');
        }
    }
}

/**
 * Print an episode summary
 */
function print_episode_summary() {
    global $w, $id, $episodePrefix, $showPrefix, $season, $episode;
    $ep = get_episode();

    if (is_valid($ep)) {
        print_back('summary', $showPrefix.$id.':epguide');
        $w->result('summary', '',
            $ep->episode->season.'x'.sprintf('%02d', $ep->episode->number).': '.$ep->episode->title.' ('.
            date('Y', $ep->episode->first_aired).')', handle_multiple_information(array('Show' => $ep->show->title,
                'Rating' => $ep->episode->ratings->percentage)), 'icon.png', 'no');
        $w->result('summary', '', date('Y-m-d', $ep->episode->first_aired), 'Air Date', 'icons/date.png', 'no');
        if (!empty($ep->episode->overview)) {
            $w->result('summary', '', $ep->episode->overview, 'Overview', 'icons/info.png', 'no');
        }
        if (is_authenticated()) {
            $w->result('summary', '', 'Show Options ...', 'Checkin/Watchlist/Library/Seen/Rate', 'icons/options.png',
                'no', $episodePrefix.$id.':'.$season.':'.$episode.':options');
            $w->result('summary', '', $ep->episode->plays, 'Personal Plays', 'icons/stats.png', 'no');
        }
        $w->result('summary', $ep->episode->url, 'View on trakt.tv', '', 'icons/external.png');
        $w->result('summary', 'http://www.imdb.com/title/'.$ep->episode->imdb_id, 'View on IMDB', '',
            'icons/external.png');
    }
}

/**
 * Handle the show option
 *
 * @param $apiName - must be one of [show]/[seen/unseen/watchlist/unwatchlist/library/unlibrary]
 */
function handle_show_option($apiName) {
    global $showPrefix, $id;
    $show = get_show();
    $item =
        array('imdb_id' => $show->imdb_id, 'tvdb_id' => $show->tvdb_id, 'title' => $show->title, 'year' => $show->year);
    $additional = array('shows' => array($item));
    handle_option($apiName, $showPrefix.$id.':summary', $additional);
}

/**
 * Handle the movie option
 *
 * @param $apiName - must be one of [movie]/[seen/unseen/watchlist/unwatchlist/library/unlibrary]
 */
function handle_movie_option($apiName) {
    global $moviePrefix, $id;
    $movie = get_movie();
    $item = array('imdb_id' => $movie->imdb_id, 'title' => $movie->title, 'year' => $movie->year);
    $additional = array('movies' => array($item));
    handle_option($apiName, $moviePrefix.$id.':options', $additional);
}

/**
 * Handle the episode option
 *
 * @param $apiName - must be one of [show/episode]/[seen/unseen/watchlist/unwatchlist/library/unlibrary]
 */
function handle_episode_option($apiName) {
    global $episodePrefix, $id, $season, $episode, $apikey, $w;
    $ep = get_episode();
    $item = array('season' => $season, 'episode' => $episode);
    $additional = array('imdb_id' => $ep->show->imdb_id, 'tvdb_id' => $ep->show->tvdb_id, 'title' => $ep->show->title,
        'year' => $ep->show->year, 'episodes' => array($item));
    $result = request_trakt("$apiName/$apikey", $additional);

    print_back($apiName, $episodePrefix.$id.':'.$season.':'.$episode.':summary');
    if (is_valid($result)) {
        $w->result($apiName, '', get_ok_message(array('episode', explode('/', $apiName)[2])), '', 'icon.png', 'no',
            $episodePrefix.$id.':'.$season.':'.$episode.':summary');
    }
}

/**
 * Handle a specified show or movie. Supported actions: (un)watchlist, (un)library, (un)seen
 *
 * @param $apiName - must be one of [movie/show]/[seen/unseen/watchlist/unwatchlist/library/unlibrary]
 * @param $target - the target
 * @param $additional - POST array
 */
function handle_option($apiName, $target, $additional) {
    global $apikey, $w;
    $result = request_trakt("$apiName/$apikey", $additional);

    print_back($apiName, $target);
    if (is_valid($result)) {
        $w->result($apiName, '', get_ok_message(explode('/', $apiName)), '', 'icon.png', 'no', $target);
    }
}

/**
 * Handle a rating. Supported types: movie, show, episode
 *
 * @param $type - must be one of [movie/show/episode]
 * @param $target - the target
 */
function handle_rating($type, $target) {
    global $apikey, $w, $rating, $season, $episode;
    $additional = null;

    switch ($type) {
        case 'movie':
            $movie = get_movie();
            $additional = array('imdb_id' => $movie->imdb_id, 'tvdb_id' => $movie->tvdb_id, 'title' => $movie->title,
                'year' => $movie->year, 'rating' => $rating);
            break;
        case 'show':
            $show = get_show();
            $additional = array('imdb_id' => $show->imdb_id, 'tvdb_id' => $show->tvdb_id, 'title' => $show->title,
                'year' => $show->year, 'rating' => $rating);
            break;
        case 'episode':
            $show = get_show();
            $additional = array('imdb_id' => $show->imdb_id, 'tvdb_id' => $show->tvdb_id, 'title' => $show->title,
                'year' => $show->year, 'season' => $season, 'episode' => $episode, 'rating' => $rating);
            break;
    }

    $result = request_trakt("rate/$type/$apikey", $additional);
    print_back($type, $target);

    if (is_valid($result)) {
        $w->result($type, '', get_ok_message(array($type, $rating > 0 ? 'rate' : 'unrate')), '', 'icon.png', 'no',
            $target);
    }
}

/**
 * Check if the specified item is checked in
 *
 * @param $type - must be one of [movie/episode]
 * @param $item - the item to check
 *
 * @return true in case the item is checked in, false otheriwse
 */
function is_checked_in($type, $item) {
    global $apikey, $w;
    $username = $w->get('username', 'settings.plist');
    $result = request_trakt("user/watching.json/$apikey/$username");
    $checked_in = false;

    if (is_valid($result)) {
        switch ($result->type) {
            case 'movie':
                if ($type === 'movie' && $result->movie->title === $item->title) {
                    $checked_in = true;
                }
                break;
            case 'episode':
                if ($type === 'episode' && $result->episode->title === $item->title
                    && $result->episode->season === $item->season
                    && $result->episode->number === $item->number
                ) {
                    $checked_in = true;
                }
                break;
        }
    }
    return $checked_in;
}

/**
 * Handle a checkin. Supported types: movie, show
 *
 * @param $type - must be one of [movie/show]
 * @param $target - the target
 */
function handle_checkin($type, $target) {
    global $apikey, $w, $season, $episode;
    $additional = null;

    switch ($type) {
        case 'movie':
            $movie = get_movie();
            $additional = array('imdb_id' => $movie->imdb_id, 'tvdb_id' => $movie->tvdb_id, 'title' => $movie->title,
                'year' => $movie->year);
            break;
        case 'show':
            $show = get_show();
            $additional = array('imdb_id' => $show->imdb_id, 'tvdb_id' => $show->tvdb_id, 'title' => $show->title,
                'year' => $show->year, 'season' => $season, 'episode' => $episode);
            break;
    }

    $result = request_trakt("$type/checkin/$apikey", $additional);
    print_back($type, $target);

    if (is_valid($result)) {
        $w->result($type, '', get_ok_message(array($type === 'show' ? 'episode' : $type, 'checkin')), '', 'icon.png',
            'no', $target);
    }
}

/**
 * Handle a cancelcheckin. Supported types: movie, show
 *
 * @param $type - must be one of [movie/show]
 * @param $target - the target
 */
function handle_cancelcheckin($type, $target) {
    global $apikey, $w;
    $result = request_trakt("$type/cancelcheckin/$apikey");
    print_back($type, $target);

    if (is_valid($result)) {
        $w->result($type, '', get_ok_message(array($type, 'cancelcheckin')), '', 'icon.png', 'no', $target);
    }
}

/**
 * Get the message for the specified message array
 *
 * @param $msgArray - the message array (1st element: name, 2nd element: operation)
 *
 * @return string - the ok messsage
 */
function get_ok_message($msgArray) {
    switch ($msgArray[1]) {
        case 'watchlist':
            return 'The '.$msgArray[0].' has been added to your watchlist!';
        case 'unwatchlist':
            return 'The '.$msgArray[0].' has been removed from your watchlist!';
        case 'seen':
            return 'The '.$msgArray[0].' has been marked as seen!';
        case 'unseen':
            return 'The '.$msgArray[0].' has been marked as unseen!';
        case 'library':
            return 'The '.$msgArray[0].' has been added to your library!';
        case 'unlibrary':
            return 'The '.$msgArray[0].' has been removed from your library!';
        case 'rate':
            return 'The '.$msgArray[0].' has been rated!';
        case 'unrate':
            return 'The rating for the '.$msgArray[0].' has been removed!';
        case 'checkin':
            return 'The '.$msgArray[0].' has been checked in!';
        case 'cancelcheckin':
            return 'The checkin for the '.$msgArray[0].' has been removed!';
    }
}

/**
 * Show the epguide of the current show
 */
function print_show_epguide() {
    global $apikey, $w, $id, $showPrefix, $episodePrefix;
    $show = request_trakt("show/summary.json/$apikey/$id/extended");

    if (is_valid($show)) {
        print_back('epguide', $showPrefix.$id.':summary');
        foreach ($show->seasons as $season) {
            foreach ($season->episodes as $ep) {
                $w->result('epguide', '', $season->season.'x'.sprintf('%02d', $ep->episode).': '.$ep->title,
                    handle_multiple_information(array('Show' => $show->title,
                        'Air Date' => date('Y-m-d', $ep->first_aired), 'Rating' => $ep->ratings->percentage)),
                    'icons/episode.png', 'no', $episodePrefix.$id.':'.$ep->season.':'.$ep->episode.':summary');
            }
        }
    }
    $w->sortresults('title', false);
}

/**
 * Print the show cast
 */
function print_show_cast() {
    global $apikey, $w, $id, $showPrefix;
    $show = request_trakt("show/summary.json/$apikey/$id/extended");

    if (is_valid($show)) {
        print_back('cast', $showPrefix.$id.':summary');
        foreach ($show->people->actors as $actor) {
            if (!empty($actor->character) && !empty($actor->name)) {
                $w->result('cast', '', $actor->character, $actor->name, 'icons/actor.png', 'no');
            }
        }
    }
}

/**
 * Print the movie cast
 */
function print_movie_cast() {
    global $apikey, $w, $id, $moviePrefix;
    $movie = request_trakt("movie/summary.json/$apikey/$id");

    if (is_valid($movie)) {
        print_back('cast', $moviePrefix.$id.':summary');
        foreach ($movie->people->actors as $actor) {
            if (!empty($actor->character) && !empty($actor->name)) {
                $w->result('cast', '', $actor->character, $actor->name, 'icons/actor.png', 'no');
            }
        }
        foreach ($movie->people->directors as $director) {
            $w->result('cast', '', $director->name, 'Director', 'icons/othercast.png', 'no');
        }
        foreach ($movie->people->writers as $writer) {
            $w->result('cast', '', $writer->name, 'Writer', 'icons/othercast.png', 'no');
        }
        foreach ($movie->people->producers as $producer) {
            $w->result('cast', '', $producer->name, 'Producer', 'icons/othercast.png', 'no');
        }
    }
}

/**
 * Print a count with an optional message (only if count is not 0)
 *
 * @param $count - the count
 * @param $msg - the optional message (default: 'Total')
 */
function print_count($count, $msg = 'Total') {
    if ($count > 0) {
        print_info('', $msg.': '.$count);
    }
}

/**
 * Print the back line
 *
 * @param $uid - the uid
 * @param $target - the target
 */
function print_back($uid, $target) {
    global $w;
    $w->result($uid, '', 'Back ...', '', 'icons/back.png', 'no', $target);
}

/**
 * Print the specified movies
 *
 * @param $movies - the movies
 */
function print_movies($movies) {
    global $w, $moviePrefix;
    foreach ($movies as $movie) {
        $w->result('movie', '', $movie->title,
            handle_multiple_information(array('Rating' => $movie->ratings->percentage, 'Year' => $movie->year,
                'Genres' => implode(', ', $movie->genres))), 'icon.png', 'no', $moviePrefix.$movie->imdb_id.':summary');
    }
}

/**
 * Print the specified shows
 *
 * @param $shows - the shows
 */
function print_shows($shows) {
    global $w, $showPrefix;
    foreach ($shows as $show) {
        $w->result('show', '', $show->title,
            handle_multiple_information(array('Rating' => $show->ratings->percentage, 'Year' => $show->year,
                'Network' => $show->network, 'Genres' => implode(', ', $show->genres))), 'icon.png', 'no',
            $showPrefix.$show->imdb_id.':summary');
    }
}

/**
 * Print the specified episodes
 *
 * @param $shows - the show wrappers
 */
function print_episodes($shows) {
    foreach ($shows as $show) {
        foreach ($show->episodes as $ep) {
            print_episode($show, $ep);
        }
    }
}

/**
 * Print the specified episode
 *
 * @param $show - the show
 * @param $ep - the episode
 * @param $target - a custom target
 */
function print_episode($show, $ep, $target = null) {
    global $w, $episodePrefix;
    $w->result('episode', '', $ep->season.'x'.sprintf('%02d', $ep->number).': '.$ep->title,
        handle_multiple_information(array('Show' => $show->title, 'Air Date' => date('Y-m-d', $ep->first_aired),
            'Rating' => $ep->ratings->percentage)), 'icon.png', 'no',
        !empty($target) ? $target : $episodePrefix.$show->imdb_id.':'.$ep->season.':'.$ep->number.':summary');
}

/**
 * Get a list of top 2 cast for the specified item (may be a show or a movie)
 *
 * @param $item - the item
 *
 * @return string - the top cast
 */
function get_main_cast($item) {
    $result = array();
    $cnt = 0;

    foreach ($item->people->actors as $actor) {
        if ($cnt < 2) {
            if (!empty($actor->character) && !empty($actor->name)) {
                array_push($result, $actor->character.' ('.$actor->name.')');
                $cnt++;
            }
        }
    }

    if (!empty($result)) {
        return implode(', ', $result);
    }
}

/**
 * Count episodes of the specified show
 *
 * @param $show - the show
 */
function count_episodes($show) {
    $counts = array();
    $normalCnt = 0;
    $specialCnt = 0;

    foreach ($show->seasons as $season) {
        if ($season->season > 0) {
            $normalCnt = $normalCnt + count($season->episodes);
        } else {
            $specialCnt = $specialCnt = count($season->episodes);
        }
    }
    array_push($counts, $normalCnt);
    array_push($counts, $specialCnt);
    return $counts;
}

/**
 * Find the latest episode for the specified show
 *
 * @param $show - the show
 *
 * @return object - the latest episode
 */
function get_latest_episode($show) {
    $today = new DateTime("now");
    $latestEpisode = null;
    $diff = 2147483647;

    foreach ($show->seasons as $season) {
        if ($season->season > 0) {
            foreach ($season->episodes as $episode) {
                if (!isset($episode->first_aired_iso)) {
                    continue;
                }
                $epdate = new DateTime(explode('T', $episode->first_aired_iso)[0]);
                $interval = $today->diff($epdate);
                // only continue if interval is negative (in the past)
                if ($interval->invert === 1 && $interval->days <= $diff) {
                    $diff = $interval->days;
                    $latestEpisode = $episode;
                }
            }
        }
    }
    if (!empty($latestEpisode)) {
        return $latestEpisode;
    }
}

/**
 * Handle multiple information, skip empty info etc.
 *
 * @param $infos - array of information key/value pairs
 *
 * @return array - updated infos array
 */
function handle_multiple_information($infos) {
    $separator = ', ';
    $result = '';
    foreach ($infos as $key => $value) {
        if (isset($value) && !empty($value)) {
            $result = $result.$separator;
            $result = $result.$key.': '.$value;

            // handle eventual suffixes
            if ($key === 'Rating') {
                $result = $result.'%';
            } else if ($key === 'Runtime') {
                $result = $result.'min';
            }
        }
    }
    return trim($result, $separator);
}

/**
 * Get the post options. Returns array with some POST options and the username and password if not empty.
 * Otherwise an empty array will be returned.
 *
 * @param $payload - the optional POST body payload
 *
 * @return array - the post options
 */
function get_post_options($payload = null) {
    global $w;
    $username = $w->get('username', 'settings.plist');
    $password = $w->get('password', 'settings.plist');

    if (empty($username) || empty($password)) {
        return array();
    }
    $options = array(
        CURLOPT_POST => 1,
        CURLOPT_USERPWD => "$username:$password",
        CURLOPT_TIMEOUT => 30,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => json_encode($payload)
    );
    return $options;
}

/**
 * Get authentication status.
 * @return true in case the user has set its username and password (no validation!)
 */
function is_authenticated() {
    global $w;
    $username = $w->get('username', 'settings.plist');
    $password = $w->get('password', 'settings.plist');

    if (empty($username) || empty($password)) {
        print_error('Please set your username and password correctly.');
        return false;
    }
    return true;
}

/**
 * Print an error message
 *
 * @param $message - the message
 * @param $title - the optional title (defaults to 'Error')
 */
function print_error($message, $title = 'Error') {
    global $w;
    $w->result('error', '', $title, $message, 'icons/error.png', 'no');
}

/**
 * Print an info message
 *
 * @param $message - the message
 * @param $title - the optional title (defaults to 'Info')
 */
function print_info($message, $title = 'Info') {
    global $w;
    $w->result('info', '', $title, $message, 'icons/info.png', 'no');
}

/**
 * Check if the specified api response is valid.
 *
 * @param $response - the object that should be checked
 *
 * @return bool - true in case the response is valid, false otherwise
 */
function is_valid($response) {
    if (isset($response->status) && $response->status === 'failure') {
        print_error($response->error);
        return false;
    }
    return true;
}


/**
 * Check if search was empty. If so -> print message
 */
function is_empty_search() {
    global $w;
    if (count($w->results()) === 0) {
        print_info('Please widen your search.', 'No results.');
    }
}


/**
 * Check if a result set is empty. If so -> print message
 *
 * @param $what - some item that could be empty, used in the result string
 */
function is_empty($what) {
    global $w;
    if (count($w->results()) === 1) {
        print_info('', 'Your '.$what.' is empty.', 'Please add some items.');
    }
}

/**
 * Log something to a file.
 *
 * @param $what - object to log
 */
function _debug($what) {
    global $w, $debugEnabled;
    if ($debugEnabled) {
        $fileName = 'debug.log';
        $w->write(date('Y-m-d G:i:s').' -- ', $fileName, FILE_APPEND);
        $w->write($what, $fileName, FILE_APPEND);
        $w->write(PHP_EOL, $fileName, FILE_APPEND);
    }
}