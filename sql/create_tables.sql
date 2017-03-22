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

CREATE TABLE poll_type (
	id INTEGER NOT NULL AUTO_INCREMENT,
	name VARCHAR(64),
	PRIMARY KEY (id)
);
-- this is a static helper table, so we init it here. any significant changes here require
-- also changes to program logic.
INSERT INTO poll_type VALUES
(1, 'Näytä nykyinen kärki'),
(2, 'Näytä kaikkien ehdokkaiden äänimäärät'),
(3, 'Älä näytä mitään tietoa äänestyksen kulusta');

CREATE TABLE poll (
	id INTEGER NOT NULL AUTO_INCREMENT,
	person_id INTEGER,
	name VARCHAR(64),
	startdate DATE,
	enddate DATE,
	anonymous BOOLEAN,
	poll_type_id INTEGER,
	FOREIGN KEY (person_id) REFERENCES person(id),
	FOREIGN KEY (poll_type_id) REFERENCES poll_type(id),
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

