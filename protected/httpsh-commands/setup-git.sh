#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/include.sh"

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
safemkdir "$SITE_DIR"

CWD=$(pwd)

# Create server repo
if [ "$REPO_URL" == "" ]
then
	REPO_URL="$GIT_DIR/$REPO_NAME.git"
	if [ ! -d "$REPO_URL" ]
	then
		safemkdir "$REPO_URL"
		cd "$REPO_URL"
		"$GIT" init --bare
		if [ $? -ne 0 ]
		then
			cd "$CWD"
			echo ">RETURN: 201 Can not init repository"
			echo ">DATA: location $REPO_URL"
			exit;
		fi
	fi
fi

# Create work repo
if [ ! -d "$SITE_DIR/.git" ]
then
	cd "$SITE_DIR"
	"$GIT" init
	if [ $? -ne 0 ]
	then
		cd "$CWD"
		echo ">RETURN: 201 Can not init repository"
		echo ">DATA: location $SITE_DIR"
		exit;
	fi

	"$GIT" remote add origin "$REPO_URL"
	if [ $? -ne 0 ]
	then
		cd "$CWD"
		echo ">RETURN: 202 Can not add remote url"
		echo ">DATA: location $SITE_DIR"
		echo ">DATA: url $REPO_URL"
		exit;
	fi
	cd "$CWD"
else
	cd "$SITE_DIR"
	"$GIT" remote set-url origin "$REPO_URL"
	if [ $? -ne 0 ]
	then
		cd "$CWD"
		echo ">RETURN: 203 Can not update remote url"
		echo ">DATA: location $SITE_DIR"
		echo ">DATA: url $REPO_URL"
		exit;
	fi
	cd "$CWD"
fi

# Report success
echo ">RETURN: 0 Repo successfully created"
echo ">DATA: url $REPO_URL"
