#!/bin/bash

#cd /app
#./runpostdeploy private no-migrate

echo Generate Yii configurations
python /prepare_configurations

echo Done generating Yii configurations

cd /app
./runpostdeploy private no-migrate

while read -r line
do
  echo "$line"
done <"/app/common/config/params-local.php"

ln -sf /usr/share/zoneinfo/Europe/Rome /etc/localtime

exec supervisord -n
