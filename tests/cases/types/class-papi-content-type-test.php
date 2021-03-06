<?php

/**
 * @group types
 */
class Papi_Content_Type_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		tests_add_filter( 'papi/settings/directories', function () {
			return [1,  PAPI_FIXTURE_DIR . '/content-types'];
		} );

		$this->post_id = $this->factory->post->create();

		update_post_meta( $this->post_id, papi_get_page_type_key(), 'empty-page-type' );

		$this->empty_content_type  = new Papi_Content_Type();
		$this->info_content_type = papi_get_content_type( PAPI_FIXTURE_DIR . '/content-types/info-content-type.php' );
	}

	public function tearDown() {
		parent::tearDown();
		unset(
			$this->post_id,
			$this->empty_content_type,
			$this->info_content_type
		);
	}

	public function test_broken_page_type() {
		$this->assertNull( papi_get_content_type_by_id( 'broken-content-type' ) );
	}

	public function test_get_boxes() {
		$this->assertTrue( is_array( $this->info_content_type->get_boxes() ) );

		$boxes = $this->info_content_type->get_boxes();

		$this->assertSame( 'Info', $boxes[0]->title );

		$this->assertEmpty( $this->empty_content_type->get_boxes() );
	}

	public function test_get_class_name() {
		$this->assertEmpty( $this->empty_content_type->get_class_name() );
		$this->assertSame( 'Info_Content_Type', $this->info_content_type->get_class_name() );
	}

	public function test_get_file_path() {
		$this->assertEmpty( $this->empty_content_type->get_file_path() );

		$this->assertSame(
			PAPI_FIXTURE_DIR . '/content-types/info-content-type.php',
			$this->info_content_type->get_file_path()
		);
	}

	public function test_get_id() {
		$this->assertEmpty( $this->empty_content_type->get_id() );
		$this->assertSame( 'info-content-type', $this->info_content_type->get_id() );
	}

	public function test_get_property() {
		$this->assertNull( $this->empty_content_type->get_property( 'fake' ) );
		$this->assertNull( $this->info_content_type->get_property( 'fake' ) );

		$property = $this->info_content_type->get_property( 'info' );
		$this->assertSame( 'string', $property->get_option( 'type' ) );
		$this->assertSame( 'string', $property->type );
		$this->assertSame( 'papi_info', $property->slug );
		$this->assertSame( 'papi_info', $property->get_option( 'slug' ) );
		$this->assertSame( 'papi_info', $property->get_slug() );
		$this->assertSame( 'info', $property->get_slug( true ) );
		$this->assertSame( 'Info', $property->get_option( 'title' ) );
		$this->assertSame( 'Info', $property->title );
	}

	public function test_get_type() {
		$this->assertSame( 'content', $this->empty_content_type->get_type() );
		$this->assertSame( 'content', $this->info_content_type->get_type() );
	}

	public function test_match_id() {
		$this->assertTrue( $this->empty_content_type->match_id( '' ) );
		$this->assertTrue( $this->info_content_type->match_id( 'info-content-type' ) );
	}

	public function test_new_class() {
		$this->assertEmpty( $this->empty_content_type->new_class() );
		$this->assertEquals( new Info_Content_Type(), $this->info_content_type->new_class() );
	}

	public function test_sort_order() {
		$this->assertSame( 1000, $this->empty_content_type->sort_order );
		$this->assertSame( 500, $this->info_content_type->sort_order );
	}
}
