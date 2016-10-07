#!/bin/bash

function source_code() {
	git pull --ff-only --no-stat
	echo -e ""

	composer install --no-interaction --prefer-source
	echo -e ""

	bower update --allow-root
}

function database_schema() {
	# Cache clean up to prevent some issues
	rm -rf temp/proxies/*
	rm -rf temp/cache/*

	SCHEMA_UPDATE=$(php www/index.php orm:schema-tool:update --dump-sql)
	SCHEMA_REGEX="^Nothing to update"

	if [[ ! $SCHEMA_UPDATE =~ $SCHEMA_REGEX ]]; then
		php www/index.php orm:schema-tool:update --dump-sql
   		echo -e "\nDo you wish to execute above queries to update database schema?"

		select term in "Yes" "No"; do
			if [ $term = "No" ]; then
				break
			fi

			php www/index.php orm:schema-tool:update --force > /dev/null
			echo -e "\nDatabase schema has been updated.\n"
			break
		done
	else
		echo -e "Already up-to-date.\n"
	fi

	php www/index.php orm:generate-proxies
}

function clear_cache() {
	rm -rf temp/cache/*

	if [ $KILL_SESSIONS -eq 1 ]; then
		rm -rf temp/sessions/* > /dev/null
		echo -e "User sessions have been killed.\n"
	fi

	which darwin &> /dev/null

	if [ $? -eq 0 ]; then
		darwin fix --no-interaction
	fi
}

function help() {
	echo -e "Application deployment script to make sure all needed tasks"
	echo -e "are taken care of when deploying or updating application."
	echo -e ""
	echo -e "${YLLOW}Usage:${RESET} ./deploy.sh -sdk"
	echo -e ""
	echo -e "${YLLOW}Available options:${RESET}"
	echo -e "  ${GREEN}-c${RESET}	Skip cache clearing"
	echo -e "  ${GREEN}-d${RESET}	Skip database schema update"
	echo -e "  ${GREEN}-s${RESET}	Skip source code update"
	echo -e "  ${GREEN}-k${RESET}	Kill all user sessions"
	echo -e "  ${GREEN}-h${RESET}	This help"
	echo -e ""
	exit 0
}


# Prepare enviroment
GREEN='\033[0;32m'
YLLOW='\033[0;33m'
LBLUE='\033[0;34m'
RESET='\033[0m'

SKIP_SOURCE=0
SKIP_DATABASE=0
SKIP_CACHE=0
KILL_SESSIONS=0

while getopts 'cdhsk' flag; do
	case "${flag}" in
		c) SKIP_CACHE=1 ;;
		d) SKIP_DATABASE=1 ;;
		s) SKIP_SOURCE=1 ;;
		k) KILL_SESSIONS=1 ;;

		h) help ;;
	esac
done

set -e
clear


echo -e "${LBLUE}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#        Updating source code of the application.        #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

if [ $SKIP_SOURCE -eq 0 ]; then
	source_code
else
	echo -e "Skipped."
fi



echo -e "${LBLUE}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#      Updating database schema of the application.      #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

if [ $SKIP_DATABASE -eq 0 ]; then
	database_schema
else
	echo -e "Skipped."
fi


echo -e "${LBLUE}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#            Clearing out application cache.             #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

if [ $SKIP_CACHE -eq 0 ]; then
	clear_cache
else
	echo -e "Skipped."
fi


echo -e "\nDeploy has been finished."
exit 0
