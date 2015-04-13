#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/include.sh"

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

# Create DB
echo "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`;" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
if [ $? -ne 0 ]
then
	echo ">RETURN: 301 Can not create database"
	echo ">DATA: db_name $DB_NAME"
	exit;
fi

#Create user and grant access
echo "GRANT ALL ON \`$DB_NAME\`.* TO '$USER_NAME'@'localhost';" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
if [ $? -ne 0 ]
then
	echo ">RETURN: 302 Can not grant access to user"
	echo ">DATA: db_name $DB_NAME"
	echo ">DATA: user_name $USER_NAME"
	exit;
fi

# Update user password
echo "SET PASSWORD FOR '$USER_NAME'@'localhost' = PASSWORD('$DB_PASSWORD');" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
if [ $? -ne 0 ]
then
	echo ">RETURN: 303 Can not update user password"
	echo ">DATA: user_name $USER_NAME"
	exit;
fi

# Report success
echo ">RETURN: 0 DB successfully configured"
