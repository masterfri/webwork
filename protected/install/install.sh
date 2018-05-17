#!/bin/bash

RED="$(tput setaf 1)"
GREEN="$(tput setaf 2)"
RESET="$(tput sgr0)"
PHP="php"
ANSWER=""
STAGE=1
ISROOT=1
WWWUSER="www-data"
DBNAME=""
DBUSER=""
DBPASS=""
YIIPATH="../yii"

function say() {
    echo " $1"
}

function printerr() {
    echo " $RED$1$RESET"
}

function printok() {
    echo " $GREEN$1$RESET"
}

function printstatus() {
    PAD=".................................................."
    printf "> %s%s" "$1" "${PAD:${#1}}"
}

function statuserr() {
    echo "$RED$1$RESET"
}

function statusok() {
    echo "$GREEN$1$RESET"
}

function ask() {
    echo -n " $1"
    read ANSWER
}

function askpwd() {
    echo -n " $1"
    read -s ANSWER
    echo ""
}

function require() {
    echo -n " $1"
    read ANSWER
    while [ "$ANSWER" == "" ]
    do
        printerr "Please provide required option."
        echo -n " $1"
        read ANSWER
    done
}

function requirepwd() {
    echo -n " $1"
    read -s ANSWER
    echo ""
    while [ "$ANSWER" == "" ]
    do
        printerr "Please provide required option."
        echo -n " $1"
        read -s ANSWER
        echo ""
    done
}

function cls() {
    echo -en "\ec"
}

function hr() {
    c=$(tput cols)
    for i in $(seq 1 $c)
    do 
        echo -n "$1"
    done
}

function printtop () {
    cls
    hr "*"
    say "WEBWORK INSTALLATION WIZARD"
    hr "*"
}

function printstage () {
    printtop
    say "Stage $STAGE of 3: $1"
    hr "-"
}

function chsuccess () {
    if [ $1 -ne 0 ]
    then
        statuserr "failed"
        printerr "Error while executing command! Aborting..."
        exit
    else
        statusok "$2"
    fi
}

function cherrors () {
    if [ $1 -ne 0 ]
    then
        statuserr "failed"
        printerr "Error while executing command! Aborting..."
        exit
    fi
}

function setupdir () {
    printstatus "Making '$1' directory"

    if [ ! -d "$1" ]
    then
        mkdir "$1"
        chsuccess $? "created"
    else
        statusok "exists"
    fi

    printstatus "Setting up permissions for '$1'"
    if [ $ISROOT -ne 0 ]
    then
        chmod 755 "$1" &> "/dev/null"
        chsuccess $? "success"
    else
        chmod 777 "$1" &> "/dev/null"
        chsuccess $? "success"
    fi
    
    printstatus "Setting up owner for '$1'"
    if [ $ISROOT -ne 0 ]
    then
        chown "$WWWUSER" "$1" &> "/dev/null"
        chsuccess $? "success"
    else
        statuserr "no root privileges"
    fi
}

printtop
echo ""
echo ""
say "Welcome to Webwork installation wizard!"

if [ ! -d "protected" ]
then
    printerr "Directory 'protected' is not found! Please make sure working directory is correct."
    exit
fi

if [ $EUID -ne 0 ]
then
   printerr "No root privileges! Some features may not be available."
   ISROOT=0
fi

ask "Hit Enter to continue..."

########################################################################

printstage "Preparing environment"

if [ $ISROOT -ne 0 ]
then
    ask "Specify web user [$WWWUSER]: "
    if [ "$ANSWER" != "" ]
    then
        WWWUSER="$ANSWER"
    fi
fi

printstatus "Yii framework"
while [ ! -f "$YIIPATH/yii.php" ]
do
    statuserr "not installed"
    ask "Please, install Yii framework (version 1.1.15 is recommended) to '$YIIPATH' then hit Enter."
    printstatus "Yii framework"
done
statusok "installed"

setupdir "assets"
setupdir "protected/runtime"
setupdir "uploads"

echo ""
ask "Hit Enter to continue..."

########################################################################

STAGE=2
printstage "Preparing database"

require "Specify database name: "
printf -v DBNAME "%q" "$ANSWER"
printf -v DBNAME "%q" "$DBNAME"

require "Specify database username: "
printf -v DBUSER "%q" "$ANSWER"
printf -v DBUSER "%q" "$DBUSER"

requirepwd "Specify database password: "
printf -v DBPASS "%q" "$ANSWER"
printf -v DBPASS "%q" "$DBPASS"

printstatus "Writing configuration"

cp "protected/config/main.dest.php" "protected/config/main.php" &> "/dev/null"
cherrors $?

cp "protected/config/console.dest.php" "protected/config/console.php" &> "/dev/null"
cherrors $?

sed -i "s/__DBNAME__/$DBNAME/g; s/__DBUSER__/$DBUSER/g; s/__DBPASS__/$DBPASS/g" "protected/config/main.php" &> "/dev/null"
cherrors $?

sed -i "s/__DBNAME__/$DBNAME/g; s/__DBUSER__/$DBUSER/g; s/__DBPASS__/$DBPASS/g" "protected/config/console.php" &> "/dev/null"
cherrors $?

statusok "done"

printstatus "Running migrations"

$PHP "yiic.php" "migrate"

