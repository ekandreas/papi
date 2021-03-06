<?php

/**
 * Core class that implements a Papi type.
 */
class Papi_Core_Type {

	/**
	 * The page type class name.
	 *
	 * @var string
	 */
	private $_class_name = '';

	/**
	 * The file name of the core type file.
	 *
	 * @var string
	 */
	private $_file_name = '';

	/**
	 * The file path of the core type file.
	 *
	 * @var string
	 */
	private $_file_path = '';

	/**
	 * The core type identifier.
	 *
	 * @var string
	 */
	public $id = '';

	/**
	 * The name of the core type.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * The sort order of the core type.
	 *
	 * @var int
	 */
	public $sort_order = 1000;

	/**
	 * The type name.
	 *
	 * @var string
	 */
	public $type = 'core';

	/**
	 * The constructor.
	 *
	 * Load a core type by the file.
	 *
	 * @param string $file_path
	 */
	public function __construct( $file_path = '' ) {
		if ( is_file( $file_path ) ) {
			$this->setup_file( $file_path );
			$this->setup_meta_data();
		}
	}

	/**
	 * Determine if the content type is allowed.
	 *
	 * @return bool
	 */
	public function allowed() {
		return true;
	}

	/**
	 * Boot page type.
	 *
	 * @codeCoverageIgnore
	 */
	public function boot() {
		$this->setup_actions();
		$this->setup_filters();
	}

	/**
	 * Get the page type class name with namespace if exists.
	 *
	 * @return string
	 */
	public function get_class_name() {
		return $this->_class_name;
	}

	/**
	 * Get the page type file pat.h
	 *
	 * @return string
	 */
	public function get_file_path() {
		return $this->_file_path;
	}

	/**
	 * Get the page type identifier.
	 *
	 * @return string
	 */
	public function get_id() {
		if ( ! empty( $this->id ) ) {
			return $this->id;
		}

		return $this->_file_name;
	}

	/**
	 * Get meta data from type class and merge
	 * with the parent meta data.
	 *
	 * @return array
	 */
	private function get_meta() {
		$method = 'meta';

		if ( ! method_exists( $this, $method ) ) {
			return [];
		}

		$child_meta = call_user_func( [$this, $method] );
		$child_meta = is_array( $child_meta ) ? $child_meta : [];

		$parent_class  = get_parent_class( $this );
		$parent_remove = method_exists( $parent_class, $method );
		$parent_meta   = [];

		while ( $parent_remove ) {
			$parent        = new $parent_class();
			$output        = call_user_func( [$parent, $method] );
			$output        = is_array( $output ) ? $output : [];
			$parent_meta   = array_merge( $parent_meta, $output );
			$parent_class  = get_parent_class( $parent_class );
			$parent_remove = method_exists( $parent_class, $method );
		}

		return array_merge( $parent_meta, $child_meta );
	}

	/**
	 * Get type name.
	 *
	 * @return string
	 */
	public function get_type() {
		$class = get_class( $this );

		preg_match( '/\w+\_(\w+)\_Type$/', $class, $matches );
		$type = isset( $matches[1] ) ? $matches[1] : $this->type;

		return strtolower( $type );
	}

	/**
	 * Check so we have a name on the page type.
	 *
	 * @return bool
	 */
	public function has_name() {
		return ! empty( $this->name );
	}

	/**
	 * Check if the the given identifier match the page type identifier.
	 *
	 * @param  string $id
	 *
	 * @return bool
	 */
	public function match_id( $id ) {
		return $this->get_id() === $id;
	}

	/**
	 * Create a new instance of the page type file.
	 *
	 * @return object
	 */
	public function new_class() {
		if ( empty( $this->_file_path ) ) {
			return;
		}

		return new $this->_class_name;
	}

	/**
	 * Setup actions.
	 *
	 * @codeCoverageIgnore
	 */
	protected function setup_actions() {
	}

	/**
	 * Load the file and setup file path, file name and class name properties.
	 *
	 * @param string $file_path
	 */
	private function setup_file( $file_path ) {
		$this->_file_path  = $file_path;
		$this->_file_name  = papi_get_core_type_base_path( $this->_file_path );
		$this->_class_name = papi_get_class_name( $this->_file_path );
	}

	/**
	 * Setup filters.
	 *
	 * @codeCoverageIgnore
	 */
	protected function setup_filters() {
	}

	/**
	 * Setup page type meta data.
	 */
	private function setup_meta_data() {
		foreach ( $this->get_meta() as $key => $value ) {
			if ( substr( $key, 0, 1 ) === '_' ) {
				continue;
			}

			$this->$key = papi_esc_html( $value );
		}

		if ( $this->sort_order === 1000 ) {
			$this->sort_order = papi_filter_settings_sort_order();
		}
	}
}
