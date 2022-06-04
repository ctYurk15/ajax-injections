# Description

Composer package that allows you to access your Database with AJAX-requests. No matter from what platform request is sent.

# Installation

To install this package you need to:
1. Install composer
2. Add line to your composer.json file in project folder:
```
"minimum-stability": "dev"
```
3. Go to your project folder with console
4. Write down next command:
```
composer require ctyurk15/ajax-injections
```

Done!

# Usage
Here is simple examples for serverside. 

1. If you want simple access without any passwords or blacklist:
```php
//attaching composer`s autoload file
require __DIR__.'/vendor/autoload.php';

//creating instance of SQLCompiler
$sqlcompiler =  new SQLCompiler('localhost', 'root', 'root', 'ajax-injections');

//getting query we need to compile and password
$query = $_POST["query"];

//sending json_coded result
echo json_encode($sqlcompiler->compile($query));
```

2. If you want to ban some words in requests:
```php
//attaching composer`s autoload file
require __DIR__.'/vendor/autoload.php';

//if you want to restrict some sequences(OPTIONAL)
$blacklist = ['create database', 'drop', '*', 'id'];

//creating instance of SQLCompiler
$sqlcompiler =  new SQLCompiler('localhost', 'root', 'root', 'ajax-injections', $blacklist);

//getting query we need to compile
$query = $_POST["query"];

//sending json_coded result
echo json_encode($sqlcompiler->compile($query));
```

3. Maximal security with password and blacklist:
```php
//attaching composer`s autoload file
require __DIR__.'/vendor/autoload.php';

//if you want to restrict some sequences(OPTIONAL)
$blacklist = ['create database', 'drop', '*', 'id'];

//creating instance of SQLCompiler
$sqlcompiler =  new SQLCompiler('localhost', 'root', 'root', 'ajax-injections', $blacklist, '12345');

//getting query we need to compile and password
$query = $_POST["query"];
$password = $_POST["password"];

//sending json_coded result
echo json_encode($sqlcompiler->compile($query, $password));
```

# Example
Example website: http://ctyurk15.xyz/ajax-injections/
