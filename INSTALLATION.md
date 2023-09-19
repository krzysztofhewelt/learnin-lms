# Installation

* [Requirements](#requirements)
* [Configuration files explanation](#configuration-files-explanation)
* [Production environment](#production-environment)
  - [standard installation (for most users)](#standard-installation)
  - [custom installation](#custom-installation)
* [Development environment](#development-environment)
* [Docker environment](#docker-development)


# Requirements
* Web server: Apache or NGINX. Web server stacks like XAMPP can also be used.
* PHP 8.1 or higher with **ext-zip** extension
* MySQL (newest version)
* Composer (newest version)
* Node (with npm) (newest version)


# Configuration files explanation
### .env file

<ins id="project-location">**Project location *(lines 5-9)***</ins>

The most important part. The most important parameters are described below:
- **APP_URL** - domain name and project location,
- **BASE_ROOT_DIR** - important parameter, it locates project directory related with root WWW directory. Relative path and URL are allowed.
- **ASSET_URL** - directory with compiled and static files. It should be the same as BASE_ROOT_DIR.
- **BASE_API_URL** - API location, in LearnIn, API URL ends /api (or /public/api).
```ini
APP_URL=http://localhost/learnin

BASE_ROOT_DIR=/learnin
ASSET_URL=/learnin
BASE_API_URL=/learnin/api
```

<ins id="database">**Database *(lines 11-16)***</ins>

These parameters allow you to change credentials to the database.<br>
If you are using Docker, DB_HOST is container name with database (default - database).
```ini
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=learnin
DB_USERNAME=root
DB_PASSWORD=superpassword
```

<ins id="localization">**Localization *(lines 18-19)***</ins>

These parameters allow you to change localization settings:
* LOCALE_TIMEZONE - timezone,
* LOCALE_DEFAULT - default locale for new users.
```ini
LOCALE_TIMEZONE=UTC
LOCALE_DEFAULT=en
```

### apache.conf

Apache web server.<br>
If you don't want /public in URL, then you need to change Apache configuration. Suggested configuration for LearnIn stored in "learnin" directory in main root WWW directory:
```apacheconf
# HTTP Server
<VirtualHost *:80>
     #ServerName www.example.com
     ServerAdmin webmaster@localhost

     # Alias for this project located in "learnin" subdirectory ex. http://localhost/learnin
     Alias /learnin "C:/xampp/htdocs/learnin/public"
     # For Linux users:
     # Alias /learnin /var/www/html/learnin/public

     # Project location
     <Directory "C:/xampp/htdocs/learnin/public">
         Options Indexes FollowSymLinks
         AllowOverride All
         Order allow,deny
         Allow from all
     </Directory>

     # Don't forget change RewriteBase in public/.htaccess file!!!
     # You must also change directories in .env file (APP_URL, BASE_ROOT_DIR, ASSET_URL, BASE_API_URL)!!!
</VirtualHost>
```

### nginx.conf

NGINX web server.<br>
If you don't want /public in URL, then you need to change NGINX configuration. Suggested configuration for LearnIn stored in "learnin" directory in main root WWW directory:
```nginx configuration
server {
    listen 80;
    listen [::]:80;
    server_name localhost;

    index index.php index.html;

     # subdirectory - /learnin ex. http://localhost/learnin
     location /learnin {
          # Windows project suggested location
          alias "C:/nginx/html/learnin/public";
          # Linux
          # alias "/var/www/html/learnin/public";
          
          try_files $uri $uri/ @sub_directory;

          location ~ \.php {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                include fastcgi_params;
                fastcgi_index index.php;

                # socket to FAST-CGI PHP, change if needed
                fastcgi_pass 127.0.0.1:9123;

                fastcgi_param SCRIPT_FILENAME $request_filename;
            }
      }

      # URL rewrite - change it also, when stored in another subdirectory!!
      location @sub_directory {
          rewrite /learnin(.*)?$ /learnin/index.php last;
      }

      # deny .htaccess files - we don't need these files
      location ~ /\.ht {
          deny all;
      }
}
```

### php.ini

Project requires ext-zip PHP extension installed and enabled. You can do this in php.ini configuration file. Uncomment this line:
```ini
extension=zip
```

# Production environment

For most users. The easiest way to deploy LearnIn on your server is showed in <q>Standard installation</q>.

### Standard installation
1. Create directory (ex. learnin) for project in main root WWW directory, ex. in C:\xampp\htdocs, C:\nginx\html, /var/www/html
```
mkdir learnin
cd learnin
git clone https://github.com/krzysztofhewelt/learnin-lms.git .
```

2. Copy .env.prod file to .env. Change parameters on your needs -> see .env
```
cp .env.prod .env
```

3. Run install.bat (Windows) or install.sh (Linux) script to install needed dependencies, generate app keys and initialize database.
**Make sure, that database exists, installed needed software, and enabled ext-zip PHP extension.**
```
./install.bat
OR
./install.sh
```

4. After installation ended, LearnIn app address is default on: http://localhost/learnin/public. 
If you don't want URL with /public, go to the [custom installation](#custom-installation).

5. It's highly recommend to run the scheduler (that cleans old generated .zip files by teachers every 12 hours):
```
php artisan schedule:work
```

6. **The default admin account credentials:**

-   email: `email@email.com`
-   password: `Admin#12345`
7. **Other users credentials:**

   |          | email                   | passwords  | example            |
      |----------|-------------------------|------------|--------------------|
   | students | student[1-15]@email.com | User#12345 | student5@email.com |
   | teachers | teacher[1-5]@email.com  | User#12345 | teacher1@email.com |

If you don't want these accounts, remove in administration panel.


### Custom installation

Advanced installation. Do these steps if you don't want /public in URL.

1. Do steps 1-3 in standard installation (you can change project location on your needs).
2. If you are using Apache web server, search for Apache configuration file, ex.:
* C:\xampp\apache\conf\extra\httpd-vhosts.conf
* /etc/apache2/sites-enabled/000-default.conf

Open apache.conf file (located in project root directory) and paste content to your Apache configuration file.<br>
Change configuration by own needs, described [here](#apacheconf). 

3. If you are using NGINX web server, search for NGINX configuration file, ex.:
* C:\nginx\conf\nginx.conf
* /etc/nginx/nginx.conf

Open nginx.conf file (located in project root directory) and paste content to your NGINX configuration file.<br>
Change configuration by own needs, described [here](#nginxconf).

# Development environment

Development mode provides HMR module and Prettier for code styling. In this mode project can be stored anywhere.

1. Copy .env.dev file to .env
```
cp .env.dev .env
```

2. Install dependencies and initialize database.
```
./install.bat
OR
./install.sh
```

3. To run this project run internal web server
```
php artisan serve
```

4. Run Vite development mode
```
npm run dev
```

5. App is now (default) on address http://localhost:8000. Any changes in project files will update page automatically without refreshing.


# Docker development

1. Copy .env.docker file to .env

```
cp .env.docker .env
```

2. In project directory run docker compose command:

```
docker-compose -f .\docker-compose.yml up -d --build
```

3. Seed database with admin account and example users:

```
docker exec -it app sh -c "php /var/www/html/artisan db:seed"
```

4. To stop project, type command:

```
docker-compose -f .\docker-compose.yml down
```

5. App is now (default) on address http://localhost. You don't need to run install.bat/install.sh, Docker will do this for you.
