## Configuration

Examples of all configuration files can be found in the data folder of the project

### database.php.example

CodeIgniter (hereby CI) config file for connecting to database. There is also a sample with SSL parameters provided

### config.php.example

CI config file. Sets the URL of the applicaiton, logging directories and permissions, etc.

### secrets.php.example

CI Config file. Holds keys for SendGrid, Stripe, and other 3rd party vendors

### facebook_ion_auth.php.exmaple

CI Config file. Holds Facebook App credentials for FB Login

### phinx.yml (or phinx.php)

COnfig at root of application. Contans connection paramters for running Phinx migrations.
**NOTE: the connection specified in this file must have permissions to modify the DB schema. If you are using,
SSL, you must provide a PDO connecton instance rather than connection parameters. AN example is also in the folder.**

### config.js

Goes in public/config.js. Holds public config parameters as required by the front end application. Places all variables in APP_ENV object,
which the application can then access
