drop table if exists vidstore;
create table vidstore (
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(255) NOT NULL,
	category varchar(255),
	length int(11),
	rented bool,
	PRIMARY KEY (id),
	UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*
how to initialize bool to 1?
how to make name required? (besines not null)
how to make sure length is positive?
*/
