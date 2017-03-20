#!/bin/bash

source config/environment.sh

echo "Luodaan tietokantataulut..."

ssh $USERNAME@users.cs.helsinki.fi "
cd htdocs/$PROJECT_FOLDER/sql
cat drop_tables.sql create_tables.sql | mysql
exit"

echo "Valmis!"
