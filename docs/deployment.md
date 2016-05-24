## Deployment

We are presently deploying using [Deployer for PHP](http://deployer.org/docs).

It SSH's into the server using the 'deploy' user, pulls the specified GitHub repo
(using a deploy key) to the release folder. If it gets through the deployment process,
it symlinks the current directory to the recently deployed release. Otherwise it errors out,
and no symlink is created. This way the old revision is accessible until we've validated the
new deployment is functional.

Allowing a new user to deploy is relatively straightforward

1. Get SSH key for the user who would like to deploy.
2. Add their SSH key to /home/deploy/.ssh/authorized_keys so they can login as deploy.
3. As long as they have the most recent deploy script, they just have to execute deployer
```shell

cd /var/www/tappyn
composer install         # If not alread installed
vendor/bin/dep deploy production  # Depends on server you'd like to deploy to

```
4. If all goes well, the new release will be in the releases folder, and current will
be syminked to it
