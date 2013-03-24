#!/bin/bash

VERSIONFILE='src/AppConfig/Version.php'
TMPVFILE='.tmpversion.php'
COMPOSERFILE='composer.json'
TMPCFILE='.tmpcomposer.json'

usage() {
  echo "Usage: $0 <major> <minor> <patch>"
}

if [ $# -ne 3 ];
then
  usage
  exit 1
fi

if ! sed -e 's/const MAJOR =.*;/const MAJOR = '$1';/g' -e 's/const MINOR =.*;/const MINOR = '$2';/g' -e 's/const PATCH =.*;/const PATCH = '$3';/g' $VERSIONFILE > $TMPVFILE;
then
  echo "Could not replace version in $VERSIONFILE"
  exit 2
fi

VER=$1.$2.$3

if ! sed -e 's/\"version\"\: \".*\"/\"version\"\: \"'$VER'\"/g' $COMPOSERFILE > $TMPCFILE;
then
  echo "Could not replace version in $COMPOSERFILE"
  exit 2
fi

mv $TMPVFILE $VERSIONFILE
mv $TMPCFILE $COMPOSERFILE

git add $VERSIONFILE $COMPOSERFILE
git commit -m "Bumping version number to $VER" $VERSIONFILE $COMPOSERFILE

echo "Successfully bumped version to $VER"
