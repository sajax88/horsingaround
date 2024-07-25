echo "** Creating default DB and user"

mariadb -uroot -p$MARIADB_ROOT_PASSWORD --execute \
"CREATE DATABASE IF NOT EXISTS $DB_NAME;
CREATE USER '$DB_USER'@'%' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%';"

echo "** Default DB and user created"

# Create initial tables
mariadb -uroot -p$MARIADB_ROOT_PASSWORD --execute \
"
USE $DB_NAME;
CREATE TABLE IF NOT EXISTS requests (
     id INT AUTO_INCREMENT,
     full_name VARCHAR(255) NOT NULL,
     description TEXT NOT NULL,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT,
    full_name VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    PRIMARY KEY(id)
);
"

echo "** Tables created"
