.ONESHELL:
.SILENT:

# Output colorization
RESET := \033[0m
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m

# Determine available components
IS_COMPOSER := $(shell which composer)
IS_DARWIN := $(shell which darwin)
IS_BOWER := $(shell which bower)


.PHONY: help
help:
	echo "Application deployment script to make sure all needed tasks"
	echo "are taken care of when deploying or updating application."
	echo ""
	echo "${YELLOW}Usage:${RESET} make deploy"
	echo ""
	echo "${YELLOW}Available targets:${RESET}"
	echo "  ${GREEN}help${RESET}              This help"
	echo "  ${GREEN}deploy${RESET}            Execute all deploy tasks"
	echo ""
	echo " ${YELLOW}source${RESET}             Update source files"
	echo "  ${GREEN}code${RESET}              Pull repository changes"
	echo "  ${GREEN}install${RESET}           Install composer dependencies"
	echo "  ${GREEN}upgrade${RESET}           Upgrade composer dependencies"
	echo "  ${GREEN}assets${RESET}            Update bower dependencies"
	echo ""
	echo " ${YELLOW}database${RESET}           Update database structure"
	echo "  ${GREEN}schema.migrate${RESET}    Migrate to latest database version"
	echo "  ${GREEN}schema.diff${RESET}       Create new migration from schema differences"
	echo "  ${GREEN}schema.update${RESET}     Update database schema ignoring migrations"
	echo "  ${GREEN}schema.dump${RESET}       Dump SQLs of pending schema structure update"
	echo "  ${GREEN}proxies${RESET}           Generate new entity proxies"
	echo ""
	echo " ${YELLOW}clean${RESET}              Clear application cache and fix permissions"
	echo "  ${GREEN}clean.cache${RESET}       Remove cached files in temp"
	echo "  ${GREEN}clean.logs${RESET}        Remove all error logs"
	echo "  ${GREEN}clean.sessions${RESET}    Remove all user sessions"
	echo "  ${GREEN}clean.proxies${RESET}     Remove generated entity proxies"
	echo "  ${GREEN}autoload${RESET}          Generate application autoloader from composer"
	echo "  ${GREEN}darwin${RESET}            Fix permissions of the files"
	echo ""

.PHONY: deploy
deploy: source database clean


.PHONY: title.source
title.source:
	echo "${BLUE}"
	echo "##############################################################"
	echo "#                                                            #"
	echo "#          Updating source code of the application.          #"
	echo "#                                                            #"
	echo "##############################################################"
	echo "${RESET}"

.PHONY: source
source: title.source code install assets
	mkdir -p -m 0755 temp/sessions
	mkdir -p -m 0755 www/static

.PHONY: code
code:
	git pull --ff-only --no-stat
	echo ""

.PHONY: install
install:
	test ! -e "$(IS_COMPOSER)" || composer install --no-interaction --optimize-autoloader --prefer-dist
	echo ""

.PHONY: upgrade
upgrade:
	test ! -e "$(IS_COMPOSER)" || composer update --optimize-autoloader --prefer-dist
	echo ""

.PHONY: assets
assets:
	test ! -e "$(IS_BOWER)" || bower update --allow-root
	echo ""


.PHONY: title.database
title.database:
	echo "${BLUE}"
	echo "##############################################################"
	echo "#                                                            #"
	echo "#        Updating database schema of the application.        #"
	echo "#                                                            #"
	echo "##############################################################"
	echo "${RESET}"

.PHONY: database
database: title.database clean.cache schema.migrate proxies

.PHONY: schema.migrate
schema.migrate:
	php www/index.php migrations:migrate --no-interaction
	echo ""

.PHONY: schema.diff
schema.diff:
	php www/index.php migrations:diff

.PHONY: schema.update
schema.update:
	php www/index.php orm:schema-tool:update --force

.PHONY: schema.dump
schema.dump:
	php www/index.php orm:schema-tool:update --dump-sql

.PHONY: proxies
proxies: clean.proxies
	php www/index.php orm:generate-proxies


.PHONY: title.clean
title.clean:
	echo "${BLUE}"
	echo "##############################################################"
	echo "#                                                            #"
	echo "#              Clearing out application cache.               #"
	echo "#                                                            #"
	echo "##############################################################"
	echo "${RESET}"

.PHONY: clean
clean: title.clean clean.cache autoload darwin

.PHONY: clean.cache
clean.cache:
	rm -rf temp/cache/*
	rm -rf www/static/*

.PHONY: clean.logs
clean.logs:
	find log/* -not -name '.gitignore' -print0 | xargs -0 rm -rf --

.PHONY: clean.sessions
clean.sessions:
	rm -rf temp/sessions/*

.PHONY: clean.proxies
clean.proxies:
	rm -rf temp/proxies/*

.PHONY: autoload
autoload:
	test ! -e "$(IS_COMPOSER)" || composer dump-autoload --optimize

.PHONY: darwin
darwin:
	test ! -e "$(IS_DARWIN)" || darwin fix --no-interaction
