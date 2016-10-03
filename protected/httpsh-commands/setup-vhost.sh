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
	if [ $USE_NGINX -eq 1 ]
	then
		echo "<VirtualHost *:$NGINX_PROXY_TO>"
	else
		echo "<VirtualHost *:80>"
	fi
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

# Reload apache config
webserver-reload

if [ $USE_NGINX -eq 1 ]
then
	# Create nginx vhost config
	NGINX_VHOST_FILE="$NGINX_VHOST_DIR/$DOMAIN.conf"
	{
		echo "server {"
		echo "	listen          80;"
		echo "	server_name $DOMAIN;"
		echo "	location / {"
		echo "		proxy_pass http://127.0.0.1:$NGINX_PROXY_TO/;"
		echo "		proxy_redirect off;"
		echo "		proxy_set_header Host \$host;"
		echo "		proxy_set_header X-Real-IP \$remote_addr;"
		echo "		proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;"
		echo "	}"
		echo "	location ~* \.(jpg|jpeg|gif|bmp|png|js|css)\$ {"
		echo "		root $DOC_ROOT;"
		echo "		access_log off;"
		echo "		expires 30d;"
		echo "		gzip              on;"
		echo "		gzip_buffers      16 8k;"
		echo "		gzip_comp_level   4;"
		echo "		gzip_http_version 1.0;"
		echo "		gzip_min_length   1280;"
		echo "		gzip_types        text/plain text/css application/x-javascript text/xml application/xml application/xml+rss text/javascript image/x-icon image/bmp;"
		echo "		gzip_vary         on;"
		echo "	}"
		echo "}"
	} > "$NGINX_VHOST_FILE"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 4 Can not create file"
		echo ">DATA: file $NGINX_VHOST_FILE"
		exit
	fi
	chmod "$VHMOD" "$NGINX_VHOST_FILE"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 2 Can not change file mode"
		echo ">DATA: file $NGINX_VHOST_FILE"
		echo ">DATA: mode $VHMOD"
		exit
	fi
	# Reload nginx config
	nginx-reload
fi

# Report success
echo ">RETURN: 0 Vhost successfully created"
