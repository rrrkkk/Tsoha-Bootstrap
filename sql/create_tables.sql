-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE person (
	id INTEGER NOT NULL AUTO_INCREMENT,
	name VARCHAR(64),
	username VARCHAR(16),
	email VARCHAR(64),
	password VARCHAR(255),
	admin BOOLEAN,
	PRIMARY KEY (id)
);

CREATE TABLE poll (
	id INTEGER NOT NULL AUTO_INCREMENT,
	person_id INTEGER,
	name VARCHAR(64),
	startdate DATE,
	enddate DATE,
	anonymous BOOLEAN,
	FOREIGN KEY (person_id) REFERENCES person(id),
	PRIMARY KEY (id)
);

CREATE TABLE voters (
	poll_id INTEGER,
	person_id INTEGER,
	time DATETIME,
	FOREIGN KEY (poll_id) REFERENCES poll(id),
	FOREIGN KEY (person_id) REFERENCES person(id),
	PRIMARY KEY (poll_id, person_id)
);

CREATE TABLE poll_option (
	id INTEGER NOT NULL AUTO_INCREMENT,
	poll_id INTEGER,
	name VARCHAR(64),
	description VARCHAR(64),
	FOREIGN KEY (poll_id) REFERENCES poll(id),
	PRIMARY KEY (id)
);

CREATE TABLE vote (
	id INTEGER NOT NULL AUTO_INCREMENT,
	poll_option_id INTEGER,
	poll_id INTEGER,
	time DATETIME,
	FOREIGN KEY (poll_option_id) REFERENCES poll_option(id),
	FOREIGN KEY (poll_id) REFERENCES poll(id),
	PRIMARY KEY (id)
);

