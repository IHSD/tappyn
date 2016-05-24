### Server Overview

The tappyn application currently runs on a single LAMP server.

* Ubuntu 14.04
* Apache 2.4.7
* Mysql 5.5
* PHP 5.5.9

### Front End

The front end applicaiton is written in Angular.js. Each seciton is broken down to its
respective components folder, and then compiled to app.js when deployed.

### Back End

The API is written in PHP using CodeIgniter as a framework. All vendor files are installed at the root
of the project, rather than inside the application.

### Database

Database migrations / seeds are handled through [Phinx](http://docs.phinx.org/en/latest/). The seeds and migrations
are located in the data folder of the project

A current EER diagram of the DB schema:

The database is presently on the same server as the application. This is something we had
planned for migrating to a remote instance, but hadn't done yet. To do so is fairly straightforward:

1. Spin up MySQL server on another instance
2. Create new users, as well as SSL if required
3. Update public/api/v1/application/config/{{env}}/database.php with new credentials.
4. Also update phinx.yml so migrations can communicate with new server

## Staging

We currently have a staging environment set up at test.tappyn.com. To deploy to it, simply use vendor/bin/dep deploy staging. It defaults
to pulling the develop branch. It uses test keys for Stripe payments so they can be functionally validated, and uses its own SendGrid API key to
send emails

**NOTE If you want to send emails from the test server, you have to SSH in and manually execute the mailer cron process. This could be added as
a cron job, as per the live server, but we didnt want to in the test environment.**

## Envirnoment Variables

All environment variables we use are at the bottom of /etc/apache2/apache2.conf.
The only one currently required is CI_ENV for the CodeIgniter application
