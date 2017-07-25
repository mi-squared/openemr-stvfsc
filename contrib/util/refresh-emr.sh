#!/bin/bash
#
# Update the current directory from a git OPENEMR based repo (argument 1) 
# display repo and branch and wait to continue
# fixes owner and cleans out cache dirs
# added DRYRUN option2 to just show what would happen

# BE AWARE OF THE --delete-after script if you don't want the rsync to 'clean up' the target

# Defaults
GITDIR=/opt/gitrepos
EXCLUDE_SQL="--exclude sqlconf.php"
EXCLUDE_SITES=
CFG_MODE=444

REPO=openemr

OPTION=$1
if [ "${OPTION}" = "" ]; then
    echo Will EXCLUDE sqlconf.php 
fi

echo Note ${REPO} ${OPTION}:

if [ "${OPTION}" = "new" ]; then
    echo Will INCLUDE sqlconf.php
    EXCLUDE_SQL=    
    CFG_MODE=766
fi  

if [ "${OPTION}" = "clean" ]; then
    echo Will delete unmatched files from target except in sites/
    DELETE="--delete-after"
    EXCLUDE_SITES="--exclude sites"    
fi
     
if [ "${OPTION}" = "dryrun" ]; then
    echo Will not actually DO ANYTHING - DRY-RUN
    DRYRUN="--dry-run"    
fi
      
USRGRP=www-data:www-data

echo Update will exclude .git, *~ and nbproject file

echo Update ${GITDIR}/${REPO} from
(cd ${GITDIR}/${REPO}; git branch | grep \*) 
echo to localdir
pwd

ISEMR=`echo $PWD | sed s+/.*/openemr$+YES+g`

if [ "${ISEMR}" != "YES" ]; then
  echo Must be at ROOT OPENEMR level - Continue?
fi

read
   
# Save source repo and branch name
(cd ${GITDIR}/${REPO}; pwd > GITSOURCEBRANCH.log; git branch | grep \* >> GITSOURCEBRANCH.log)

echo Starting...

sudo rm -rf interface/main/calendar/modules/PostCalendar/pntemplates/compiled/*
sudo rm -rf interface/main/calendar/modules/PostCalendar/pntemplates/cache/*
sudo rm -rf gacl/admin/templates_c/*

sudo rsync -i --recursive ${EXCLUDE_SQL} ${EXCLUDE_SITES} --exclude nbproject --exclude .git --exclude *~ ${DELETE} ${DRYRUN} ${GITDIR}/${REPO}/* . | grep -v "f\.\."

# Update Ownership
sudo chown -R ${USRGRP} *
sudo chmod ${CFG_MODE} sites/default/sqlconf.php
sudo chmod ${CFG_MODE} interface/modules/zend_modules/config/application.config.php
sudo chmod 766 sites/default/letter_templates
chmod 766 interface/main/calendar/modules/PostCalendar/pntemplates/compiled
chmod 766 interface/main/calendar/modules/PostCalendar/pntemplates/cache
chmod 766 gacl/admin/templates_c
echo mods/owners fixed.
# cleanup source branch name
(cd ${GITDIR}/${REPO}; rm GITSOURCEBRANCH.log)
echo Done.

