#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/include.sh"

# Input vars
DOMAIN=""
DB_NAME=""
DB_USER=""
REPO_NAME=""
DELETE_FILES=0
DELETE_VHOST=0
DELETE_GIT=0
DELETE_DB=0
DELETE_DB_USER=0

# Reading args
while true
do
	case "$1" in
		--domain ) 
			DOMAIN="$2"
			shift 2
			;;
		--db_name ) 
			DB_NAME="$2"
			shift 2
			;;
		--db_user ) 
			DB_USER="$2"
			shift 2
			;;
		--repo_name ) 
			REPO_NAME="$2"
			shift 2
			;;
		--delete_files ) 
			DELETE_FILES=1
			shift 2
			;;
		--delete_vhost ) 
			DELETE_VHOST=1
			shift 2
			;;
		--delete_git ) 
			DELETE_GIT=1
			shift 2
			;;
		--delete_db ) 
			DELETE_DB=1
			shift 2
			;;
		--delete_dbuser ) 
			DELETE_DB_USER=1
			shift 2
			;;
		* ) 
			break 
			;;
	esac
done

# Delete application files
if [ $DELETE_FILES -eq 1 ]
then
	SITE_DIR="$WWW_DATA_DIR/$DOMAIN"
	if [ -d "$SITE_DIR" ]
	then
		rm -rf "$SITE_DIR"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 5 Can not delete directory"
			echo ">DATA: dir $SITE_DIR"
			exit;
		fi
	fi
fi

# Delete vhost configuration file
if [ $DELETE_VHOST -eq 1 ]
then
	VHOST_FILE="$VHOST_DIR/$DOMAIN.conf"
	if [ -f "$VHOST_FILE" ]
	then
		rm -f "$VHOST_FILE"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 6 Can not delete file"
			echo ">DATA: dir $VHOST_FILE"
			exit;
		fi
		
		# Reload apache config
		sudo "$APACHECTL" graceful
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 101 Can not reload apache configuration"
			exit;
		fi
	fi
fi

# Delete git repo
if [ $DELETE_GIT -eq 1 ]
then
	REPO_URL="$GIT_DIR/$REPO_NAME.git"
	if [ -d "$REPO_URL" ]
	then
		rm -rf "$REPO_URL"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 5 Can not delete directory"
			echo ">DATA: dir $REPO_URL"
			exit;
		fi
	fi
fi

# Drop mysql database
if [ $DELETE_DB -eq 1 ]
then
	echo "DROP DATABASE IF EXISTS \`$DB_NAME\`;" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 304 Can not drop database"
		echo ">DATA: db_name $DB_NAME"
		exit;
	fi
fi

# Delete mysql user
if [ $DELETE_DB_USER -eq 1 ]
then
	echo "DROP USER '$DB_USER'@'localhost';" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 305 Can not drop user"
		echo ">DATA: db_user $DB_USER"
		exit;
	fi
fi

# Report success
echo ">RETURN: 0 Cleanup successfully completed"
