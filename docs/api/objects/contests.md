### Contests

Contests are the core object created by companies, that users can then submit too.

### Table Fields

* id
* owner                 | ID of the company
* title                 * deprecated
* time_length
* start_time
* stop_time
* submission_limit    
* prize                 | Prize to pay out to user
* objective             | Objective of the ad campaign
* platform              | Social platform the contest is usingg
* age                   * deprecated
* gender                * deprecated
* location              * deprecated
* audience
* different
* summary               
* additional_images     | Array of extra images
* paid                  | If the contest has been paid for
* industry              | The industry the company is in
* emotion               * Deprecated
* display_type          | Type of submissions it needs [with_photo or text_only]
* created_at            
* min_age               | Min age required to submit
* max_age               | Max age that can submit
* website_clicks        | # of clicks on website link
* facebook_clicks       | # of clicks on facebook link
* twitter_clicks        | # of clicks on twitter link
* status                | If the contest is running [ACTIVE or PAUSED]

### Methods

#### index($type = 'all')

Fetch all contests. If $type is supplied, we only find contests for that given industry

#### show($cid)

Get a single contest, or throw error if it doesnt exist

#### create()

Create a contest.

#### update($cid)

Update the specified contest  

#### delete($cid)

Delete the specified contest

#### select_winner($cid)

Choose the winner for a completed contest

#### winner($cid)

View the winner for a contest that has already selected one

#### leaderboard()

Fetch most recent contests, with highest upvotes

#### start_dates()

Deprecated
