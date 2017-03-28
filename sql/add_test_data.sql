-- Lisää INSERT INTO lauseet tähän tiedostoon

INSERT INTO person VALUES
(1, 'Riku Kalinen', 'riku', 'riku.kalinen@helsinki.fi', '02b353bf5358995bc7d193ed1ce9c2eaec2b694b21d2f96232c9d6a0832121d1', TRUE), -- foo123
(2, 'Testi Käyttäjä', 'test', 'foo@foo.fi', '02b353bf5358995bc7d193ed1ce9c2eaec2b694b21d2f96232c9d6a0832121d1', FALSE); -- foo123

INSERT INTO poll VALUES
(1, 1, 'Paras harjoitustyön aihe', '2017-03-19', '2017-05-19', FALSE, 1),
(2, 1, 'Kiinnostavin muumihahmo', '2017-01-01', '2017-12-31', TRUE, 3);

INSERT INTO voters VALUES
(1, 2, '2017-03-20 12:34:56');

INSERT INTO poll_option VALUES
(1, 1, 'Äänestys', 'Äänestyssovellus'),
(2, 1, 'Pelit', 'Lista peleistä'),
(3, 2, 'Muumipeikko', ''),
(4, 2, 'Nuuskamuikkunen', ''),
(5, 2, 'Pikku Myy', '');

INSERT INTO vote VALUES
(1, 1, 1, '2017-03-20 12:34:56'),
(2, 3, 2, '2017-03-20 11:34:00'),
(3, 3, 2, '2017-03-20 12:34:00'),
(4, 4, 2, '2017-03-20 12:34:01');
