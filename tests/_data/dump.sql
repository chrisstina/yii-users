CREATE TABLE `user` (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    email VARCHAR(32) NOT NULL UNIQUE,
    password_hash VARCHAR(60) NOT NULL UNIQUE,
    activation_code VARCHAR(32) UNIQUE,
    is_active BIT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL,
    activation_code_created_at TIMESTAMP NOT NULL);

CREATE TABLE profile (
    `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT, 
    `uid` INT UNSIGNED UNIQUE,
    `name` VARCHAR(64) NULL
);

INSERT INTO `user` (email, password_hash, activation_code, is_active, created_at, last_login_at, activation_code_created_at) 
	VALUES ('test1@test.com', '$2y$13$EQShGmOHgLAMAT/7ks.BZOzlpcLsvYc3gyaMVTeGk/3O9r18O8bUm', 'ID2xBCgkFQOz2FcsmCh-bbLDlWccm6El', false, '2014-10-15 00:33:47.0', NULL, '2014-10-15 00:33:47.0');
INSERT INTO `user` (email, password_hash, activation_code, is_active, created_at, last_login_at, activation_code_created_at) 
	VALUES ('test2@test.com', '$2y$13$/lg.gjRn4LxTJU0GRXmWB.eCMXwJrX.hfnC4b34eSkMlFyp6FPKUe', 'IziMnWcEVfeZryqLffnNZzwrFfn-2mZw', false, '2014-10-15 09:26:27.0', NULL, '2014-10-15 09:26:27.0');
INSERT INTO `user` (email, password_hash, activation_code, is_active, created_at, last_login_at, activation_code_created_at) 
	VALUES ('test3@gmail.com', '$2y$13$W/nHd.xKIr6r5O5uW4ggr.0XY1u.qOldoqeK6DNJQUocj7pfgYr3q', null, true, '2014-10-15 09:26:27.0', '2014-10-15 09:26:27.0', '2014-10-15 09:26:27.0');

INSERT INTO profile (uid, `name`) 
	VALUES (1, 'test1');
INSERT INTO profile (uid, `name`) 
	VALUES (2, 'test2');
INSERT INTO profile (uid, `name`) 
	VALUES (3, 'test3');
