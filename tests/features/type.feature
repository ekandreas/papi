Feature: Manage types

  Background:
	 Given a WP install
	  When I run `wp plugin activate papi`
    Then STDOUT should be:
    	"""
			Success: Plugin 'papi' activated.
    	"""

  Scenario: List all types
    When I run `wp papi type list --format=csv`
    Then STDOUT should be CSV containing:
      | name | id | post_type | template | number_of_pages | type |
      | Attachment | others/attachment-type | attachment | | 0 | attachment |
      | Header | options/header-option-type | n/a | n/a | n/a | option |
      | Properties page type | properties-page-type | page | pages/properties-page.php | 0 | page |
