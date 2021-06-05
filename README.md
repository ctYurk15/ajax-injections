# Description

Composer package that allows you to access your Database with AJAX-requests. No matter from what platform request is sent.

# Installation

To install this package you need to:
1. Install composer
2. Go to your directory folder with console
3. Write down next command:
```
composer require ctyurk15/ajax-injections
```

Done!

# Usage
Here is simple example for serverside

```php
//attaching composer`s autoload file
require __DIR__.'/vendor/autoload.php';

//creating instance of SQLCompiler
$sqlcompiler =  new SQLCompiler('localhost', 'root', 'root', 'ajax-injections');

//getting query we need to compile
$query = $_POST["query"];

//sending json_coded result
echo json_encode($sqlcompiler->compile($query));
```