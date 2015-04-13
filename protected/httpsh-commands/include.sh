#!/bin/bash

# Config
VHOST_DIR="/etc/apache2/pm-vhosts"
WWW_DATA_DIR="/var/www/domains"
GIT_DIR="/var/git/repos"
GROUP="grisha"
DIRMOD="u=rwx,g=rx,o=rx"
VHMOD="u=rwx,g=rx,o=rx"
MYSQL_USER="root"
MYSQL_PASS="sax"

# Commands
APACHECTL="apachectl"
GIT="git"
MYSQL="mysql"

function safemkdir {
	DIRNAME="$1"
	if [ ! -d "$DIRNAME" ]
	then
		mkdir "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 1 Can not create directory"
			echo ">DATA: dir $DIRNAME"
			exit;
		fi
		chmod "$DIRMOD" "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 2 Can not change directory mode"
			echo ">DATA: dir $DIRNAME"
			echo ">DATA: mode $DIRMOD"
			exit;
		fi
		chgrp "$GROUP" "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 3 Can not change directory owner group"
			echo ">DATA: dir $DIRNAME"
			echo ">DATA: group $GROUP"
			exit;
		fi
	fi
}
