##############
 Tappyn
##############

This is the project repo for Tappyn, a web application that allows companies to
create contests paying for ad creative content. Users can then sign up and
submit ideas, with the company selecting and paying one winner.


**************
Installation
**************
To bootstrap your environment, pull this repository to your desired location.
Run all .sql files located in application/lib/migrations/*.sql
In application/config, copy all files with .example suffix to .php, i.e.
    database.php.example -> database.php
Then fill out the required fields based on your server environment.

facebook_ion_auth.php   -> Facebook App Credentials for FB login.
                        * You will have to create a new app if you dont have one
                        * https://developers.facebook.com

database.php            -> Database connection credentials

config.php              -> Server config (base url, logging, etc)

secrets.php             -> Environment variables necessary for 3rd party vendors
                        * As of now this is not being used

***************
Resourc
