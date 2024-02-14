<?php

$main_url = 'http://localhost:8080/agriculture/';

$time_created = time();

session_start();
$server = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'agriculture';

$conn = mysqli_connect($server, $db_user, $db_pass, $db_name);

$allowed_image_extension = array(
	"png",
	"jpg",
	"jpeg"
);

$stock_weight = 'KGs';
$mun_in_kg = 50;

// User Roles
# Role------------------Role----Type--------Name
# |						|		|			|
# Super Admin-----------0-------0-----------Director Seed KPK
# |						|		|			|
# Admin-----------------0-------1-----------District Director Agriculture
# |						|		|			|
# Extension Manager-----1-------0-----------Procurement Officer
# |						|		|			|
# Store Keeper----------1-------1-----------Store Keeper



// Stock Active Status
# Status Name-------Value
# |					|
# Inactive----------0
# |					|
# Active------------1
# |					|
# Completed---------2
# |					|
# Fumigation--------3



// Stock Status
# Status Name-------Value
# |					|
# Uncleaned---------0
# |					|
# Cleaning----------1
# |					|
# Cleaned-----------2
# |					|
# Fumigation--------3


// Receive Status
# Status Name-------Value
# |					|
# On Way------------0
# |					|
# Received----------1
# |					|
# Rejected----------2



// Transaction Type
# Type------------------------------Value
# |									|
# Procure Stock Cost----------------0
# |									|
# Fumigate Stock Cost---------------1
# |									|
# Cleaning Stock Cost---------------2
# |									|
# Supply Stock Price----------------3
# |									|
# Supply Stock Cost-----------------4
# |									|
# Receive Stock Price---------------5
# |									|
# Receive Stock Cost----------------6
# |									|
# Other-----------------------------7
# |									|
# Allot Budget----------------------9




// Transaction Flow
# Flow----------------------Value
# |							|
# Out Flow/Debit------------0
# |							|
# In Flow/Credit------------1



// Transaction Active Status
# Status Name-------------------Value
# |								|
# Inactive----------------------0
# |								|
# Active------------------------1
# |								|
# Pending Transaction-----------2



// Stock Transaction Type:
# Type Name------------------------------------------------------Value
# |																 |
# Purchase stock from farmer {FARMER_CNIC}-----------------------0
# |																 |
# Supply stock to farmer {FARMER_CNIC}---------------------------1
# |																 |
# Purchase stock from other province {PROVINCE}------------------2
# |																 |
# Supply stock to other province {PROVINCE}----------------------3
# |																 |
# Received stock from other extension {EXTENSION_NAME}-----------4
# |																 |
# Supply stock from other extension {EXTENSION_NAME}-------------5
# |																 |
# Cleaning Stock Outcomes----------------------------------------|
# {LOT_NUMBER}---------------------------------------------------|
# {STOCK_TYPE}---------------------------------------------------|
# {CLASS}--------------------------------------------------------|
# {VARIETY}------------------------------------------------------|
# {PROCESSING_QTY}-----------------------------------------------|
# {CLEANING_COST}------------------------------------------------|
# {EXTENSION_NAME}-----------------------------------------------|
# {USER_ID}------------------------------------------------------6
# |																 |
# fumigation stock-----------------------------------------------|
# {LOT_NUMBER}---------------------------------------------------|
# {STOCK_TYPE}---------------------------------------------------|
# {CLASS}--------------------------------------------------------|
# {VARIETY}------------------------------------------------------|
# {PROCESSING_QTY}-----------------------------------------------|
# {CLEANING_COST}------------------------------------------------|
# {EXTENSION_NAME}-----------------------------------------------|
# {USER_ID}------------------------------------------------------7
# |																 |
# Send sample to FADF--------------------------------------------|
# {LOT_NUMBER}---------------------------------------------------|
# {STOCK_TYPE}---------------------------------------------------|
# {CLASS}--------------------------------------------------------|
# {VARIETY}------------------------------------------------------|
# {SAMPLE_QTY}---------------------------------------------------|
# {EXTENSION_NAME}-----------------------------------------------|
# {USER_ID}------------------------------------------------------8
# |																 |
# change class on the report based on FADF-----------------------9



// Activity Type
# Type------------------------------Value
# |									|
# Procure Stock---------------------0
# |									|
# Fumigate Stock--------------------1
# |									|
# Clean Stock-----------------------2
# |									|
# Supply Stock----------------------3

?>