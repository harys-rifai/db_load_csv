[root@VIDDRLXUGBQDB01 csv]# cp /home/padmin/CSV/* /data/csv/
[root@VIDDRLXUGBQDB01 csv]# for file in *_2024.csv; do   mv "$file" "${file/_2024.csv/_2025.csv}"; done
[root@VIDDRLXUGBQDB01 csv]# sh /home/postgres/scripts/6load.sh




#duplikasi:

#!/bin/bash

# Database connection parameters
DB_NAME="skeleton"
DB_USER="mis_user"
DB_HOST="127.0.0.1"
DB_PORT="5432"
DB_PASSWORD="Password09"
# Export password for psql to use
export PGPASSWORD="$DB_PASSWORD"
# Log file
LOG_FILE="/home/postgres/scripts/duplikasi.log"
# Ensure log file exists
touch "$LOG_FILE"
echo "Starting duplicate removal process..." | tee -a "$LOG_FILE"
# SQL command to delete duplicate rows from system_metrics
SQL_COMMAND="
DELETE FROM system_metrics a
USING system_metrics b
WHERE a.ctid < b.ctid
  AND a.timestamp = b.timestamp
  AND a.hostname = b.hostname
  AND a.environment = b.environment;
"
# Execute the SQL command
psql -h $DB_HOST -p $DB_PORT -U $DB_USER -d $DB_NAME -c "$SQL_COMMAND" >> "$LOG_FILE" 2>&1
echo "Duplicate removal completed." | tee -a "$LOG_FILE"

#loadfilecsv

#!/bin/bash

# Database connection parameters
DB_NAME="skeleton"
DB_USER="mis_user"
DB_HOST="127.0.0.1"
DB_PORT="5432"
DB_PASSWORD="Password09"

# Export password for psql to use
export PGPASSWORD="$DB_PASSWORD"

# Directory containing CSV files
CSV_DIR="/data/csv"


#*/16 * * * * /bin/sh /home/postgres/scripts/rsynch_csv.sh
#*/17 * * * * /bin/sh  /home/postgres/scripts/6load.sh
#*/18 * * * * /bin/sh  /root/scripts/duplicate.sh


#[root@VIDDCLXPAICDB01 ~]# cat /home/postgres/scripts/rsynch_csv.sh
#!/bin/bash
#rsync -avz -e "ssh -i /Postgres/client_136" /DBA_Monitoring/*.csv padmin@10.170.49.136:/data/csv/

#You have new mail in /var/spool/mail/root
#[root@VIDDCLXPAICDB01 ~]#

# Log file
LOG_FILE="/home/postgres/scripts/csv_loader.log"

# Ensure log file exists
touch "$LOG_FILE"

echo "Starting CSV last-row loader..." | tee -a "$LOG_FILE"

# Loop through CSV files
for file in "$CSV_DIR"/*.csv; do
    filename=$(basename "$file")

    echo "Processing last row of $filename..." | tee -a "$LOG_FILE"

    # Get the last row
    last_row=$(tail -n 1 "$file")

    # Extract only the first 8 fields
    truncated_row=$(echo "$last_row" | awk -F',' '{
        for (i=1; i<=8; i++) {
            if (i <= NF) {
                printf "%s%s", $i, (i<8 ? "," : "\n")
            } else {
                printf "%s%s", "", (i<8 ? "," : "\n")
            }
        }
    }')

    # Enrich to 12 columns
    enriched_row=$(echo "$truncated_row" | awk -F',' -v fname="$filename" 'BEGIN {OFS=","} {
        print $0, "default1", "default2", fname, "loaded"
    }')

    # Load into PostgreSQL
    echo "$enriched_row" | \
    psql -U "$DB_USER" -d "$DB_NAME" -h "$DB_HOST" -p "$DB_PORT" \
    -c "\copy system_metrics(timestamp, hostname, environment, cpu_usage, memory_usage, disk_usage, network_usage, status, extra1, extra2, file_name, load_status) FROM STDIN WITH (FORMAT csv)" >> "$LOG_FILE" 2>&1

    if [ $? -eq 0 ]; then
        echo "$filename last row loaded successfully." | tee -a "$LOG_FILE"
    else
        echo "Error loading $filename." | tee -a "$LOG_FILE"
    fi
done

echo "CSV loading completed." | tee -a "$LOG_FILE"

