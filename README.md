# PHP framework

Small and fast PHP framework usable for practically any small PHP project. Contains MySQL and MongoDB support, automatic caching with Memcached.

## Goals

* Simple and small.
* Lazy-loading system prevents unneeded files from being loaded.
* MVC-based with simple template system.
* Supports MySQL and MongoDB databases.
* Automatically caches database results with Memcached.
* Allows restrictions to each controller based on IP address.
* Supports AJAX requests.
* Contains example of a simple user's object allowing to login the user.

## Requirements

* PHP 5.3.
* [mcrypt](http://php.net/mcrypt) extension is required for encryption.
* [memcached](http://php.net/memcached) extension is required for caching.
* [PDO extension with MySQL driver](http://php.net/pdo) is required for MySQL usage.
* [MongoDB](http://php.net/mongo) extension is required for MongoDB usage.


## How to see the build-in example

Rename appropriate file from `app/models` to `usersModel.php` (for example, if you want to use MongoDB, rename `usersModel_mongodb.php` to `usersModel.php`).

Add appropriate config settings concerning your database to `index.php`:
 + For MySQL: `mysql_db_host`, `mysql_db_port`, `mysql_db_user`, `mysql_db_pass`.
 + For MongoDB: `mongo_db_socket` or `mongo_db_host` and `mongo_db_port`, optionally also `mongo_db_user`, `mongo_db_pass`.
5. Open the page, example login data is - login: _**test**_,  password: _**test**_. 

Set the `memcache_host` config value to appropriate memcache host (`memcache_port` is also available if needed). That's all.

Change `crypt_std_key` value to any key you want (with the same lenght) and set `$_crypted` value in appropriate models to `true`.

* Restriction of access for specific controllers?_

See the appConfig.php file for information on `restrict` config key. Basic usage:

    "restrict" => array(
        "admin" => array(	    
            "127.0.0.1"  
        )
    )

This will restrict access to the controller named "admin" only to users connecting from localhost.

### Configuration

`app` directory contains the core files of the application, `public_html` is, on the other hand, the place where your server should point to.

All settings and configuration options are held in `public_html/index.php` file. The only thing that should be changed in this file is this array:

    $c->start(array(
        "debug" => true,
        "crypt_std_key" => "c475da59d82f5d8bd52153367ecb7420",
    ));

If you're using a web-server other than Apache, you need to adjust the "rewrites" in your server's configuration to match the contents of `.htaccess`.
It is a very simple configuration: all requests that doesn't go to `www/` directory, should be directed to `index.php`.

### MVC architecture

After your configuration is set up, you can get into the `app` directory. As the framework is based on MVC concept there are 3 main directories here:
`controllers`, `models` and `templates`. 

#### Controllers

There should **always** be a file named `main.php` with `appController` class with `index()` method inside. Empty version of this file should look like this:
    class appController extends appCore {

        public function index() {

        }
    }

This is the only "special" controller in the application: `index()` method is called always for every empty request, i.e. every request than opens the "home page".
For example if your application is based under *http://www.slbcampeao.com/*, every request coming to *http://www.slbcampeao.com/* will be redirected to this `index()` method. 

All requests that do not come to the root directory (`/`) are treated with the standard MVC approach: **http://www.slbacampeao.com/controller/action/param1/param2/.../**
If the `action` is not set (i.e. request comes to *http://www.slbacampeao.com/controller/*), it will be redirected to the `index()` method in specified controller. 

#### Models
Each model can be connected to a different database (right now: MongoDB or MySQL).
The type of the database that the model refers to is determined by the class that model extends.

`usersModel` that will be connecting to MongoDB database must extend the `databaseMongoDB` class:

    class usersModel extends databaseMongoDB {}

`usersModel` that will be connecting to MySQL database must extend the `databaseMysql` class:

    class usersModel extends databaseMysql {}

Each model contains two basic information that are required for it to work: `$_dbName` and `$_dbTable`.
So, if you want your model to connect to the database "test" and use the table (collection) "users", you should have the following code in your model:
protected $_dbName = "test";
protected $_dbTable = "users";




#### Templates
There should always be a file called `layout.php`, which holds the "main"
HTML template of the whole page. Inside this file there should be a loop:

    <?php foreach($templates as $template){ include($template); }?>

Every other template file will be passed in the `$templates` array to this file. You can, of course, adjust this file accordingly to your needs.
Refer to the existing template files for the examples.


## Using templates with controllers

Usage of the templates inside of the controllers is very simple. Every action on the templates should be done on the `$this->view()` object.
For example to show a template called `welcome.php`:

    $this->view()->addTemplate("welcome");

The name passed to the `addTemplate` method does not have to end with ".php" (it will be added automatically if it's not there).

If you want to pass a variable to the template (for example: show a user's name):

    $this->view()->addTemplateVal("username", "Paul");

Now, you can use the `$username` variable in the `welcome.php` template:

    <div>
        <h1>Welcome <?=$username?>!</h1>
    </div>

    $this->view()->addJs("my_additional_script.js");


### Responding to AJAX
    $this->view()->setAjax(true);
    $this->view()->addAjaxTemplate("ajax");
    $this->view()->sendAjax($somecontenthere);


## Using models with controllers
    $usersModel = $this->db()->getModel("users");
    $result = $usersModel->getUser($login, $password);

## Using helpers:

    $menuHelper = new menuHelper();
    $menuHelper->getMenu();