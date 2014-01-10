#!/bin/sh

USER='mailserver'
PASSWD='boo1aKeisoot'
BASE='itbase'
HEADER='\nContent-Type: text/plain; charset=UTF-8'

mysql -N -u $USER -p${PASSWD} -B $BASE < test.sql | awk -F "\t" -v HD="$HEADER" '{system("echo \""$3"\" | mailx -s \""$1 HD"\" "$2)}'
