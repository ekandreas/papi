<?php

class Papi_Loader_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();
		$this->papi = Papi_Loader::instance();
	}

	public function tearDown() {
		parent::tearDown();
		unset( $this->papi );
	}

	public function test_after_setup_theme_action() {
		$this->assertSame( 10, has_action( 'plugins_loaded', 'papi' ) );
	}

	public function test_constants() {
		$this->assertTrue( defined( 'PAPI_PLUGIN_DIR' ) );
		$this->assertTrue( defined( 'PAPI_PLUGIN_URL' ) );
		$this->assertTrue( defined( 'PAPI_PAGE_TYPE_KEY' ) );
	}

	public function test_instance() {
		$this->assertClassHasStaticAttribute( 'instance', 'Papi_Loader' );
	}

	public function test_porter() {
		$porter = $this->papi->porter();
		$this->assertTrue( $porter instanceof Papi_Porter );
	}

	public function test_plugin_activated() {
		$this->assertTrue( class_exists( 'Papi_Loader' ) && class_exists( 'Papi_Admin' ) );
	}

	public function test_name() {
		$this->assertSame( 'Papi', $this->papi->name );
	}
}
