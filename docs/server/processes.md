### Processes

We are only running a few processes as cron-jobs right now.

```shell

* * * * * php /var/www/html/tappyn/api/v1/index.php admin mailing execute
5 * * * * php /var/www/html/tappyn/api/v1/index.php admin mailing find_recently_closed_contests
0 0 0 0 0 ./var/www/tappyn/current/bin/backup.sh

```
Backup.sh backs up each table of the database and uploads to S3. This does have a dependency on [s3cmd](https://github.com/s3tools/s3cmd)

The other two are for our current mailing system. Here's a rundown of how we are using it.

1. Event occurs, such as sign-up, contest payment, etc.
2. We insert the event details and recipient into tappyn.mailing_queue.
3. Every minute, the `admin mailing execute` process takes unsent emails, processes them
   based on the script, and creates the emails through SendGrid.
4. If successful, we update the table with the current 'sent_at' time.
5. If there was an error, we also update accordingly.

find_recently_closed_contests simply finds contests that have ended in the past hour,
and notifies the owner company that they need to select a winner.
