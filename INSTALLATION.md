# Installation

* [Requirements](#requirements)
* [Configuration files explanation](#configuration-files-explanation)
* [Production environment (for most users)](#production-environment)
  - [standard installation](#production-standard)
  - [custom installation](#production-custom)
* [Development environment](#development)
* [Docker environment](#docker)


# Requirements
* Web server: Apache or NGINX. Web server stacks like XAMPP can also be used.
* PHP 8.1 or higher
* MySQL (newest version)
* Composer (newest version)
* Node (with npm) (newest version)


# Configuration files explanation
### .env file

**Project location**

*lines 5-9*

The most important part. Here you can change.
The APP_URL parameter allows you to change
The BASE_ROOT_DIR parameter allows you to point . 
The ASSET_URL parameters allows you to . It should be the same as BASE_ROOT_DIR.
The BASE_API_URL parameter allows you . 
```
APP_URL=http://localhost

BASE_ROOT_DIR=/learnin
ASSET_URL=/learnin
BASE_API_URL=/learnin/api
```

**Database**

*lines 11-16*

These parameters allow you to change credentials to the database. If you are using Docker, DB_HOST is container name with database (default - database).
```
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=learnin
DB_USERNAME=root
DB_PASSWORD=superpassword
```

**Localization**

*lines 18-19*

These parameters allow you to change localization settings:
* LOCALE_TIMEZONE - timezone.
* LOCALE_DEFAULT - default locale for new users.
```
LOCALE_TIMEZONE=UTC
LOCALE_DEFAULT=en
```

### apache.conf
```apacheconf
# HTTP Server
<VirtualHost *:80>
        #ServerName www.example.com
        ServerAdmin webmaster@localhost

        # Alias for this project located in "learnin" subdirectory ex. http://localhost/learnin
        Alias /learnin "C:/xampp/htdocs/learnin/public"

        # Project location
        <Directory "C:/xampp/htdocs/learnin/public">
             Options Indexes FollowSymLinks
             AllowOverride All
             Order allow,deny
             Allow from all
        </Directory>

        # Don't forget change RewriteBase in public/.htaccess file!!!
</VirtualHost>
```

### nginx.conf
```nginx configuration
http {
    include       mime.types;
    default_type  application/octet-stream;

    sendfile        on;

    keepalive_timeout  65;

    # MAX REQUEST SIZE - FILES
    client_max_body_size 256M;

    # HTTP server
	server {
        listen 80;
        listen [::]:80;
        server_name localhost;

        index index.php index.html;

         # subdirectory - /learnin ex. http://localhost/learnin
         location /learnin {
              alias "C:/nginx/html/learnin/public"; # Windows project suggested location
              # alias "/var/www/html/learnin/public"; # Linux
              try_files $uri $uri/ @sub_directory;

              location ~ \.php {
                    fastcgi_split_path_info ^(.+\.php)(/.+)$;
                    include fastcgi_params;
                    fastcgi_index index.php;
                    fastcgi_pass 127.0.0.1:9123; # FAST-CGI socket
                    fastcgi_param SCRIPT_FILENAME $request_filename;
                }
          }

          # URL rewrite - change it too
          location @sub_directory {
              rewrite /learnin(.*)?$ /learnin/index.php last;
          }

          # deny .htaccess files - we don't need these files
          location ~ /\.ht {
              deny all;
          }
    }
}
```

# Production environment


### Standard installation
1. Create directory (ex. learnin) for project in main root WWW directory, ex. in C:\xampp\htdocs, C:\nginx\html, /var/www/html
```
mkdir learnin
cd learnin
git clone https://github.com/krzysztofhewelt/learnin-lms.git .
```

2. Copy .env.prod file to .env
```
cp .env.prod .env
```

2.1. **Optional:** Change project location (if you located project in another directories), database, locale parameters in .env file.
The default 

3. Run install.bat (Windows) or install.sh (Linux) script to install needed dependencies, generate app keys and initialize database.
Make sure, that database exists, and installed needed software.
```
./install.bat
OR
./install.sh
```

4. After installation ended, app address is default on: http://localhost/learnin/public. 
If you don't want URL with /public, go to custom installation.

5. **The default admin account credentials:**

-   email: `email@email.com`
-   password: `Admin#12345`
6. **Other users credentials:**

   |          | email                   | passwords  | example            |
      |----------|-------------------------|------------|--------------------|
   | students | student[1-15]@email.com | User#12345 | student5@email.com |
   | teachers | teacher[1-5]@email.com  | User#12345 | teacher1@email.com |

If you don't want these accounts, remove in administration panel.


# Development environment

```
php artisan serve
```

```
npm run dev
```

App is now (default) on address http://localhost:8000


# Docker development

1. Copy .env.docker file to .env

2. In project directory run docker compose command:

```
docker-compose -f .\docker-compose.yml up -d --build
```

3. Seed database with admin account:

```
docker exec -it app sh -c "php /var/www/html/artisan db:seed"
```

4. To stop project, type command:

```
docker-compose -f .\docker-compose.yml down
```

5. App is now (default) on address http://localhost
