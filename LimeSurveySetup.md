# LimeSurvey on Nginx and MySql

## Nginx with php support

```bash
sudo apt update
sudo apt install nginx
sudo apt install php7.2
sudo apt install php7.2-fpm
```

Enable the php support for nginx by uncommenting the corresponding part in the config 
(and adjusting version numbers if necessary)

```bash 
sudo nano /etc/nginx/sites-available/default
sudo systemctl restart nginx
``` 

## Extensions for php

```bash 
# Standard extensions 
sudo apt install php7.2-mbstring php7.2-pdo-mysql php7.2-gettext
sudo phpenmod mbstring
# Optional extensions
sudo apt install php7.2-zip php7.2-gd php7.2-ldap php7.2-imap php-sodium
``` 

## MySql

```bash
sudo apt install mysql-server
# For production environment:
sudo mysql_secure_installation
``` 