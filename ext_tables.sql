#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_multidomainpublishing_visibility varchar(100) DEFAULT '0' NOT NULL
);


#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
	tx_multidomainpublishing_visibility varchar(100) DEFAULT '0' NOT NULL
);


#
# Table structure for table 'sys_domain'
#
CREATE TABLE sys_domain (
	tx_multidomainpublishing_pagetype int(11) DEFAULT '0' NOT NULL,
	tx_multidomainpublishing_mode int(11) DEFAULT '0' NOT NULL

);


