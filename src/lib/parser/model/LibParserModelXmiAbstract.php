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
 */
abstract class LibParserModelXmiAbstract
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var Logsys
   */
  protected $log              = null;

  /**
   * @var string
   */
  protected $xmlFile          = null;

  /**
   * @var simpleXml
   */
  protected $xml              = null;

  /**
   * @var array
   */
  protected $typeIndex        = array();

  /**
   * @var array
   */
  protected $classIndex       = array();

  /**
   * @var array
   */
  protected $interfaceIndex   = array();

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $extendsMap       = array();

  /**
   * Enter description here...
   *
   * @var array
   */
  protected $implementsMap    = array();

  /**
   * @var array
   */
  protected $typeXml          = null;

  /**
   * @var array
   */
  protected $classXml         = null;

  /**
   * @var string
   */
  protected $tables           = '';

  /**
   * @var string
   */
  protected $tablesXml        = null;

  /**
   * @var array
   */
  protected $tableIndex       = array();

  /**
   * @var string
   */
  protected $sequences        = '';

  /**
   * @var string
   */
  protected $foreignKeys      = '';

  /**
   *
   */
  protected $classes          = '';

  /**
   *
   */
  protected $interfaces       = '';

  /**
   * @var String
   */
  protected $dbXml            = '';

  /**
   *
   */
  protected $enums            = '';

  /**
   *
   */
  protected $roles            = '';

  /**
   * @var array
   */
  protected $activPk          = array();

  /**
   * @var LibParserMalApi
   */
  protected $malParser        = null;

  /**
   * @var array
   */
  protected $metaIndex        = array();

  protected $modeller         = null;

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $xmlFile
   */
  public function __construct( $xmlFile )
  {

    if( Log::$levelVerbose )
      Log::create( get_class($this) );

    $this->malParser = LibParserMalApi::getInstance();

    $this->xmlFile = $xmlFile;

  }//end public function __construct

  /**
   * @return string
   */
  public function __toString()
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__);

    return $this->parse();

  }//end public function __toString


////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getDbXml()
  {

    return $this->dbXml;

  }//end public function getDbXml

  /**
   * @return string
   */
  public function getTables()
  {

    return $this->tables;

  }//end public function getTables

  /**
   * @return string
   */
  public function getForeignKeys()
  {

    return $this->foreignKeys;

  }//end public function getForeignKeys

  /**
   * @return string
   */
  public function getSequences()
  {

    return $this->sequences;

  }//end public function getSequences

////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string[optional] $xmlFile
   */
  public function load( )
  {

    if( !file_exists($this->xmlFile) )
    {
      Error::addError
      (
      'File '.$this->xmlFile.' does not exist',
      'LibParser_Exception'
      );
    }

    if( !$this->xml = simplexml_load_file( $this->xmlFile ) )
    {
      Error::addError
      (
      'Failed to load the xmi File:'.$this->xmlFile,
      'LibParser_Exception'
      );
    }


  }//end public function load

  /**
   * @param simpleXML $xml
   * @param string $ns
   * @return simpleXML
   */
  protected function removeNamespace( $xml , $ns = 'UML' )
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__,array($xml , $ns));

    $string = $xml->asXml();
    $string = str_replace( $ns.':' , '' , $string );
    return new SimpleXMLElement($string);

  }//end function removeNamespace

  /**
   * @param string
   */
  public function save( $folder , $filename = null )
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__,array($folder , $filename));

    if( is_null($filename) )
    {
      $tmp = SFiles::splitFilename($folder);

      $folder = $tmp[0];
      $filename = $tmp[1];
    }

    if( !is_writeable($folder) )
    {
      Error::addError
      (
      'The Folder: '.$folder.' is not writeable!',
      'LibParser_Exception'
      );
    }

    if(SFiles::write( $folder.'/'.$filename , $this->dbXml ))
    {
      Message::addMessage('Successfully Created Metamodel');
    }
    else
    {
      Message::addError('Failed to create Metamodel');
    }

  }//end public function save

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  protected function openXmlFile()
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__);

    $modelName = SFiles::getRawFilename($this->xmlFile);

    $this->dbXml = '<?xml version="1.0" encoding="UTF-8" ?>'.NL ;
    $this->dbXml .= '<metaModel name="'.$modelName.'" modeller="'.$this->modeller.'" >'.NL;


  }//end protected function openXmlFile

  /**
   *
   */
  protected function closeXmlFile()
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__);


    $this->dbXml .= '</metaModel>';

  }//end protected function closeXmlFile

  /**
   * @return void
   */
  protected function addSequence( $table , $keyname, $isMeta = false )
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__,array($table , $keyname) );

    if( $isMeta )
    {
      $meta = ' meta="meta" ';
    }
    else
    {
      $meta = '';
    }

    $sequence = '<sequence '.$meta;
    $sequence .='tabName="'.$table.'" ';
    $sequence .='name="'.$table.'_'.$keyname.'_seq" ';
    $sequence .='start="1" ';
    $sequence .='increment="1" ';
    $sequence .=' />'.NL;

    $this->sequences .= $sequence;

  }//end protected function addSequence

  /**
   * @return string
   */
  public abstract function parse();

} // end abstract class LibParserXmiAbstract

