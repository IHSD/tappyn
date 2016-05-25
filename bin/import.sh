#!/bin/bash

#=================================
# Importing database backups
#=================================
# Arguments:
#   -u | --user          Database User
#   -p | --password      Password
#   -d | --database      Which database to backup
#   -b | --backup        Which backup we want to restore
#
# Based on the backup supplied, we check to see if it already exists on the server.
# If it does not, we yank it from S3 using the configured s3cmd tool. Once we have
# the backup stored locally, we iterate through each existent table and
# upload the sql file into MySQL.


for i in "$@"
do
case $i in
    -p=*|--password=*)
    PASSWORD="${i#*=}"

    ;;
    -d=*|--database=*)
    DATABASE="${i#*=}"
    ;;
    -b=*|--backup=*)
    BACKUP="${i#*=}"

    ;;
    -u=*|--user=*)
    USER="${i#*=}"

    ;;
    *)
            # unknown option
    ;;
esac
done


tables=`mysql -u ${USER} -p${PASSWORD} ${DATABASE} -e "SHOW TABLES;" | tr -d "| " | grep -v Tables_in`
get_dir="/tmp/mysql/backups/${BACKUP}"

if [ ! -d "$get_dir" ]; then
    echo -e "Making directory $get_dir"
    `mkdir $get_dir`

    for table in $tables
    do
        echo -e "Downloading $table.sql.gz from S3"
        `s3cmd get s3://feedly/${BACKUP}/$table.sql.gz "$get_dir/$table.sql.gz"`
        echo -e "Unzipping sql backup"
        `gzip -d "$get_dir/$table.sql.gz"`
        echo -e "Downloaded successfully"
    done
fi

for table in $tables
do
    echo -e "Importing $table.sql"
    `mysql -u ${USER} -p${PASSWORD} "${DATABASE}" < "$get_dir/$table.sql"`
done
# Now we have our downloaded backups, let's proceed to import them into the database
