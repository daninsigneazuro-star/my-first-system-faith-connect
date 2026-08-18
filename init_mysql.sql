-- Initialize MySQL system tables
SOURCE C:\xampp\mysql\share\mysql_system_tables.sql;
SOURCE C:\xampp\mysql\share\mysql_system_tables_data.sql;
CREATE DATABASE IF NOT EXISTS faith_connect;
USE faith_connect;
SOURCE C:\xampp\htdocs\faith-connect\database\schema.sql;
SOURCE C:\xampp\htdocs\faith-connect\database\seed.sql;
