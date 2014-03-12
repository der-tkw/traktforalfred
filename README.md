# Trakt for Alfred
*Trakt for Alfred* is a workflow for [Alfred v2] that allows you to search for movies and tv shows on [trakt.tv], check out current trends and manage your personal libraries. 

### Features
#### General Features
 - Display upcoming shows of the next 7 days
 - Display trends (shows and movies)
 - Display recommendations (shows and movies)
 - Display watchlists (shows, movies and episodes)
 - Display your libraries (collected/watched shows and movies)
 - Display version number
 - Store API key, username, password-hash
 - Test currently stored credentials

#### Show Features
 - Summary
    - Basic info
    - Latest episode
    - Certification (e.g. 'TV-MA')
    - Network info
    - trakt.tv Stats
 - Add/Remove to/from watchlist
 - Rate/Unrate
 - Search trailer on YouTube
 - View on IMDB
 - View on trakt.tv
 - Display list of actors
 - Display episode list (including special episodes)

#### Episode Features
 - Summary
    - Basic info
    - Overview
    - Personal play count
 - View on IMDB
 - View on trakt.tv
 - Mark/Unmark as seen
 - Add/Remove to/from watchlist
 - Add/Remove to/from library
 - Rate/Unrate

#### Movie Features
 - Summary
    - Basic info
    - Release date
    - Certification (e.g. 'R')
    - trakt.tv Stats
 - View trailer on YouTube
 - View on IMDB
 - View on trakt.tv
 - Display list of actors, directors, producers, writers
 - Mark/Unmark as seen
 - Add/Remove to/from watchlist
 - Add/Remove to/from library
 - Rate/Unrate

### Notes
 - You need a trakt.tv account for this workflow to work completely (some keywords will work partly/without authentication).
 - You need to store your [API key from trakt.tv] and your credentials (username/password).
 - Your password will be stored as SHA1 hash. It is not stored in plain text!
 - As soon as OAuth authentication is available it will replace the current authentication approach.

### Setup Commands
 - `trakt-apikey <your-api-key-here>` will store your API key
 - `trakt-username <your-username-here>` will store your username
 - `trakt-password <your-password-here>` will store your password as SHA1 hash
 - `trakt-testauth` will validate the currently stored credentials
 - `trakt-version` will show the current version

### General Commands
 - `trakt-upcoming` will display the upcoming shows of the next 7 days
 - `trakt-trends` will show the current trending options
 - `trakt-recommendations` will show the current recommendation options
 - `trakt-watchlists` will show the current watchlist options
 - `trakt-libraries` will show the current watchlist options
 - `trakt-shows breaking` will search for shows containing the name *breaking*
 - `trakt-movies thor` will search for movies containing the name *thor*

### Version
1.5 beta

### Thanks
 - [David Ferguson] for creating [Workflows]
 - [@iconmonstr] for the [icons]

### License
Copyright (c) 2013-2014 Tim Weller. See the LICENSE file for license rights and limitations (MIT).

### Screenshots
##### Trends
![Trends][screenshot_trends]

##### Trends Shows
![Trends][screenshot_trends_shows]

##### Trends Movies
![Trends][screenshot_trends_movies]

##### Watchlists
![Trends][screenshot_watchlists]

##### Watchlist Shows
![Trends][screenshot_watchlist_shows]

##### Watchlist Movies
![Trends][screenshot_watchlist_movies]

##### Watchlist Episodes (empty)
![Trends][screenshot_watchlist_episodes]

##### Libraries
![Trends][screenshot_libraries]

##### Libraries Watched Shows
![Trends][screenshot_libraries_watchedshows]

##### Search Shows
![Search][screenshot_search_shows]

##### Search Movies
![Search][screenshot_search_movies]

##### Summary Show
![Summary][screenshot_summary_show]

##### Summary Episode
![Summary][screenshot_summary_episode]

##### Summary Movie
![Summary][screenshot_summary_movie]

##### Episode Guide
![Episode Guide][screenshot_epguide]

##### Cast Members Show
![Cast][screenshot_cast_show]

##### Cast Members Movie
![Cast][screenshot_cast_movie]

##### Options Movie
![Cast][screenshot_options_movie]

##### Options Episode
![Cast][screenshot_options_episode]

##### Workflow
![Workflow][screenshot_workflow]

[Alfred v2]:http://www.alfredapp.com/
[API key from trakt.tv]:http://trakt.tv/settings/api
[trakt.tv]:http://trakt.tv/
[Profile -> Settings -> API]:http://trakt.tv/settings/api
[David Ferguson]:http://dferg.us/
[Workflows]:https://github.com/jdfwarrior/Workflows
[@iconmonstr]:https://twitter.com/iconmonstr
[icons]:http://iconmonstr.com/
[screenshot_commands]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/commands.png
[screenshot_summary_show]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary_show.png
[screenshot_summary_movie]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary_movie.png
[screenshot_summary_episode]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary_episode.png
[screenshot_search_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/search_shows.png
[screenshot_search_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/search_movies.png
[screenshot_trends]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends.png
[screenshot_trends_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends_shows.png
[screenshot_trends_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends_movies.png
[screenshot_watchlists]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlists.png
[screenshot_watchlist_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlist_shows.png
[screenshot_watchlist_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlist_movies.png
[screenshot_watchlist_episodes]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlist_episodes.png
[screenshot_epguide]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/epguide.png
[screenshot_cast_show]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/cast_show.png
[screenshot_cast_movie]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/cast_movie.png
[screenshot_workflow]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/workflow.png
[screenshot_options_movie]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/options_movie.png
[screenshot_options_episode]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/options_episode.png
[screenshot_libraries]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/libraries.png
[screenshot_libraries_watchedshows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/libraries_watchedshows.png
