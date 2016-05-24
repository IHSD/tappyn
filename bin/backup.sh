#!/bin/bash
#
# Usage:
# ./backup.sh -p=password -d=database_name
#
# ./backup.sh -p=davol350 -d=tappyn
#
# Creates a temp directory in /tmps/mysql/dumps, and exports a zipped backup
# of each table in the specified database. It then uploads those to Amazon S3,
# and deletes the tmp storage of backups
#

for i in "$@"
do
case $i in
    -p=*|--password=*)
    PASSWORD="${i#*=}"

    ;;
    -d=*|--database=*)
    DATABASE="${i#*=}"
    ;;
    *)
            # unknown option
    ;;
esac
done


echo DATABASE = ${DATABASE}
echo PASSWORD = ${PASSWORD}

# Set required variables
bucket="s3://${DATABASE}"
stamp=`date +%Y-%m-%d-%T`
tmp_dir="/tmp/mysql/dumps/$stamp"
mkdir $tmp_dir
echo "Backing up ${DATABASE} to $bucket"
tables=`mysql -u root -p${PASSWORD} ${DATABASE} -e "SHOW TABLES;" | tr -d "| " | grep -v Tables_in`

for table in $tables; do
    tmp_file="$tmp_dir/$table.sql"
    location="$bucket/$stamp/$table.sql"
    echo -e "Exporting $table"
    mysqldump -u root -p${PASSWORD} ${DATABASE} "$table" --force --opt --no-create-db --no-create-info --complete-insert | gzip -c > "$tmp_file"
    echo -e "Uploading to s3"
    s3cmd put "$tmp_file" "$location"
done
echo -e "Cleaning up"
`rm -r $tmp_dir`
