#!/bin/bash
#
# Script to set the parameters for the cafe application in the Parameter Store.
#
#
# Get the region where the instance is running, and set as it the default AWS region.
# This ensures that we are using the Parameter Store in the region where the instance is running.
#
echo
echo "Setting the default AWS region..."
az=$(curl http://169.254.169.254/latest/meta-data/placement/availability-zone)
region=${az%?}
export AWS_DEFAULT_REGION=$region
echo "Region =" $AWS_DEFAULT_REGION
#
# Set the application parameter values.
#
publicDNS=$(curl http://169.254.169.254/latest/meta-data/public-hostname)
echo "Public DNS =" $publicDNS
echo
echo "Setting the application parameter values in the Parameter Store..."
aws ssm put-parameter --name "/cafe/showServerInfo" --type "String" --value "false" --description "Show Server Information Flag" --overwrite
aws ssm put-parameter --name "/cafe/timeZone" --type "String" --value "America/New_York" --description "Time Zone" --overwrite
aws ssm put-parameter --name "/cafe/currency" --type "String" --value "$" --description "Currency Symbol" --overwrite
aws ssm put-parameter --name "/cafe/dbUrl" --type "String" --value $publicDNS --description "Database URL" --overwrite
aws ssm put-parameter --name "/cafe/dbName" --type "String" --value "cafe_db" --description "Database Name" --overwrite
aws ssm put-parameter --name "/cafe/dbUser" --type "String" --value "admin" --description "Database User Name" --overwrite
aws ssm put-parameter --name "/cafe/dbPassword" --type "String" --value "Re:Start!9" --description "Database Password" --overwrite

echo
echo "Application Parameter Setup script completed."
echo
