#!/bin/bash

# Commands
APACHECTL="apache2ctl"
NGINX="nginx"
GIT="git"
MYSQL="mysql"

function safe-create-dir {
	DIRNAME="$1"
	if [ ! -d "$DIRNAME" ]
	then
		mkdir "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 1 Can not create directory"
			echo ">DATA: dir $DIRNAME"
			exit
		fi
		chmod "$DIRMOD" "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 2 Can not change directory mode"
			echo ">DATA: dir $DIRNAME"
			echo ">DATA: mode $DIRMOD"
			exit
		fi
		chgrp "$GROUP" "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 3 Can not change directory owner group"
			echo ">DATA: dir $DIRNAME"
			echo ">DATA: group $GROUP"
			exit
		fi
	fi
}

function safe-remove-dir {
	DIRNAME="$1"
	if [ -d "$DIRNAME" ]
	then
		rm -rf "$DIRNAME"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 5 Can not delete directory"
			echo ">DATA: dir $DIRNAME"
			exit
		fi
	fi
}

function safe-remove-file {
	FILE="$1"
	if [ -f "$FILE" ]
	then
		rm -f "$FILE"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 6 Can not delete file"
			echo ">DATA: file $FILE"
			exit
		fi
	fi
}

function cleanup-dir {
	DIR="$1"
	if [ -d "$DIR" ]
	then
		find "$DIR" -mindepth 1 -delete
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 8 Can not empty directory"
			echo ">DATA: dir $DIR"
			exit
		fi
	fi
}

function webserver-reload {
	sudo "$APACHECTL" graceful
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 101 Can not reload apache configuration"
		exit
	fi
}

function nginx-reload {
	sudo "$NGINX" -s reload
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 501 Can not reload nginx configuration"
		exit
	fi
}

function database-drop {
	DB_NAME="$1"
	echo "DROP DATABASE IF EXISTS \`$DB_NAME\`;" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 304 Can not drop database"
		echo ">DATA: db_name $DB_NAME"
		exit
	fi
}

function database-drop-user {
	DB_USER="$1"
	echo "DROP USER '$DB_USER'@'localhost';" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 305 Can not drop user"
		echo ">DATA: db_user $DB_USER"
		exit
	fi
}

function database-create {
	DB_NAME="$1"
	echo "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`;" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 301 Can not create database"
		echo ">DATA: db_name $DB_NAME"
		exit
	fi
}

function database-create-user {
	DB_NAME="$1"
	USER_NAME="$2"
	echo "GRANT ALL ON \`$DB_NAME\`.* TO '$USER_NAME'@'localhost';" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 302 Can not grant access to user"
		echo ">DATA: db_name $DB_NAME"
		echo ">DATA: user_name $USER_NAME"
		exit
	fi
}

function database-set-password {
	USER_NAME="$1"
	DB_PASSWORD="$2"
	echo "SET PASSWORD FOR '$USER_NAME'@'localhost' = PASSWORD('$DB_PASSWORD');" | "$MYSQL" -u"$MYSQL_USER" -p"$MYSQL_PASS"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 303 Can not update user password"
		echo ">DATA: user_name $USER_NAME"
		exit
	fi
}

function git-set-branch {
	BRANCH="$1"
	# Check if required branch already choosen
	CURENT_BRANCH=$("$GIT" symbolic-ref --short HEAD)
	if [ "$CURENT_BRANCH" != "$BRANCH" ]
	then
		# Check if branch exists
		BRANCH_TEST=$("$GIT" branch --list "$BRANCH")
		if [ "$BRANCH_TEST" == "" ]
		then
			# Checkout new branch
			"$GIT" checkout -b "$BRANCH"
		else
			# Checkout existing branch
			"$GIT" checkout "$BRANCH"
		fi
		# Check results
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 206 Can not checkout branch"
			echo ">DATA: branch $BRANCH"
			echo ">DATA: location $(pwd)"
			exit
		fi
	fi
}

function git-pull {
	REPO_URL="$1"
	BRANCH="$2"
	"$GIT" pull "$REPO_URL" "$BRANCH"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 205 Can not make pull"
		echo ">DATA: url $REPO_URL"
		echo ">DATA: branch $BRANCH"
		echo ">DATA: location $(pwd)"
		exit
	fi
}

function git-push {
	REPO_URL="$1"
	BRANCH="$2"
	MESSAGE="$3"
	# checking if there are changes
	CHANGES=$("$GIT" status --porcelain)
	if [ "$CHANGES" != "" ]
	then
		# adding untracking files
		while IFS= read -r LINE
		do
			if [ "${LINE:0:3}" == "?? " ]
			then
				"$GIT" add "${LINE:3}"
				if [ $? -ne 0 ]
				then
					echo ">RETURN: 207 Can not stage file"
					echo ">DATA: file ${LINE:3}"
					echo ">DATA: location $(pwd)"
					exit
				fi
			fi
		done <<< "$CHANGES"
		# make commit
		"$GIT" commit -a -m "$MESSAGE"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 208 Can not commit changes"
			echo ">DATA: location $(pwd)"
			exit
		fi
		# make push
		"$GIT" push "$REPO_URL" "$BRANCH"
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 205 Can not make push"
			echo ">DATA: url $REPO_URL"
			echo ">DATA: branch $BRANCH"
			echo ">DATA: location $(pwd)"
			exit
		fi
	fi
}

function git-init-repo {
	"$GIT" init
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 201 Can not init repository"
		echo ">DATA: location $(pwd)"
		exit
	fi
}

function get-create-repo {
	REPO_URL="$1"
	if [ ! -d "$REPO_URL" ]
	then
		safe-create-dir "$REPO_URL"
		cd "$REPO_URL"
		"$GIT" init --bare
		if [ $? -ne 0 ]
		then
			echo ">RETURN: 201 Can not init repository"
			echo ">DATA: location $(pwd)"
			exit
		fi
	fi
}

function git-create-origin {
	REPO_URL="$1"
	"$GIT" remote add origin "$REPO_URL"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 202 Can not add remote url"
		echo ">DATA: url $REPO_URL"
		echo ">DATA: location $(pwd)"
		exit
	fi
}

function git-update-origin {
	REPO_URL="$1"
	"$GIT" remote set-url origin "$REPO_URL"
	if [ $? -ne 0 ]
	then
		echo ">RETURN: 203 Can not update remote url"
		echo ">DATA: url $REPO_URL"
		echo ">DATA: location $(pwd)"
		exit
	fi
}
