CREATE TABLE guest (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(128) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE guest_perms (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    fk_guest INT(11) NOT NULL,
    fk_perm INT(11) NOT NULL
);

CREATE TABLE perm (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(20) NOT NULL
);

ALTER TABLE guest_perms
    ADD CONSTRAINT fk_guest_param_to_guest FOREIGN KEY (fk_guest)
    REFERENCES guest(id);

ALTER TABLE guest_perms
    ADD CONSTRAINT fk_guest_param_to_perm FOREIGN KEY (fk_perm)
    REFERENCES perm(id);