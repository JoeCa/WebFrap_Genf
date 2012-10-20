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
abstract class LibCartridgeCode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * date
   * @var string
   */
  protected $date           = null;

  /**
   * @var String
   */
  protected $xmlFile        = '';

   /**
   * @var String
   */
  protected $xmlModel        = '';

  /**
   * @var String
   */
  protected $outputFolder   = null;

  /**
   * collecting all used lang entries
   *
   * @var LibCartridgeI18n
   */
  protected $langParser     = null;

  /**
   * Enter description here...
   *
   * @var string
   */
  protected $classType      = null;

  /**
   * the activ Project that gets parsed
   *
   * @var String
   */
  public    $activProject   = null;

  /**
   * the activ Project that gets parsed
   *
   * @var LibCartridgeRegistry3
   */
  protected $registry       = null;

  /**
   * Code der später von hand angepasst werden kann
   *
   * @var array
   */
  protected $handCode       = array();

  /**
   * Generierter Code der immer wieder überschrieben werden kann
   *
   * @var array
   */
  protected $genfCode       = array();

  /**
   *
   * @var array
   */
  protected $subProject     = null;

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   * @param SimpleXMLElement $xml
   */
  public function __construct(  $xml = null )
  {

    $this->xmlModel = $xml;

    $this->langParser = LibCartridgeI18n::getInstance();
    $this->registry   = LibCartridgeRegistry::getInstance();

    $this->date = SDate::getDate();

    $this->init();

  }//end public function __construct

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->parse();
  }//end public function __toString

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * Setter for Date
   *
   * @param string $date
   */
  protected function setDate( $date )
  {
    $this->date = $date;
  }//end protected function setDate

  /**
   *
   * @param $registry
   * @return unknown_type
   */
  public function setRegistry( $registry )
  {
    $this->registry = $registry;
  }//end public function setRegistry

  /**
   * @return SimpleXMLElement
   */
  public function getCodeXML()
  {
    return $this->xmlModel;
  }//end public function getCodeXML

  /**
   * @param  String $folder
   */
  public function setOutputFolder( $folder )
  {
    $this->outputFolder =  $folder.'/';
  }//end public function setOutputFolder

  /**
   * setter for parse all
   *
   * @param boolean $parseAll
   */
  public function setParseAll( $parseAll = true )
  {
    $this->parseAll = $parseAll;
  }//end public function setParseAll

  /**
   *
   * @return unknown_type
   */
  public function init(){}


  /**
   * Enter description here...
   *
   * @param array $texts
   */
  public function addTexts($texts)
  {

    foreach( $texts as $lang => $data )
    {
      foreach( $data as $key => $entry )
      {
        $this->i18nPool->addText($lang,$key,$entry );
      }
    }

  }//end public function addTexts */

  /**
   * Enter description here...
   *
   * @param array $tables
   * @param string $tableName
   * @return RecordEntityReference
   */
  protected function createRefRecord( $tables , $tableName )
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
        $record->embededTables[]            = $node;

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
    catch( Lib_Exception $e )
    {
      Error::addError
      (
        $e->getMessage() . ' in '.$tableName ,
        'LibParser_Exception'
      );
    }

    return $record;

  }//end protected function extractTables */

  /**
   *
   * @return unknown_type
   */
  public function isRefTable()
  {

    $r = $this->registry;
    $isRef = array();

    foreach( $r->tables as $tab )
    {
      if( !isset($tab->mal->embededTables) )
        continue;

      $embTab = $tab->mal->embededTables;

      foreach( $embTab->table as $refTab  )
      {
        if( strtolower($refTab['relation']) == Bdl::MANY_TO_MANY && (string)$refTab['connectTable']  == $r->tableName )
          $isRef[] = $refTab;
      }

    }

    return $isRef;

  }//end public function isRefTable */

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public abstract function parse();


  /**
   * Enter description here...
   *
   */
  public function write()
  {

    $r = $this->registry;

    // den Genf Code Wegschreiben
    foreach( $this->genfCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName   = $this->classType.SParserString::subToCamelCase($name);
        $classPath  = SParserString::getClassPath($fileName,false);
        $folderPath = $this->outputFolder.'genf/'.$mod.'/src/'.$classPath;
        $this->writeFile($code , $folderPath , $fileName.'.php' );
      }
    }//end foreach( $this->parsedElements as $mod => $elements )

    // den Genf Code Wegschreiben
    foreach( $this->handCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName   = $this->classType.SParserString::subToCamelCase($name);
        $classPath  = SParserString::getClassPath($fileName,false);
        $folderPath = $this->outputFolder.'hand/'.$mod.'/src/'.$classPath;
        $this->writeFile($code , $folderPath , $fileName.'.php' );
      }
    }//end foreach( $this->parsedElements as $mod => $elements )

  }//end public function write  */

  /**
   * Enter description here...
   *
   */
  protected function writeFile( $code ,  $folderPath , $filename )
  {

    $absolute = $folderPath[0]=='/'?true:false;

    if( !file_exists($folderPath) )
    {
      if( !SFilesystem::createFolder( $folderPath, true, $absolute ) )
      {
        Error::addError
        (
          I18n::s
          (
            'Failed to create Folder {@folder@}',
            'wbf.message',
            array( 'folder' => $folderPath)
          )
        );

        Message::addError( 'Konnte den Temporären Pfad nicht erstellen' );
        return;
      }
    }

    if(!SFiles::write( $folderPath.'/'.$filename , $code ))
    {
      Error::addError
      (
        'Failed to write '.$folderPath.'/'.$filename
      );

      Message::addWarning( 'Failed to write '.$folderPath.'/'.$filename );
    }


  }//end public function writeFile */


} // end abstract class LibCartridgeAbstract
