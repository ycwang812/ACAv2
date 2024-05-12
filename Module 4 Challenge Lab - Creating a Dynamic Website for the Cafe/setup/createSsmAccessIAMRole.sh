
#
# Create an IAM role that gives EC2 instances access to AWS Systems Manager,
# and create an instance profile that uses it.
#
echo
echo "Creating SSMaccess IAM role..."
aws iam create-role --role-name SSMaccess --assume-role-policy-document file://ec2-assume-role-trust-policy.json

echo
echo "Attaching SSM access policy to IAM role..."
aws iam put-role-policy --role-name SSMaccess --policy-name SSM-Permissions --policy-document file://ssm-access-policy.json

echo
echo "Creating instance profile..."
aws iam create-instance-profile --instance-profile-name SSMaccess-profile

echo
echo "Adding SSMAccess role to instance profile..."
aws iam add-role-to-instance-profile --instance-profile-name SSMaccess-profile --role-name SSMaccess

#
# Launch an instance with the SSM profile.
#
# Example: aws ec2 run-instances --image-id ami-11aa22bb --iam-instance-profile Name="s3access-profile" --key-name my-key-pair --security-groups my-security-group --subnet-id subnet-1a2b3c4d
instanceDetails=$(aws ec2 run-instances \
--image-id $imageId \
--count 1 \
--instance-type $instanceType \
--region us-west-1 \
--subnet-id $subnetId \
--security-group-ids $securityGroup \
--tag-specifications 'ResourceType=instance,Tags=[{Key=Name,Value=cafeserver}]' \
--associate-public-ip-address \
--profile $profile \
--iam-instance-profile Name="SSMaccess-profile"
--user-data file://create-lamp-instance-userdata.txt )
