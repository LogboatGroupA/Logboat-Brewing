DROP TABLE IF EXISTS beerUsesIngredient;
DROP TABLE IF EXISTS fermentation;
DROP TABLE IF EXISTS fermentationType;
DROP TABLE IF EXISTS kegOrder;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS keg;
DROP TABLE IF EXISTS brew;
DROP TABLE IF EXISTS beer;
DROP TABLE IF EXISTS beerType;
DROP TABLE IF EXISTS ingredient;
DROP TABLE IF EXISTS unit;
DROP TABLE IF EXISTS user;
DROP VIEW IF EXISTS userSafe;


/*
 * User
 */
CREATE TABLE user (
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username varchar(15) NOT NULL UNIQUE,
    password varchar(256) NOT NULL,
    isAdmin boolean NOT NULL DEFAULT false,
    created timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=INNODB;

/* Remove these for production application? These are here for our convenience right now. 
   Not sure how else to seed an admin account. */
INSERT INTO user VALUES
    (DEFAULT, 'admin', '$2y$10$N5FtjNxtYAxB0WuoXHU.eOVVGeL3.kqtuSFKKnOpbEZ6vdlph.4Py', true, DEFAULT),
    (DEFAULT, 'test', '$2y$10$cDYAjrH6f/Q9SMjd5/EiNOxWzG1M/3BbNQO3NNU/0WBWzs8IxpAoe', false, DEFAULT);

CREATE VIEW userSafe AS SELECT id, username, isAdmin, created FROM user;

/*
 * Unit
 */
CREATE TABLE unit (
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(8) NOT NULL UNIQUE
) ENGINE=INNODB;

INSERT INTO unit VALUES
    (DEFAULT, 'bags'),
    (DEFAULT, 'oz'),
    (DEFAULT, 'lbs');

/*
 * Ingredient
 */
CREATE TABLE ingredient (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(30) NOT NULL,
    supplier varchar(30) DEFAULT NULL,
    quantity float NOT NULL DEFAULT 0,
    lowValue float NOT NULL DEFAULT 0,
    unitId smallint NOT NULL,
    FOREIGN KEY (unitId) REFERENCES unit (id)
) ENGINE=INNODB;

INSERT INTO ingredient VALUES
    (DEFAULT, 'Lemondrop Hop Pellets', 'Northern Brewer', 55, 2, 1),
    (DEFAULT, 'Rahr 2-row pale', 'Rahr', 50, 500, 11),
    (DEFAULT, 'Briess Caramel 6OL', 'Briess', 10, 8, 11),
    (DEFAULT, 'Briess Caramel 8OL', 'Briess', 8.6, 2.5, 1),
    (DEFAULT, 'Fawecett Pale Chocolate', 'Fawcett Pale', 10.2, 2.6, 1),
    (DEFAULT, 'English Black Malt', 'Logboat', 3.3, 2.5, 1);


/*
 * BeerType
 */
CREATE TABLE beerType (
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(20) NOT NULL UNIQUE
) ENGINE=INNODB;

INSERT INTO beerType VALUES
    (DEFAULT, 'All Grain'),
    (DEFAULT, 'Wheat'),
    (DEFAULT, 'IPA');

/*
 * Beer
 */
CREATE TABLE beer (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(50) NOT NULL,
    createdBy smallint NOT NULL,
    beerTypeId smallint NOT NULL,
    FOREIGN KEY (createdBy) REFERENCES user (id),
    FOREIGN KEY (beerTypeId) REFERENCES beerType (id)
) ENGINE=INNODB;

INSERT INTO beer VALUES
    (DEFAULT, 'Caribou Slobber Brown Ale', 1, 21),
    (DEFAULT, 'Squirrel Nutkin Ale', 1, 21),
    (DEFAULT, 'Janet''s Brown Ale', 1, 21),
    (DEFAULT, 'Shiphead', 1, 11);


/*
 * BeerUsesIngedient
 */
CREATE TABLE beerUsesIngredient (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    beerId int NOT NULL,
    ingredientId int NOT NULL,
    quantity float NOT NULL,
    FOREIGN KEY (beerId) REFERENCES beer (id),
    FOREIGN KEY (ingredientId) REFERENCES ingredient (id)
) ENGINE=INNODB;

INSERT INTO beerUsesIngredient VALUES
    (DEFAULT, 1, 11, 9.0),
    (DEFAULT, 1, 21, 4.5),
    (DEFAULT, 1, 41, 0.05),
    (DEFAULT, 11, 21, .75),
    (DEFAULT, 11, 31, .5),
    (DEFAULT, 21, 41, .25),
    (DEFAULT, 21, 11, 6.0),
    (DEFAULT, 31, 41, 0.10);


/**
 * Brew
 */
CREATE TABLE brew (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    brewStart datetime NOT NULL,
    brewEnd datetime NOT NULL,
    quantity float NOT NULL,
    beerId int NOT NULL,
    userId smallint NOT NULL,
    FOREIGN KEY (beerId) REFERENCES beer (id),
    FOREIGN KEY (userId) REFERENCES user (id)
) ENGINE=INNODB;

INSERT INTO brew VALUES
    (DEFAULT, '2015-12-02 10:00:00', '2015-12-18 10:00:00', 450, 1, 1),
    (DEFAULT, '2016-01-05 08:00:00', '2016-02-04 14:30:00', 565, 11, 1),
    (DEFAULT, '2015-12-09 09:00:00', '2015-12-15 11:00:00', 675, 11, 1),
    (DEFAULT, '2015-12-21 10:00:00', '2015-12-25 16:15:00', 888, 1, 1),
    (DEFAULT, '2015-12-08 00:00:00', '2015-12-12 09:00:00', 600, 21, 1);


/*
 * FermentationType
 */
CREATE TABLE fermentationType (
    id smallint NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name varchar(20) NOT NULL UNIQUE
) ENGINE=INNODB;

INSERT INTO fermentationType VALUES
    (DEFAULT, 'Gravity'),
    (DEFAULT, 'pH');


/*
 * Fermentation
 */
CREATE TABLE fermentation (
    id int NOT NULL AUTO_INCREMENT,
    value double NOT NULL DEFAULT 0,
    dateTime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    typeId smallint NOT NULL,
    brewId int NOT NULL,
    userId smallint NOT NULL,
    FOREIGN KEY (typeId) REFERENCES fermentationType (id),
    FOREIGN KEY (brewId) REFERENCES brew (id),
    FOREIGN KEY (userId) REFERENCES user (id),
    PRIMARY KEY (id, dateTime)
) ENGINE=INNODB;

INSERT INTO fermentation VALUES
    (DEFAULT, 13,   '2015-12-13 05:08:00', 1,   1, 1),
    (DEFAULT, 13,   '2015-12-13 05:08:00', 1,   1, 1),
    (DEFAULT, 1.08, '2015-12-13 05:08:00', 11,  1, 1),
    (DEFAULT, 1.89, '2015-12-14 13:00:00', 1,   1, 1),
    (DEFAULT, 9,    '2015-12-16 21:04:00', 1,   1, 1),
    (DEFAULT, 11,   '2015-12-16 08:04:00', 1,   1, 1),
    (DEFAULT, 1.2,  '2015-12-18 00:00:00', 11,  1, 1),
    (DEFAULT, 1.22, '2015-12-18 12:00:00', 11,  1, 1),
    (DEFAULT, 11,   '2015-12-18 12:00:00', 1,   1, 1),
    (DEFAULT, 12,   '2015-12-12 11:00:00', 1,   1, 1),
    (DEFAULT, 12,   '2015-12-12 11:00:00', 1,   1, 1),
    (DEFAULT, 1.22, '2015-12-19 13:00:00', 11,  1, 1),
    (DEFAULT, 13,   '2015-12-22 00:00:00', 1,  31, 1),
    (DEFAULT, 8,    '2015-12-23 12:00:00', 1,  31, 1),
    (DEFAULT, 1.08, '2015-12-22 09:00:00', 11, 31, 1), 
    (DEFAULT, 1.11, '2015-12-23 12:00:00', 11, 31, 1);


/**
 * Customer
 */
CREATE TABLE customer (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstName varchar(20) NOT NULL,
    lastName varchar(20) NOT NULL,
    phoneNumber varchar(12),
    email varchar(50),
    address varchar(30) NOT NULL,
    city varchar(20) NOT NULL,
    state varchar(2) NOT NULL,
    zipCode varchar(5) NOT NULL
) ENGINE=INNODB;

INSERT INTO customer VALUES
    (DEFAULT, 'Marjorie', 'Patterson', '717-965-7202', 'MarjorieJPatterson@teleworm.us', '2935 Simpson Avenue', 'Carlisle', 'PA', '17013'),
    (DEFAULT, 'Bill', 'Snider', '586-228-4053', 'BillVSnider@rhyta.com', '3256 Cherry Ridge Drive', 'Mount Clemens', 'MI', '48044');


/**
 * Keg
 */
CREATE TABLE keg (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    serialNum varchar(50) NOT NULL
) ENGINE=INNODB;

INSERT INTO keg VALUES
    (DEFAULT, 'N9TT-9G0A-B7FQ-RANC'),
    (DEFAULT, 'QK6A-JI6S-7ETR-0A6C'),
    (DEFAULT, 'SXFP-CHYK-ONI6-S89U'),
    (DEFAULT, 'XNSS-HSJW-3NGU-8XTJ'),
    (DEFAULT, 'NHLE-L6MI-4GE4-ETEV'),
    (DEFAULT, '6ETI-UIL2-9WAX-XHYO');


/**
 * KegOrder
 */
CREATE TABLE kegOrder (
    id int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pickupDate datetime NOT NULL,
    returnDate datetime NOT NULL,
    returned boolean NOT NULL DEFAULT false,
    customerId int NOT NULL,
    kegId int NOT NULL,
    brewId int NOT NULL,
    userId smallint NOT NULL,
    FOREIGN KEY (customerId) REFERENCES customer (id),
    FOREIGN KEY (kegId) REFERENCES keg (id),
    FOREIGN KEY (brewId) REFERENCES brew (id),
    FOREIGN KEY (userId) REFERENCES user (id)
) ENGINE=INNODB;

INSERT INTO kegOrder VALUES
    (DEFAULT, '2015-11-25 00:00:00', '2015-12-04 00:00:00', false, 1, 1, 1, 1),
    (DEFAULT, '2015-12-02 13:00:00', '2015-12-08 14:00:00', false, 11, 11, 11, 1);