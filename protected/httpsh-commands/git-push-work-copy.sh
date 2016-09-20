#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
WORK_PATH=""
REPO_URL=""
BRANCH=""
MESSAGE=""

# Reading args
while true
do
	case "$1" in
		--workpath ) 
			WORK_PATH="$2"
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
		--message )
			MESSAGE="$2"
			shift 2
			;;
		* ) 
			break 
			;;
	esac
done

if [ ! -d "$WORK_PATH/.git" ]
then
	echo ">RETURN: 204 Can not find working copy"
	echo ">DATA: location $WORK_PATH"
	exit
fi

cd "$WORK_PATH"
git-push "$REPO_URL" "$BRANCH" "$MESSAGE"

# Report success
echo ">RETURN: 0 Push has been successfully completed"
