#!/bin/sh
# app name , change to your name
#cd $(dirname $0)
APP_NAME="misfz"

# ==== NO NEED TO CHANGE FOR iknow/php/app/* ====
rm -rf output

mkdir -p output/$APP_NAME
#mkdir -p output/template/$APP_NAME
#mkdir -p output/webroot/static/$APP_NAME

#cp -r actions controllers library models plugins Bootstrap.php output/$APP_NAME

cp -r Bootstrap.php actions controllers models library script output/$APP_NAME

#cp -r template/*  output/template/$APP_NAME
#cp -r static/* output/webroot/static/$APP_NAME
cp -r conf output/

cp -r index.php  output/

cd output

find ./ -type d -name .svn |xargs -i rm -rf {}
find ./  -name "*.php~" |xargs -i rm -rf {}

mkdir -p webroot/$APP_NAME
mkdir -p app
mkdir -p conf_tmp/app/$APP_NAME

mv index.php webroot/$APP_NAME/
mv $APP_NAME app/
mv conf/* conf_tmp/app/$APP_NAME/
rm -rf conf
mv conf_tmp conf

#新版archer上线不需要打包了
tar -zcf $APP_NAME.tar.gz  app/ webroot/ conf/
rm -rf app webroot conf 
