Copy `config/config-default.php -> config/config.php`

Copy `config/db-default.php -> config/db.php`

Edit `config/db.php`, fill in the database name, username, and password with
your appropriate settings. (If you are using postgres, then set the driver to 'pgsql')

If your app url is not located at the domain root `/` (i.e. in a subdirectory),
then edit `config/db.php`, set the appropriate `root_path`. Exempli gratia,
if your app url is located at `http://localhost/todo/`, then root_path should
be `/todo/`. Don't forget to add the trailing slash!

By default, you aren't using url-rewrite, thus any url to access
controller/action/params must include `index.php`. For example
`http://localhost/index.php/task/all`.

Import the schema file (`models/schema.<dbms>.sql`) to the database, example
command: `mysql -u [username] -p [database_name] < models/schema.mysql.sql`
and `psql [database_name] < models/schema.postgres.sql`

If you have php cli interpreter, run `php tests/reseedtask.php` and
`php tests/reseedhello.php`. Otherwise, you can open it via browser
while your app is listening.
