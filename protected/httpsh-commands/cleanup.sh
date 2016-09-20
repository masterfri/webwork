#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
DOMAIN=""
DB_NAME=""
DB_USER=""
REPO_NAME=""
WORKDIR=""
DELETE_FILES=0
DELETE_VHOST=0
DELETE_GIT=0
DELETE_DB=0
DELETE_DB_USER=0
DELETE_TMPGIT=0

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
		--workdir ) 
			WORKDIR="$2"
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
		--delete_tmpgit ) 
			DELETE_TMPGIT=1
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
	safe-remove-dir "$WWW_DATA_DIR/$DOMAIN"
fi

# Delete vhost configuration file
if [ $DELETE_VHOST -eq 1 ]
then
	VHOST_FILE="$VHOST_DIR/$DOMAIN.conf"
	if [ -f "$VHOST_FILE" ]
	then
		safe-remove-file "$VHOST_FILE"
		webserver-reload
	fi
fi

# Delete git repo
if [ $DELETE_GIT -eq 1 ]
then
	safe-remove-dir "$GIT_DIR/$REPO_NAME.git"
fi

# Drop mysql database
if [ $DELETE_DB -eq 1 ]
then
	database-drop "$DB_NAME"
fi

# Delete mysql user
if [ $DELETE_DB_USER -eq 1 ]
then
	database-drop-user "$DB_USER"
fi

# Cleanup work copy
if [ $DELETE_TMPGIT -eq 1 ]
then
	cleanup-dir "$WORKDIR"
fi

# Report success
echo ">RETURN: 0 Cleanup successfully completed"
