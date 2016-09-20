#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
WORK_PATH=""
REPO_URL=""
BRANCH=""

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
		* ) 
			break 
			;;
	esac
done

if [ ! -d "$WORK_PATH" ]
then
	echo ">RETURN: 7 No such file or directory"
	echo ">DATA: dir $WORK_PATH"
	exit
fi

cd "$WORK_PATH"

if [ ! -d "$WORK_PATH/.git" ]
then
	git-init-repo
fi

git-set-branch "$BRANCH"

# Check if remote branch exists
BRANCH_TEST=$("$GIT" ls-remote --heads "$REPO_URL" "$BRANCH")
if [ "$BRANCH_TEST" != "" ]
then
	git-pull "$REPO_URL" "$BRANCH"
fi

# Report success
echo ">RETURN: 0 Pull has been successfully completed"
