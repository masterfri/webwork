#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
DOMAIN=""
REPO_NAME=""
REPO_URL=""

# Reading args
while true
do
	case "$1" in
		--domain ) 
			DOMAIN="$2"
			shift 2
			;;
		--name ) 
			REPO_NAME="$2"
			shift 2
			;;
		--url ) 
			REPO_URL="$2"
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

# Create server repo
if [ "$REPO_URL" == "" ]
then
	get-create-repo "$GIT_DIR/$REPO_NAME.git"
fi

# Create work repo
cd "$SITE_DIR"
if [ ! -d "$SITE_DIR/.git" ]
then
	git-init-repo
	git-create-origin "$REPO_URL"
else
	git-update-origin "$REPO_URL"
fi

# Report success
echo ">RETURN: 0 Repo successfully created"
echo ">DATA: url $REPO_URL"
