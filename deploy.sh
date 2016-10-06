#!/bin/bash

# Prepare enviroment
COLOR='\033[0;34m'
RESET='\033[0m'

clear
set -e


echo -e "${COLOR}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#        Updating source code of the application.        #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

git pull --ff-only --no-stat
echo -e ""

composer install --no-interaction --prefer-source
echo -e ""

bower update --allow-root


echo -e "${COLOR}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#      Updating database schema of the application.      #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

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


echo -e "${COLOR}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#            Clearing out application cache.             #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

rm -rf temp/cache/*


which darwin &> /dev/null

if [ $? -eq 0 ]; then

	darwin fix --no-interaction

fi


echo -e "\nWould you also like to clear out user sessions?"

select term in "Yes" "No"; do

	if [ $term = "No" ]; then
		break
	fi

	rm -rf temp/sessions/* > /dev/null
	echo -e "\nUser sessions have been cleared out."

	break

done


echo -e "\nDeploy has been finished."
exit 0
