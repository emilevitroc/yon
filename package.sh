#!/bin/bash

WORK_DIR="packages"
OUT_DIRECTORY="YESorNO-Admin"
GIT_REMOTE=`git remote -v | grep fetch | awk '{print $2}'`

echo "ddddd"
if [ ! -d $WORK_DIR ]
then
    mkdir $WORK_DIR
fi
cd $WORK_DIR
echo "ddddd"
rm -rf $OUT_DIRECTORY
echo "ddddd"
git clone $GIT_REMOTE $OUT_DIRECTORY || exit
echo "ddddd"
cd $OUT_DIRECTORY || exit
SHORT_COMMIT_HASH=`git rev-parse --short HEAD`
CURRENT_BRANCH=`git rev-parse --abbrev-ref HEAD`
ZIP_FILE="$CURRENT_BRANCH-$SHORT_COMMIT_HASH.zip"

cd -
zip -r $ZIP_FILE $OUT_DIRECTORY && rm -rf $OUT_DIRECTORY
echo ""
echo ""
echo "Packaged to $WORK_DIR/$ZIP_FILE"
