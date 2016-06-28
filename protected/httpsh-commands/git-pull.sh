#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/include.sh"

# Input vars
DOMAIN=""
REPO_URL=""
BRANCH=""

# Reading args
while true
do
	case "$1" in
		--domain ) 
			DOMAIN="$2"
			shift 2
			;;
		--branch ) 
			BRANCH="$2"
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

SITE_DIR="$WWW_DATA_DIR/$DOMAIN"
if [ ! -d "$SITE_DIR/.git" ]
then
	echo ">RETURN: 204 Can not find working copy"
	echo ">DATA: location $SITE_DIR"
fi	

CWD=$(pwd)
cd "$SITE_DIR"
"$GIT" pull origin "$BRANCH"
if [ $? -ne 0 ]
then
	cd "$CWD"
	echo ">RETURN: 205 Can not make pull"
	echo ">DATA: location $SITE_DIR"
	echo ">DATA: url $REPO_URL"
	echo ">DATA: branch $BRANCH"
	exit;
fi
cd "$CWD"

# Report success
echo ">RETURN: 0 Pull has been successfully completed"
