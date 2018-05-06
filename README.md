# Overview

## Installation
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

## Installation via ansible
You need ansible playbook installed on your machine
* edit ansible hosts `vi /etc/ansible/hosts` and add row like this `time_table_tweek ansible_host=111.111.111.111 ansible_private_key_file=~/.ssh/id_rsa ansible_user=ubuntu ansible_python_interpreter=/usr/bin/python3`
* in your project run `ansible-playbook .build/install.yml`
* in your project run `ansible-playbook .build/deploy.yml` (you can specify deployed branch by `-e "branch=some_test_branch"`)