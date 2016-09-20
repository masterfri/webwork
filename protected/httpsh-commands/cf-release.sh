#!/bin/bash

# Include extras
THISDIR=$(dirname $0)
. "$THISDIR/config.sh"
. "$THISDIR/helpers.sh"

# Input vars
GIT_DIR=""
CF_DIR=""
OVERWRITE=0

# Reading args
while true
do
	case "$1" in
		--gitpath ) 
			GIT_DIR="$2"
			shift 2
			;;
		--cfpath )
			CF_DIR="$2"
			shift 2
			;;
		--overwrite )
			OVERWRITE=1
			shift 2
			;;
		* )
			break 
			;;
	esac
done

cd "$CF_DIR"
if [ $OVERWRITE -eq 1 ]
then
	OUTPUT=$(php "$CODEFORGE" release --overwrite --printsum --flashmode -o "$GIT_DIR")
else
	OUTPUT=$(php "$CODEFORGE" release --printsum --flashmode -o "$GIT_DIR")
fi
RESULT=$?
if [ $RESULT -ne 0 ]
then
	if [ $RESULT -eq 1 ]
	then
		echo ">RETURN: 402 Conflicted files have been detected"
		while IFS= read -r LINE
		do
			if [ "${LINE:0:10}" == "CONFLICT: " ]
			then
				echo ">DATA: conflict ${LINE:10}"
			fi
		done <<< "$OUTPUT"
		exit
	else
		echo ">RETURN: 401 Can not make release"
		exit
	fi
fi

# Report success
echo ">RETURN: 0 Application sucessfully released"
while IFS= read -r LINE
do
	echo ">DATA: checksum $LINE"
done <<< "$OUTPUT"
