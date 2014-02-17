# Trakt for Alfred
*Trakt for Alfred* is a workflow for [Alfred v2] that enables you interact with [trakt.tv]. 

### Features
#### General Features
 - Display trending movies
 - Display trending shows
 - Display show watchlist (if user authenticated correctly)
 - Display movie watchlist (if user authenticated correctly)
 - Display version number
 - Store API key, username, password-hash
 - Test currently stored credentials

#### Show Features
 - Display summary
    - Basic info
    - Latest episode
    - Certification (e.g. 'TV-MA')
    - Network info
    - Search trailer
    - trakt.tv Stats
    - View on IMDB
    - View on trakt.tv
    - Add/Remove to/from watchlist (if user is authenticated correctly)
 - Display list of actors
 - Display episode list (including special episodes)

#### Movie Features
 - Display summary
    - Basic info
    - Release date
    - Certification (e.g. 'R')
    - View trailer in YouTube
    - trakt.tv Stats
    - View on IMDB
    - View on trakt.tv
    - Add/Remove to/from watchlist (if user is authenticated correctly)
 - Display list of actors, directors, producers, writers

### Notes
 - You need a trakt.tv account for this workflow. 
 - You need to store your [API key from trakt.tv] and your credentials (username/password).
 - Your password will be stored as SHA1 hash. It is not stored in plain text!
 - As soon as OAuth authentication is available it will replace the current authentication approach.

### Command Overview
![][screenshot_commands]

### Setup Commands
 - `trakt-apikey <your-api-key-here>` will store your API key
 - `trakt-username <your-username-here>` will store your username
 - `trakt-password <your-password-here>` will store your password as SHA1 hash
 - `trakt-testauth` will check the currently stored credentials
 - `trakt-version` will show the current version

### General Commands
 - `trakt-trends` will show the current trending options
 - `trakt-watchlists` will show the current watchlist options
 - `trakt-shows breaking` will search for shows containing the name *breaking*
 - `trakt-movies thor` will search for movies containing the name *thor*

### Version
1.3

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

##### Search Shows
![Search][screenshot_search_shows]

##### Search Movies
![Search][screenshot_search_movies]

##### Summary Show
![Summary][screenshot_summary_show]

##### Summary Movie
![Summary][screenshot_summary_movie]

##### Episode Guide
![Episode Guide][screenshot_epguide]

##### Cast Members Show
![Cast][screenshot_cast_show]

##### Cast Members Movie
![Cast][screenshot_cast_movie]

##### Workflow
![Workflow][screenshot_workflow]

### Thanks
 - [David Ferguson] for his wonderful starting-point [Workflows]
 - [@iconmonstr] for the beautiful [icons]

[Alfred v2]:http://www.alfredapp.com/
[API key from trakt.tv]:http://trakt.tv/settings/api
[trakt.tv]:http://trakt.tv/
[Profile -> Settings -> API]:http://trakt.tv/settings/api
[David Ferguson]:http://dferg.us/workflows-class/
[Workflows]:https://github.com/jdfwarrior/Workflows
[@iconmonstr]:https://twitter.com/iconmonstr
[icons]:http://iconmonstr.com/
[screenshot_commands]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/commands.png
[screenshot_summary_show]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary_show.png
[screenshot_summary_movie]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary_movie.png
[screenshot_search_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/search_shows.png
[screenshot_search_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/search_movies.png
[screenshot_trends]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends.png
[screenshot_trends_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends_shows.png
[screenshot_trends_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends_movies.png
[screenshot_watchlists]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlists.png
[screenshot_watchlist_shows]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlist_shows.png
[screenshot_watchlist_movies]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/watchlist_movies.png
[screenshot_epguide]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/epguide.png
[screenshot_cast_show]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/cast_show.png
[screenshot_cast_movie]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/cast_movie.png
[screenshot_workflow]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/workflow.png
