# Overview

# Installation
* `git clone https://github.com/michondr/my-project`
* `cd my-project`
* `composer install`

running server: 
* You can start dev server by running `bin/console server:run`, the visit `http://127.0.0.1:8000` in browser
* running on your own server - point server to `public/index.php`

building db structure:
* `bin/console doctrine:database:create`
* `bin/console doctrine:migrations:diff`
* `bin/console doctrine:migrations:migrate`