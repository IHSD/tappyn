#!/bin/bash
for i in "$@"
do
case $i in
    -p=*|--password=*)
    PASSWORD="${i#*=}"

    ;;
    -d=*|--database=*)
    DATABASE="${i#*=}"

    ;;
    -u=*|--user=*)
    USER="${i#*=}"

    ;;
    *)
            # unknown option
    ;;
esac
done
echo DATABASE = ${DATABASE}
echo PASSWORD = ${PASSWORD}
echo USER = ${USER}
# Set required variables
bucket="s3://${DATABASE}"
stamp=`date +%s`
tmp_dir="/tmp/mysql/dumps/$stamp"
mkdir $tmp_dir
echo "Backing up ${DATABASE} to $bucket"
tables=`mysql -u ${USER} -p${PASSWORD} ${DATABASE} -e "SHOW TABLES;" | tr -d "| " | grep -v Tables_in`

for table in $tables; do
    tmp_file="$tmp_dir/$table.sql.gz"
    location="$bucket/$stamp/$table.sql.gz"
    echo -e "Exporting $table"
    mysqldump -u ${USER} -p${PASSWORD} ${DATABASE} "$table" --force --opt --no-create-db --no-create-info --complete-insert | gzip -c > "$tmp_file"
    echo -e "Uploading to s3"
    s3cmd put "$tmp_file" "$location"
done

echo -e "Cleaning up after myself"
`rm -r $tmp_dir`
