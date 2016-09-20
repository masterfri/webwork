#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
DB_NAME=""
USER_NAME=""
DB_PASSWORD=""

# Reading args
while true
do
	case "$1" in
		--db_name ) 
			DB_NAME="$2"
			shift 2
			;;
		--user_name ) 
			USER_NAME="$2"
			shift 2
			;;
		--password ) 
			DB_PASSWORD="$2"
			shift 2
			;;
		* ) 
			break 
			;;
	esac
done

database-create "$DB_NAME"
database-create-user "$DB_NAME" "$USER_NAME"
database-set-password "$USER_NAME" "$DB_PASSWORD"

# Report success
echo ">RETURN: 0 DB successfully configured"
