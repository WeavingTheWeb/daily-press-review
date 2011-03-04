<?php

$exception = NULL;

if ( ! function_exists( 'assignConstant' ) && defined( 'ENTITY_FUNCTION' ) )

		$exception = sprintf( EXCEPTION_MISSING_ENTITY, ENTITY_FUNCTION );

if ( ! defined('CLASS_APPLICATION' ) &&  defined( 'ENTITY_CLASS' ) )

		$exception = sprintf( EXCEPTION_MISSING_ENTITY, ENTITY_CLASS );

if ( ! is_null( $exception ) )

	throw new Exception( $exception );

if ( ! isset( $class_application ) )

	$class_application = CLASS_APPLICATION;

// Services

declareConstantsBatch(
	array(

		// services

		'SERVICE_DEBUGGING' => 'debug',
		'SERVICE_CACHING' => 'caching',
		'SERVICE_DEPLOYMENT' => 'deployment',
		'SERVICE_GMAIL' => 'gmail',
		'SERVICE_IMAP' => 'imap',
		'SERVICE_READITLATER' => 'readitlater',
		'SERVICE_MYSQL' => 'mysql',
		'SERVICE_SMTP' => 'smtp',
		'SERVICE_TWITTER' =>'twitter',

		// Services configuration
		
		'SETTING_API_KEY' => 'api_key',
		'SETTING_ASSERTION' => 'assertion',
		'SETTING_CACHING' => 'caching',
		'SETTING_CALLBACK' => 'callback',
		'SETTING_DATABASE' => 'database',
		'SETTING_FLAGS' => 'flags',
		'SETTING_HOST' => 'host',
		'SETTING_MODE' => 'mode',
		'SETTING_PASSWORD' => 'password',
		'SETTING_PORT' => 'port',
		'SETTING_ROUTING' => 'routing',
		'SETTING_UNIT_TESTING' => 'unit_testing',
		'SETTING_USER_NAME' => 'username',

		// DSN for PDO

		'DB_DSN_PREFIX' => 'data_source_prefix',
		'DB_DSN_PREFIX_MYSQL' => 'mysql',

		'DB_DEFAULT_HOST' => 'localhost',
	)
);

declareConstantsBatch(
	array(

		// Api for Twitter 

		'API_TWITTER_USER_NAME' => 
			$class_application::getServiceProperty(
				SETTING_USER_NAME, SERVICE_TWITTER
			),
		'API_TWITTER_CALLBACK' => 
			$class_application::getServiceProperty(
				SETTING_CALLBACK, SERVICE_TWITTER
			),
		'API_TWITTER_CONSUMER_KEY' => 
			$class_application::getServiceProperty(
				SETTING_API_KEY, SERVICE_TWITTER
			),
		'API_TWITTER_CONSUMER_SECRET' => 
			$class_application::getServiceProperty(
				SETTING_PASSWORD, SERVICE_TWITTER
			),

		// Database

		'DB_SEFI' => 
			$class_application::getServiceProperty(
				SETTING_DATABASE
			),
		'DB_HOST' => 
			$class_application::getServiceProperty(
				SETTING_HOST
			),
		'DB_USER_NAME' => 
			$class_application::getServiceProperty(
				SETTING_USER_NAME
			),
		'DB_PASSWORD' => 
			$class_application::getServiceProperty(
				SETTING_PASSWORD
			),

		// SMTP

		'SMTP_HOST' => 
			$class_application::getServiceProperty(
				SETTING_HOST, SERVICE_SMTP
			),
		'SMTP_USER_NAME' => 
			$class_application::getServiceProperty(
				SETTING_USER_NAME, SERVICE_SMTP
			),
		'SMTP_PASSWORD' => 
			$class_application::getServiceProperty(
				SETTING_PASSWORD, SERVICE_SMTP
			),
		'SMTP_PORT' => 
			$class_application::getServiceProperty(
				SETTING_PORT, SERVICE_SMTP
			),

		// Deployment

		'DEBUGGING_ROUTING' => (
				$class_application::getServiceProperty(
					SETTING_ROUTING, SERVICE_DEBUGGING
				)
				?
					TRUE
				:
					FALSE
			),

		// Deployment

		'DEPLOYMENT_CACHING' => 
			$class_application::getServiceProperty(
				SETTING_CACHING, SERVICE_DEPLOYMENT
			),
		'DEPLOYMENT_MODE' => 
			(int) $class_application::getServiceProperty(
				SETTING_MODE, SERVICE_DEPLOYMENT
			),

		// Gmail

		'GMAIL_USER_NAME' => 
			$class_application::getServiceProperty(
				SETTING_USER_NAME, SERVICE_GMAIL
			),
		'GMAIL_PASSWORD' => 
			$class_application::getServiceProperty(
				SETTING_PASSWORD, SERVICE_GMAIL
			),

		// IMAP

		'IMAP_HOST' => 
			$class_application::getServiceProperty(
				SETTING_HOST, SERVICE_IMAP
			),
		'IMAP_PORT' => 
			$class_application::getServiceProperty(
				SETTING_PORT, SERVICE_IMAP
			),
		'IMAP_FLAGS' => 
			$class_application::getServiceProperty(
				SETTING_FLAGS, SERVICE_IMAP
			),

		// ReadItLater

		'READITLATER_API_KEY' => 
			$class_application::getServiceProperty(
				SETTING_API_KEY, SERVICE_READITLATER
			),
		'READITLATER_USER_NAME' => 
			$class_application::getServiceProperty(
				SETTING_USER_NAME, SERVICE_READITLATER
			),
		'READITLATER_PASSWORD' => 
			$class_application::getServiceProperty(
				SETTING_PASSWORD, SERVICE_READITLATER
			),

		// Unit testing

		'UNIT_TESTING_MODE_STATUS' => 
				$class_application::getServiceProperty(
					SETTING_UNIT_TESTING, SERVICE_DEPLOYMENT
				)
			?
				UNIT_TESTING_MODE_ENABLED
			:
				UNIT_TESTING_MODE_DISABLED
		,
		'UNIT_TESTING_ASSERTIVE_MODE_STATUS' => 
				$class_application::getServiceProperty(
					SETTING_ASSERTION, SERVICE_DEPLOYMENT
				)
			?
				UNIT_TESTING_ASSERTIVE_MODE_ENABLED
			:
				UNIT_TESTING_ASSERTIVE_MODE_DISABLED
		)
);

declareConstantsBatch(
	array(
		'IMAP_USERNAME' => GMAIL_USER_NAME,
		'IMAP_PASSWORD' => GMAIL_PASSWORD
	)
);