#!/bin/sh

###############################################################################
#  Git Script                                                                 #
#                                                                             #
#  Author: Victor Tesoura Júnior  <txsoura@yahoo.com>                         #
###############################################################################
#                                                                             #
#  This script, is to be used after a approved pull request in main repo      #
#  branch.                               				                      #
#                                                                             #
###############################################################################


git checkout develop
git fetch -p
git pull origin develop

