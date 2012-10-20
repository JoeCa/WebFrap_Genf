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
abstract class LibParserDdlAbstract
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var Logsys
   */
  protected $log          = null;

  /**
   * @var string
   */
  protected $tables       = array();

  /**
   * @var string
   */
  protected $sequences    = array();

  /**
   * @var string
   */
  protected $foreignKeys  = array();

  /**
   * @var  string
   */
  protected $ddlString    = '';

  /**
   *
   */
  protected $owner        = '';

  /**
   * @var String the Schema for the Database
   */
  protected $schema       = 'public';

  /**
   * @var String
   */
  protected $xmlFile        = '';

    /**
   * @var String
   */
  protected $ddlXML        = '';

  /**
   * @var String
   */
  protected $parsedSequences = array();

  /**
   * @var String
   */
  protected $parsedTables = array();

  /**
   * @var String
   */
  protected $parsedKeys = array();

  /**
   * an array with the metadata for a key
   * @var array
   */
  protected $keyTree = array();

  /**
   * @var String
   */
  protected $basePath = null;

  /**
   * @var String
   */
  protected $subPath = null;

  /**
   * @var String
   */
  protected $outputFolder = null;

  /**
   * @var String
   */
  protected $type = null;

  /**
   * The Name of the DBMS
   * @var String
   */
  protected $dbmsName = null;

  /**
   * parse all
   *
   * @var array
   */
  protected $toParse = array();

  /**
   * should all tables be parsed or not
   *
   * @var array
   */
  protected $parseAll = false;

  /**
   * path to save the quotes data
   *
   * @var string
   */
  protected $quotesPath  = null;

  /**
   * the quotes data
   *
   * @var array
   */
  protected $quotesPool  = null;

  /**
   * Enter description here...
   *
   * @var boolean
   */
  protected $ignoreMeta  = false;

  /**
   * create a tree with the foreign key metadata
   *
   * @var boolean
   */
  protected $createKeyTree = false;

  /**
   * insert for the table
   *
   * @var string
   */
  protected $insertEntitys = '';

  /**
   * @var array
   */
  protected $tablesInstall = array();

  /**
   * @var array
   */
  protected $sequencesInstall = array();

  /**
   * @var array
   */
  protected $parsedInstallSequences = array();

  /**
   * @var array
   */
  protected $parsedInstallTables = array();

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param String $xmlFile the Filepath of the Metamodel to parse
   */
  public function __construct( $xmlFile )
  {

    if( Log::$levelVerbose )
      Log::create(get_class($this),array($xmlFile));

    if( is_string($xmlFile) )
    {
      $this->xmlFile = $xmlFile;
      $this->load();
    }
    elseif( is_object($xmlFile) and $xmlFile instanceof simplexmlElement )
    {
      $this->ddlXml = $xmlFile;
    }


    $this->basePath = PATH_GW.'data/code_gen_cache/'; // where?

    $this->outputFolder = PATH_GW.'data/code_gen_cache/';

    $this->subPath = '/data/ddl/'.$this->type.'/';
    $this->quotesPath = '/data/db_quotes_cache/'.$this->type.'/webfrap/';


  }//end public function __construct( $xmlFile )

  /**
   * @return string
   */
  public function __toString()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

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
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->dbXml;

  }//end public function getDbXml

  /**
   * should the meta flag be ignored
   * @return string
   */
  public function setIgnoreMeta( $ignore = true )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($ignore) );

    $this->ignoreMeta = $ignore;

  }//end public function setIgnoreMeta( $ignore = true )

  /**
   * setter for Create Key Tree
   *
   * @param boolean $set
   */
  public function setCreateKeyTree( $set = true )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($set) );

    $this->createKeyTree = $set;
  }//end public function setCreateKeyTree( $set = true )

  /**
   * @return string
   */
  public function getTables()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->tables;

  }//end public function getTables()

  /**
   * request the ddl for a table
   *
   * @param string $key
   */
  public function getTable( $key )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($key));

    if( isset( $this->tables[$key] ))
    {
      return $this->tables[$key];
    }
    else
    {
      return '';
    }

  }//end public function getTable( $key )

  /**
   * request the ddl for a table
   *
   * @param string $key
   */
  public function getFk( $key )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($key));

    if( isset( $this->keyTree[$key] ))
    {
      if( Log::$levelTrace )
        Log::logTrace(__file__,__line__, 'found foreign key for: '.$key );

      return $this->keyTree[$key];
    }
    else
    {
      if( Log::$levelTrace )
        Log::logTrace(__file__,__line__, 'found no foreign key for: '.$key );

      return array();
    }

  }//end public function getFk( $key )

  /**
   * @return string
   */
  public function getForeignKeys()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->foreignKeys;

  }//end public function getForeignKeys

  /**
   * @return string
   */
  public function getSequences()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->sequences;

  }//end public function getSequences

  /**
   * @param String $owner
   */
  public function setOwner( $owner )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array( $owner ));

    $this->owner = $owner;

  }//end public function setOwner( $owner )

  /**
   * @var String $schema
   */
  public function setSchema( $schema )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array( $schema ));

    $this->schema = $schema;

  }//end public function setSchema()

  /**
   * @return  String
   */
  public function getTableSql()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->parsedTables;
  }//end public function getTableSql()

  /**
   * @return String
   */
  public function getSequenceSql()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return $this->parsedSequences;
  }//end public function getSequenceSql()

  /**
   * @return String
   */
  public function getKeySql()
  {
    if( Log::$levelDebug )Log::start(__file__,__line__,__method__);
    return $this->parsedKeys;
  }//end public function getKeySql()

  /**
   * @return String
   */
  public function setOutputFolder( $folder )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array( $folder ));

    $this->outputFolder =  $this->basePath.$folder.'/'.$this->type.'/';

  }//end public function setOutputFolder( $folder )

  /**
   * set the tables that should be parsed
   *
   * @param array $toParse
   */
  public function setToParse( $toParse)
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($toParse));

    $this->toParse = $toParse;

  }//end public function setToParse( $toParse)

  /**
   * tell the parser to parse all tables
   *
   */
  public function setParseAll( $parse = true )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($parse));

    $this->parseAll = $parse;
  }//end public function setParseAll( $parse = true )


////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function load( )
  {
    if( Log::$levelDebug )Log::start(__file__,__line__,__method__);

    if( !file_exists($this->xmlFile) )
    {
      Error::addError
      (
      'File '.$this->xmlFile.' does not exist',
      'LibParser_Exception'
      );
    }

    if( !$this->ddlXml = simplexml_load_file( $this->xmlFile ) )
    {
      Error::addError
      (
      'Failed to load the xmi File:'.$this->xmlFile,
      'LibParser_Exception'
      );
    }


  }//end public function load( )

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the quotes map for the database
   *
   */
  public function parseQuotes()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    if( $this->parseAll )
    {
      $this->parseAllQuotes();
    }
    else
    {
      $this->parseMapQuotes();
    }
  }//end public function parseQuotes()

  /**
   * parse all quotes
   * @return void
   */
  public function parseAllQuotes()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    foreach( $this->ddlXml->tables->table as $tab )
    {

      if( !isset( $tab->mal->meta ) or $this->ignoreMeta )
      {
        $tabName = (string)$tab['name'];

        if( Log::$levelTrace )
          Log::logTrace(__file__,__line__,'parsing '.$tabName);

        $quote = '$this->quotesCache[\''.$tabName.'\'] = array
  ('.NL;

        foreach( $tab->row as $row )
        {
          $quote .= $this->parseMapRow( $row );
        }

        $quote .= ');'.NL;

        $modName = SParserString::getModname($tabName,true);

        $this->quotesPool[$modName][$tabName] = $quote;
      }

    }//end foreach

  }//end public function parseAllQuotes()

  /**
   * parse quotes map
   * @return void
   */
  public function parseMapQuotes()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);


    foreach( $this->ddlXml->tables->table as $tab )
    {

      $tabName = (string)$tab['name'];

      if( isset( $this->toParse[$tabName] ) )
      {

        if( Log::$levelTrace )
          Log::logTrace(__file__,__line__,'parsing '.$tabName);

        $quote = '$this->quotesCache[\''.$tabName.'\'] = array
('.NL;

        foreach( $tab->row as $row )
        {
          $quote .= $this->parseMapRow( $row );
        }

        $quote .= ');'.NL;

        $this->quotesPool[$tabName] = $quote;
      }
      else
      {
        if( Log::$levelTrace )
          Log::logTrace(__file__,__line__,'not parsing '.$tabName);
      }
    }

  }//end public function parseMapQuotes()

  /**
   * Enter description here...
   * @param simpleXml $rowXml
   */
  public function parseMapRow( $rowXml )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($rowXml));

    $name = (string)$rowXml['name'];
    $type = strtolower((string)$rowXml['type']);
    $row = "'".$name."' => ".$this->quotesMap[$type].", ".NL;

    return $row;

  }//end public function parseMapRow($rowXml)

  /**
   * Enter description here...
   * @param simpleXml $rowXml
   */
  public function parseInsertEntity( $entityName )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($entityName));

    $this->insertEntitys .= 'insert into webfrap.core_entity '
      .' (name,m_creator) values ( \''.$entityName.'\', 1 ); '.NL;

  }//end public function parseMapRow($rowXml)


////////////////////////////////////////////////////////////////////////////////
// Abstract Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return String
   */
  public abstract function parse();

  /**
   * @return  String
   *
   */
  public function highlight()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    return highlight_string(
      '--- DBMS TYPE '.$this->dbmsName.' --'.NL.NL.$this->parsedSequences.
      $this->parsedTables.$this->parsedKeys,true
                            );

  }//end public function highlight()

  /**
   *
   */
  public function write( $outputFolder = null )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    if($outputFolder)
    {
      $this->setOutputFolder($outputFolder);
    }

    $fullDump = '';

    foreach( $this->parsedTables as $modName => $parsed )
    {

      $path = $this->outputFolder.$modName.$this->subPath;

      if(!SFilesystem::createFolder($path))
      {
        Error::addError('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$modName.'_table.sql' , $parsed ))
      {
        Error::addError
        (
        'Failed to write '.$path.$modName.'_table.sql'
        );
      }
      else
      {
        Message::addMessage( 'Successfully wrote '.$path.$modName.'_table.sql');
      }
    }//end foreach( $this->parsedTables as $modName => $parsed )

    foreach( $this->parsedSequences as $modName => $parsed )
    {
      $path = $this->outputFolder.$modName.$this->subPath;
      if(!SFilesystem::createFolder($path))
      {
        Error::addError('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$modName.'_sequence.sql' , $parsed ))
      {
        Error::addError
        (
        'Failed to write '.$path.$modName.'_sequence.sql'
        );
      }
      else
      {
        Message::addMessage( 'Successfully wrote '.$path.$modName.'_sequence.sql');
      }

      // create a full dump
      $fullDump = $parsed.$fullDump;

    }//end foreach( $this->parsedSequences as $modName => $parsed )

    foreach( $this->parsedKeys as $modName => $parsed )
    {
      $path = $this->outputFolder.$modName.$this->subPath;
      if(!SFilesystem::createFolder($path))
      {
        Error::addError('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$modName.'_keys.sql' , $parsed ))
      {
        Error::addError
        (
        'Failed to write '.$path.$modName.'_keys.sql'
        );
      }
      else
      {
        Message::addMessage('Successfully wrote '.$path.$modName.'_keys.sql');
      }
    }//end foreach( $this->parsedKeys as $modName => $parsed )

    foreach( $this->quotesPool as $modName => $quotes )
    {
      foreach( $quotes as $name => $data  )
      {

        $path = $this->outputFolder.$modName.$this->quotesPath;
        if(!SFilesystem::createFolder($path))
        {
          Error::addError('Failed to create folder: '.$path);
          return false;
        }

        if(!SFiles::write( $path.$name.'.php' , '<?php'.NL.$data.NL.'?>' ))
        {
          Error::addError
          (
          'Failed to write '.$path.$name.'.php'
          );
          Message::addError( 'Failed to write '.$path.$name.'.php' );
        }
        else
        {
          Message::addMessage( 'Successfully wrote '.$path.$name.'.php');
        }

      }//end foreach( $quotes as $name => $data )

    }//end foreach( $this->quotesPool as $modName => $quotes )

  }//end public function write()

} // end abstract class LibParserDdlAbstract

