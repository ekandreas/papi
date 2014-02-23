<?php

class PTB_List_Page extends PTB_Base {

  public static $page_type = array(
    'name' => 'List sida',
    'description' => 'En list sida',
    'template' => 'page-list-page.php',
  );

  public function __construct () {
    parent::__construct();

    $this->remove(array(
      'editor',
      'comments',
      'revisions'
    ));
    
    $this->box('Bilder', array(
      $this->collection('Innehåll', array(
        $this->property(array(
          'type' => 'PropertyString',
          'title' => 'Rubrik',
          'collection' => true
        )),
        $this->property(array(
          'type' => 'PropertyString',
          'title' => 'Bild',
          'collection' => true
        ))
      ))
    ));
    
    $this->box('Text', array(
      $this->property(array(
        'type' => 'PropertyText',
        'title' => 'Text'
      ))
    ));
  }
  
}