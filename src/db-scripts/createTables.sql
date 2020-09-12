CREATE TABLE users (
    userid VARCHAR(128) NOT NULL PRIMARY KEY,
    username VARCHAR(128) NOT NULL UNIQUE KEY,
    password CHAR(32) NOT NULL,
    email varchar(128) NOT NULL,
    firstname VARCHAR(128) NOT NULL,
    lastname VARCHAR(128) NOT NULL
);

CREATE TABLE upload (
    id INT AUTO_INCREMENT,
    PRIMARY KEY (id),
    username VARCHAR(128),
    file_path VARCHAR(100),
    FOREIGN KEY (username) REFERENCES users(username)
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT,
    PRIMARY KEY (id),
    username VARCHAR(128),
    ts TIMESTAMP NOT NULL,
    activity_type VARCHAR(64),
    latitude FLOAT,
    longitude FLOAT,
    FOREIGN KEY (username) REFERENCES users(username)
);
