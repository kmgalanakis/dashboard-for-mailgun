<?php

class MailgunDashboard_Autoloader {

	private $classmap = array();

	public function initialize() {
		add_action( 'mailgun_dashboard_register_classmap', array( $this, 'register_classmap' ) );
		spl_autoload_register( array( $this, 'autoload' ), true, true );
	}

	public function register_classmap( $classmap ) {

		if( ! is_array( $classmap ) ) {
			throw new InvalidArgumentException( 'The classmap must be an array.' );
		}

		$this->classmap = array_merge( $this->classmap, $classmap );

	}

	public function autoload( $class_name ) {

		if( array_key_exists( $class_name, $this->classmap ) ) {
			$file_name = $this->classmap[ $class_name ];

			// If this causes an error, blame the one who filled the $classmap.
			require_once $file_name;

			return true;
		}

		return false;
	}
}