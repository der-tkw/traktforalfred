# Trakt for Alfred
*Trakt for Alfred* is a workflow for [Alfred v2] that enables you to list trending shows and search for shows on [trakt.tv]. 

### Features
 - Display currently trending shows
 - Search for specific tv shows
 - Display show summary (basic stuff, latest episode, network info, stats, a few links etc.)
 - Display list of cast members 
 - Display a complete episode guide
 - Navigate between summary, cast members and episode list

### Notes
 - You need a trakt.tv account for this workflow. Get your personal [API key from trakt.tv] and register it with the following command: `apikey <your-api-key-here>`. Otherwise this workflow will not function.
 - This workflow currently only covers TV Shows from [trakt.tv]. Movies are not supported at the moment.

### Usage
 - `trakt-trends` will show the current trending shows
 - `trakt-shows breaking` will search for shows containing the name *breaking*
 - `trakt-version` will show the current version

Each command will present a list of shows to you. Now you have the possibility to select a show and navigate between its summary, a cast list and a complete episode guide.

### Version
1.0

### Screenshots
##### Trends
![Trends][screenshot_trends]

##### Search
![Search][screenshot_search]

##### Summary
![Summary][screenshot_summary]

##### Episode Guide
![Episode Guide][screenshot_epguide]

##### Cast Members
![Cast][screenshot_cast]

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
[screenshot_summary]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/summary.png
[screenshot_search]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/search.png
[screenshot_trends]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/trends.png
[screenshot_epguide]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/epguide.png
[screenshot_cast]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/cast.png
[screenshot_workflow]:https://dl.dropboxusercontent.com/u/2188000/traktforalfred/workflow.png
