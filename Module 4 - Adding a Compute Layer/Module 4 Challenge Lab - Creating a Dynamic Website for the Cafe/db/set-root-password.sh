
#!/bin/bash
#
# Script to set the Mariadb root user password right after database installation.
#
# Check the set-root-password.log file after running it to verify successful execution.
# 
sudo mysql --user=root --verbose < sql/set-root-password.sql > set-root-password.log

echo
echo "Set Root Password script completed."
echo "Please check the set-root-password.log file to verify successful execution."
echo
