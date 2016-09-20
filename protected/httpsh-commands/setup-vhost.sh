#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
DOMAIN=""
DOC_ROOT=""
LOG_DIR=""
VHOST_OPTS=""

# Reading args
while true
do
	case "$1" in
		--domain ) 
			DOMAIN="$2"
			shift 2
			;;
		--document_root ) 
			DOC_ROOT="$2"
			shift 2
			;;
		--log_directory ) 
			LOG_DIR="$2"
			shift 2
			;;
		--vhost_options* )
			VHOST_OPTS="$VHOST_OPTS\t$2\n"
			shift 2
			;;
		* ) 
			break 
			;;
	esac
done

# Create necessary directories
SITE_DIR="$WWW_DATA_DIR/$DOMAIN"
safe-create-dir "$SITE_DIR"

DOC_ROOT="$SITE_DIR/$DOC_ROOT"
safe-create-dir "$DOC_ROOT"

if [ "$LOG_DIR" != "" ]
then
	LOG_DIR="$SITE_DIR/$LOG_DIR"
	safe-create-dir "$LOG_DIR"
fi

# Create vhost config
VHOST_FILE="$VHOST_DIR/$DOMAIN.conf"
{
	echo "<VirtualHost *:80>"
	echo "	ServerName $DOMAIN"
	echo "	DocumentRoot $DOC_ROOT"
	echo "$VHOST_OPTS"
	echo "	<Directory $DOC_ROOT >"
	echo "		Options FollowSymLinks"
	echo "		AllowOverride All"
	echo "		Order allow,deny"
	echo "		Allow from all"
	echo "	</Directory>"
	if [ "$LOG_DIR" != "" ]
	then
		echo "	CustomLog $LOG_DIR/access_log combined"
		echo "	ErrorLog $LOG_DIR/error_log"
	fi
	echo "</VirtualHost>"
} > "$VHOST_FILE"
if [ $? -ne 0 ]
then
	echo ">RETURN: 4 Can not create file"
	echo ">DATA: file $VHOST_FILE"
	exit
fi

chmod "$VHMOD" "$VHOST_FILE"
if [ $? -ne 0 ]
then
	echo ">RETURN: 2 Can not change file mode"
	echo ">DATA: file $VHOST_FILE"
	echo ">DATA: mode $VHMOD"
	exit
fi

# Restart apache
webserver-reload

# Report success
echo ">RETURN: 0 Vhost successfully created"
