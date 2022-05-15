# Billie Factoring Service

**Symfony 6 project**

### Environment
- nginx 1.17
- php-fpm 8.1
- mysql 

### Requirements
Docker compose, Git and as the main IDE is PhpStorm (preferably the latest version with Shell Configuration supports).

### Installation
First, create a folder for your project and clone the repository:

```bash
git clone https://github.com/f-stojanovic/Billie_Backend_Coding_Challenge.git
```

Open project root folder and run next configurations:
1. Open terminal and run: `docker-compose build`
2. Then: `docker-compose -f docker-compose.yml up`
3. Enter the PHP container: `docker exec -it billie_backend_coding_challenge-php-1 /bin/bash`
4. In the container run `composer install` (after execute, it will take a little time to index the installed vendors)
5. In the same container run: `php bin/console doctrine:migrations:execute --up 'DoctrineMigrations\Version20220515054739'` to fill up the database
6. Now, lets load some fixtures with `php bin/console doctrine:fixtures:load`

Go to `http://localhost` page in your browser. If the installation is successful, you will see:
- Registration form. 
- After completed registration you will be redirected to login form
- You can login now and you will be redirected to Dashboard. :) 

> Welcome to Billie Factoring Service Dashboard!

Here you can perform all operations defined in the task. All options are in the `List of Comapnies` and `List of Invoices` views.

>Now go and play! :)


