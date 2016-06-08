### Installation

Dependencies
* node
* git
* php5-curl
* composer

3rd Party Vendors
* Stripe
* SendGrid
* Facebook

### Steps

1. Create directory, and setup git to pull from the repo, and pull it.
2. We need to install a few application dependencies, all from root of project
    * composer install
    * npm install
    * npm run build   # this gulps our application into public/app.js
3. Create and fill out public/config.js
4. In public/api/v1/application/config, create a development folder, and fill with required config files.
5. Create phinx.yml in root of project, and populate
6. Execute `vendor/bin/phinx migrate`, which will generate your DB.

This could be modified to reflect the deployer process, which you can then run `vendor/bin/dep deploy` local, and
mimic the production environment. Up to you.
