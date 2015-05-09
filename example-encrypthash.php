<?php
/**
 * Encrypted password hash storage example.
 *
 * Uses bcrypt + AES-256-CBC PKCS#7 padding.
 *
 * Requires mcrypt PHP extension.
 *
 * Set your password database column to VARCHAR(255) or similar.
 * Generate 256-bit key (64 chars, 0-9, A-F) using
 * <code>echo current(unpack('H64', mcrypt_create_iv(32, MCRYPT_DEV_RANDOM)));</code>
 * Store the key in a configuration file.
 *
 * @author Michal Špaček <https://www.michalspacek.cz>
 */

require_once './functions-encrypthash.php';

// Hash, encrypt and store password
// ********************************

$key = '...'; // Loaded from a config file
$encrypted = hashAndEncrypt($_POST['password'], $key);

// Example only
$statement = $pdo->prepare('INSERT INTO users (user, password) VALUES (?, ?)');
$statement->execute(array($_POST['user'], $encrypted));


// Verify that a password matches an encrypted hash
// ************************************************

$key = '...'; // Loaded from a config file

// Example only
$statement = $pdo->prepare('SELECT password FROM users WHERE user = ?');
$statement->execute(array($_POST['user']));
$encrypted = $statement->fetchColumn();

if (decryptAndVerifyHash($_POST['password'], $encrypted, $key)) {
	// Logged in
} else {
	// Wrong password
}
