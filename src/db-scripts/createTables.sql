CREATE TABLE users (
    userid VARCHAR(128) NOT NULL PRIMARY KEY,
    username VARCHAR(128) NOT NULL UNIQUE KEY,
    password CHAR(32) NOT NULL,
    email varchar(128) NOT NULL,
    firstname VARCHAR(128) NOT NULL,
    lastname VARCHAR(128) NOT NULL,
    last_upload TIMESTAMP NULL DEFAULT NULL after,
    admin BOOLEAN DEFAULT FALSE
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT,
    PRIMARY KEY (id),
    username VARCHAR(128),
    loc_ts TIMESTAMP NOT NULL,
    latitude DECIMAL (7, 5),
    longitude DECIMAL (7, 5),
    accuracy INT NOT NULL,
    heading INT DEFAULT NULL,
    verticalAccuracy INT DEFAULT NULL,
    velocity INT DEFAULT NULL,
    altitude INT DEFAULT NULL,
    activity_type VARCHAR(64),
    ts TIMESTAMP NOT NULL,
    confidence INT NOT NULL,
    FOREIGN KEY (username) REFERENCES users(username)
);
