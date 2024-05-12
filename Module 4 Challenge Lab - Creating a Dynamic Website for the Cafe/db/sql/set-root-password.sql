--
-- Creates a root user that can connect from any host and sets the password for all root users in Mariadb
--
USE mysql;
CREATE user 'admin'@'%';
GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%';
SET PASSWORD FOR 'admin'@'%' = PASSWORD('Re:Start!9');
--UPDATE user SET password=PASSWORD("Re:Start!9") WHERE user='admin';
flush privileges;