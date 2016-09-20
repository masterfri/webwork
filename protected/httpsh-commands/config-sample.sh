#!/bin/bash

# First of all you need to create working user and add it to working group
# This user should be listed in sudoers list with ability to execute 
# sudo apachectl graceful without password

# Config

# directory for vhosts configurations, writable for working group
# don'f forget to add following directive to apache2.conf (or httpd.conf)
# IncludeOptional /path/to/vhosts/*.conf
VHOST_DIR="/path/to/vhosts"

# directory for web, writable for working group
WWW_DATA_DIR="/var/www/domains"

# directory for git repos, writable for working group
GIT_DIR="/var/git/repos"

# permissions settings
GROUP="workgroup"
DIRMOD="u=rwx,g=rx,o=rx"
VHMOD="u=rwx,g=rx,o=rx"

# mysql settings
MYSQL_USER="mysql_user"
MYSQL_PASS="mysql_password"

# path to codeforge
CODEFORGE="/path/to/codeforge.php"

# exports
# Path to yii framework, points to "framework" directory
export YII_PATH="/path/to/yii/framework"
