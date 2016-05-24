### Server Overview

The tappyn application currently runs on a single LAMP server.

* Ubuntu 14.04
* Apache 2.4.7
* Mysql 5.5
* PHP 5.5.9

The database is presently on the same server as the application. This is something we had
planned for migrating to a remote instance, but hadn't done yet. To do so is fairly straightforward:

1. Spin up MySQL server on another instance
2. Create new users, as well as SSL if required
3. Update public/api/v1/application/config/{{env}}/database.php with new credentials.
