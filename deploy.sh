#!/bin/bash

# Color declaration
COLOR='\033[0;34m'
RESET='\033[0m'

# Stop on errors
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
echo -e "#        Checking if the database is up to date.         #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

php www/index.php orm:schema-tool:update --dump-sql

echo -e "\nDo you wish to forcibly update database schema?"

select term in "Yes" "No"; do

	if [ $term = "No" ]; then
		break
	fi

	php www/index.php orm:schema-tool:update --force > /dev/null
	echo -e "\nDatabase schema has been updated."

	break

done

echo -e ""
php www/index.php orm:generate-proxies


echo -e "${COLOR}"
echo -e "##########################################################"
echo -e "#                                                        #"
echo -e "#            Clearing out application cache.             #"
echo -e "#                                                        #"
echo -e "##########################################################"
echo -e "${RESET}"

rm -rf temp/cache/*
darwin fix --no-interaction

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
