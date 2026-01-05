<?php
/**
 * Database settings for development environment (Docker MySQL)
 */

return array(
	'default' => array(
		'connection' => array(
			'dsn'      => 'mysql:host=db;dbname=fuel_dev;charset=utf8mb4',
			'username' => 'fuel',
			'password' => 'fuel',
		),
	),
);
