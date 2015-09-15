<?php
namespace Tikomatic;

class Config {

	protected $_config = [];

	public function __construct( $filename='' ) {
		if ( empty($filename) ) {
			$filename = APP_BASEPATH . '/tikomatic.ini';
		}
		$this->_config = $this->readIni( $filename );
	}

	public function readIni( $filename ) {
		if ( file_exists($filename) ) {
			return parse_ini_file($filename);
		} else {
			throw new \Exception('Cannot create instance of Config, File not found('.$filename.')');
		}
	}

	public function get( $key='' ) {
		if ( !empty($key) ) {
			if ( array_key_exists($key, $this->_config)) {
				return $this->_config[$key];
			}
		}
		return $this->_config;
	}

	public function set( $key='', $value='' ) {
		if ( !empty($key) ) {
			return $this->_config[$key] = $value;
		}
	}

}