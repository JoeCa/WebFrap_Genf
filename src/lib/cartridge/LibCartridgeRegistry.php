<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/

/**
 * @package WebFrap
 * @subpackage ModGenf
 * @deprecated sieht bescheiden aus, schaun ob das überhaupt noch verwendet wird, wenn nicht löschen
 */
class LibCartridgeRegistry
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the activ table
   *
   * @var SimpleXMLElement
   */
  public static $activTable = null;

  /**
   * Enter description here...
   *
   * @var unknown_type
   */
  public static $model = null;

  /**
   * Enter description here...
   *
   * @var SimpleXMLElement
   */
  public static $allTables = null;

  /**
   * all embeded Tables
   *
   * @var array
   */
  public static $embededTables = array();

  /**
   * a tree with all embeded Tables
   *
   * @var array
   */
  public static $embededTree = array();

  /**
   * the dependencies of embeded Tables
   *
   * @var array
   */
  public static $embededDependencies = array();

  /**
   * array for the names
   *
   * @var array
   */
  public static $names = array();

  /**
   * the name of the mod in CamelCase
   *
   * @var string
   */
  public static $modName = null;

  /**
   * the name of the module in lowercase
   *
   * @var string
   */
  public static $lowModName = null;

  /**
   * the full modul name in camel Case
   *
   * @var string
   */
  public static $entityName = null;

  /**
   * the full modul name in lowercase
   *
   * @var string
   */
  public static $lowEntityName = null;

  /**
   * name of the table
   *
   * @var string
   */
  public static $tableName = null;

  /**
   * the name of the mex in CamelCase
   *
   * @var string
   */
  public static $mexName = null;

  /**
   * the name of the mex in lowercase
   *
   * @var string
   */
  public static $lowMexName = null;

  /**
   * Enter description here...
   *
   * @var array
   */
  public static $modules = array();

  /**
   * the language parser object
   *
   * @var LibCartridgeWbfI18n
   */
  public static $langParser = null;

  /**
   * @var string
   */
  public static $author      = 'WebFrap Developer';

  /**
   * copyright infos
   * @var string
   */
  public static $copyright   = 'Webfrap Developer Network <contact@webfrap.net>';

  /**
   * @var string
   */
  public static $licence  = 'BSD License see: LICENCE/BSD Licence.txt';


  /**
   * @var LibCartridgeRegistry
   */
  private static $instance  = null;

////////////////////////////////////////////////////////////////////////////////
// Private Constructor
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return LibCartrigedRegistry
   */
  public static function getInstance()
  {
    return self::$instance;
  }//end public static function getInstance */

  /**
   * Enter description here...
   *
   */
  private function __construct(){}

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * Enter description here...
   *
   * @param string $name
   * @return boolean
   */
  public static function getNames($name)
  {
    return isset(self::$names[$name])?self::$names[$name]:array();
  }//end public static function getNames */

  /**
   * Enter description here...
   *
   * @param string $name
   * @return boolean
   */
  public static function getTable($name)
  {
    return isset(self::$allTables[$name])?self::$allTables[$name]:null;
  }

////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * setter for the embeded tables
   *
   * @param SimpleXmlElement $xml
   */
  public static function init($xml ,$compXml = null , $version = null )
  {

    // wenn ein eine Compilation und ein Metamodell übergeben werden
    // dann ist das eine MAL Projekt
    if( is_object($xml) && is_object($compXml) )
    {
      if($version)
      {
        $className      = 'LibCartridgeRegistry'.$version;
        self::$instance = new $className();
        self::$instance->init( $xml ,  $compXml );
      }
      else
      {
        self::$langParser           = LibCartridgeWbfI18n::getInstance();
        self::$allTables            = array();
        self::$names                = array();
        self::$embededTree          = array();
        self::$embededDependencies  = array();
        self::$embededTables        = array();

        // parse all tables
        foreach( $xml->tables->table as $tab )
        {
          $name = trim($tab['name']);
          self::$allTables[$name] =  $tab;
          self::parseNames( $name );
        }
      }
    }
    else // wenn nur ein XML Objekt übergeben wird dann ist das ein BDL Projekt
    {

      $version = $compXml;

      $className      = 'LibCartridgeRegistry'.$version;
      self::$instance = new $className();
      self::$instance->init( $xml );

    }

    return self::$instance;

  }//end public function init */

  /**
   * Enter description here...
   *
   * @param string $table
   */
  public static function setActivTable( $name )
  {


    self::$embededTree          = array();
    self::$embededDependencies  = array();
    self::$embededTables        = array();

    self::$activTable           = self::$allTables[$name];

    $data = self::$names[$name];

    self::$tableName      = $name;
    self::$entityName     = $data['entityName'];
    self::$lowEntityName  = $data['lowEntityName'];
    self::$modName        = $data['modName'];
    self::$lowModName     = $data['lowModName'];
    self::$mexName        = $data['mexName'];
    self::$lowMexName     = $data['lowMexName'];

    if( isset( $table->mal->embededTables ) )
    {
      self::extractEmbeded( $table->mal->embededTables );
    }

  }//end public function setActivTable */

  /**
   * check if we have embeded Tables
   *
   * @return boolean
   */
  public static function hasEmbededTables()
  {

      // if we have no dependencies good by
    if( isset( self::$activTable->mal->embededTables ) )
    {
      return true;
    }
    else
    {
      return false;
    }

  }//end public static function hasEmbededTables */

////////////////////////////////////////////////////////////////////////////////
// Parsers
////////////////////////////////////////////////////////////////////////////////

  /**
   * extract all tables
   * @version 2
   * @param SimpleXmlElement $tables
   * @return void
   */
  protected static function extractEmbeded( $tables )
  {


    self::$embededTree = array();
    self::$embededDependencies = array();
    self::$embededTables = array();

    $dependencies = array();

    foreach( $tables->children() as $name => $node )
    {
      if($name == 'table')
      {

        if( (string)$node['type'] == 'pre' )
        {
          $dependencies[] = array(  (string)$node['refTab'] , (string)$node  );
        }
        else
        {
          $dependencies[] = array( (string)$node ,  (string)$node['refTab'] );
        }

        if( strtolower($node['relation'])  == Bdl::MANY_TO_MANY )
        {
          LibCartridgePool::getInstance()->addRefTable( (string)$node['refTab'] , $node );
        }

        self::$embededTree[(string)$node][] = $node;
        self::$embededTables[] = $node;

        //$this->embededTables[] = array( (string)$node , trim($node['refTab']) , trim($node['refId']) );
      }
      else
      {
        continue;
      }
    }

    try
    {
      $deps = new LibDependency($dependencies,true,self::$tableName);
      self::$embededDependencies = SParserArray::multiDimFusion($deps->solveDependencies());

    }
    catch( LibException $e )
    {
      Error::addError
      (
      $e->getMessage() . ' in '.$this->activTable['name'] ,
      'LibParser_Exception'
      );
    }

  }//end protected static function extractEmbeded */

  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @return void
   */
  protected static function parseNames( $name )
  {


    self::$names[$name]['tableName']      = $name;
    self::$names[$name]['entityName']     = SParserString::subToCamelCase($name);
    self::$names[$name]['lowEntityName']  =
      strtolower(self::$names[$name]['entityName']);

    self::$names[$name]['modName']        = SParserString::getModname($name);
    self::$names[$name]['lowModName']     =
      strtolower(self::$names[$name]['modName']);

    self::$names[$name]['mexName']        =
      SParserString::subToCamelCase(SParserString::removeFirstSub($name)) ;
    self::$names[$name]['lowMexName']     =
      strtolower(self::$names[$name]['mexName']);

  }//end protected static function parseNames */

  /**
   * add texts to the language parser
   * @param array $texts
   */
  public static function addTexts($texts)
  {
    if(Log::$levelDebug)
      Log::start(__FILE__,__LINE__,__METHOD__,array($texts));

    foreach( $texts as $lang => $data )
    {
      foreach( $data as $key => $entry )
      {
        self::$langParser->addText($lang,$key,$entry );
      }
    }

  }//end public static function addTexts */

  /**
   * Enter description here...
   * @param simpleXmlElement $xml
   */
  public static function loadModules( $xml )
  {


    $tmpMap = array();

    if( isset($xml->tables ))
    {
      $classes = $xml->tables;

      foreach( $classes->table as $table )
      {

        if( !isset($table->mal->meta) )
        {
          // Get the Modul info
          $parts = explode( '_' , (string)$table['name']);

          if( !isset($tmpMap[$parts[0]]) )
          {
            $tmpMap[$parts[0]] = true;
            self::$modules[$parts[0]] = true;
          }
        }
      }//end foreach

    }//end if

  }//end public static function loadModules */

  /**
   *
   * @param $tables
   * @param $tableName
   * @return unknown_type
   */
  public static function createRefRecord( $tables , $tableName )
  {


    $record = new RecordEntityReference();

    $record->embededTree          = array();
    $record->embededDependencies  = array();
    $record->embededTables        = array();

    if( !isset($tables->mal->embededTables) )
      return $record;

    $dependencies = array();

    foreach( $tables->mal->embededTables->children() as $name => $node )
    {

      $name = trim($name);

      if ($name == 'table')
      {

        if( trim($node['type']) == 'pre' )
        {
          $dependencies[] = array(  trim($node['refTab']) , trim($node)  );
        }
        else
        {
          $dependencies[] = array( trim($node) ,  trim($node['refTab']) );
        }

        $record->embededTree[trim($node)][] = $node;
        $record->embededTables[] = $node;

      }
      else
      {
        continue;
      }
    }//end foreach( $tables->children() as $name => $node )

    try
    {
      $deps = new LibDependency($dependencies,true,$tableName);
      $record->embededDependencies = SParserArray::multiDimFusion
      (
        $deps->solveDependencies()
      );
    }
    catch( LibException $e )
    {
      Error::addError
      (
      $e->getMessage() . ' in '.$tableName ,
      'LibParser_Exception'
      );
    }

    return $record;

  }//end protected function extractTables */

} // end class LibCartridgeRegistry
