drop table if exists vidstore;
create table vidstore (
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(255) NOT NULL,
	category varchar(255),
	length int(11) unsigned,
	rented bool default 1,
	PRIMARY KEY (id),
	UNIQUE(name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*

*/
INSERT INTO vidstore (name, category, length) VALUES
('Avengers: Age of Ultron', 'Action', 141),
('Furious Seven', 'Action', 137),
('Mad Max: Fury Road', 'Action', 120),
('The Avengers', 'Action', 143),
('Jupiter Ascending', 'Action', 127),
('Kingsman: The Secret Service', 'Comedy', 129),
('Mortdecai', 'Comedy', 107),
('Paul Blart: Mall Cop 2', 'Comedy', 94),
('The Wedding Ringer', 'Comedy', 101),
('Home', 'Comedy', 94),
('The Age of Adaline', 'Drama', 112),
('Ex Machina', 'Drama', 108),
('The Water Diviner', 'Drama', 111),
('Fifty Shades of Gray', 'Drama', 125),
('The Longest Ride', 'Drama', 139);

--prepared statements below

--add video

--check in video

--check out video

--delete all videos

--get all movies in given category
