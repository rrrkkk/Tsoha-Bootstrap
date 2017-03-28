-- Lisää CREATE TABLE lauseet tähän tiedostoon

CREATE TABLE person (
	id SERIAL PRIMARY KEY,
	name VARCHAR(64),
	username VARCHAR(16),
	email VARCHAR(64),
	password VARCHAR(255),
	admin BOOLEAN
);

CREATE TABLE poll_type (
	id SERIAL PRIMARY KEY,
	name VARCHAR(64)
);
-- this is a static helper table, so we init it here. any significant changes here require
-- also changes to program logic.
INSERT INTO poll_type VALUES
(1, 'Näytä nykyinen kärki'),
(2, 'Näytä kaikkien ehdokkaiden äänimäärät'),
(3, 'Älä näytä mitään tietoa äänestyksen kulusta');

CREATE TABLE poll (
	id SERIAL PRIMARY KEY,
	person_id INTEGER REFERENCES person(id),
	name VARCHAR(64),
	startdate DATE,
	enddate DATE,
	anonymous BOOLEAN,
	poll_type_id INTEGER REFERENCES poll_type(id)
);

CREATE TABLE voters (
	poll_id INTEGER REFERENCES poll(id),
	person_id INTEGER REFERENCES person(id),
	time DATE,
	PRIMARY KEY (poll_id, person_id)
);

CREATE TABLE poll_option (
	id SERIAL PRIMARY KEY,
	poll_id INTEGER REFERENCES poll(id),
	name VARCHAR(64),
	description VARCHAR(64)
);

CREATE TABLE vote (
	id SERIAL PRIMARY KEY,
	poll_option_id INTEGER REFERENCES poll_option(id),
	poll_id INTEGER REFERENCES poll(id),
	time DATE
);

