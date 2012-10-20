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
abstract class LibCartridgeDdl
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

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
   * The Name of the DBMS
   * @var String
   */
  protected $dbName = 'webfrap';

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
  protected $quotesPool  = array();

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
   *
   * @var unknown_type
   */
  public $appendDump = null;

  /**
   * map for the quoting
   *
   * @var array
   */
  protected $quotesMap = array();


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

    if( is_string($xmlFile) )
    {
      $this->xmlFile = $xmlFile;
      $this->load();
    }
    elseif( is_object($xmlFile) and $xmlFile instanceof simplexmlElement )
    {
      $this->ddlXml = $xmlFile;
    }


    $this->outputFolder = PATH_GW.'data/code_gen_cache/';

    $this->subPath      = '/data/ddl/'.$this->schema.'/'.$this->type.'/';
    $this->quotesPath   = '/conf/db/'.$this->type.'/';


  }//end public function __construct( $xmlFile )

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
   * @return string
   */
  public function getDbXml()
  {
    return $this->dbXml;
  }//end public function getDbXml

  /**
   * should the meta flag be ignored
   * @return string
   */
  public function setIgnoreMeta( $ignore = true )
  {
    $this->ignoreMeta = $ignore;
  }//end public function setIgnoreMeta( $ignore = true )

  /**
   * setter for Create Key Tree
   *
   * @param boolean $set
   */
  public function setCreateKeyTree( $set = true )
  {
    $this->createKeyTree = $set;
  }//end public function setCreateKeyTree( $set = true )

  /**
   * @return string
   */
  public function getTables()
  {
    return $this->tables;
  }//end public function getTables()

  /**
   *
   * @param $path
   * @return unknown_type
   */
  public function appendDump( $path )
  {
    $this->appendDump = $path;
  }//end public function appendDump( $path )


  /**
   * request the ddl for a table
   *
   * @param string $key
   */
  public function getTable( $modName,  $entityName )
  {

    if( isset( $this->tables[$modName][$entityName] ))
    {
      return $this->tables[$modName][$entityName];
    }
    else
    {
      Message::addWarning('not found sql for: '.$modName.'::'.$entityName);
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

    if( isset( $this->keyTree[$key] ))
      return $this->keyTree[$key];

    else
      return array();

  }//end public function getFk( $key )

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

  /**
   * @param String $owner
   */
  public function setOwner( $owner )
  {
    $this->owner = $owner;
  }//end public function setOwner( $owner )

  /**
   * @var String $schema
   */
  public function setSchema( $schema )
  {

    $this->schema     = $schema;

    $this->subPath      = '/data/ddl/'.$this->schema.'/'.$this->type.'/';
    $this->quotesPath   = '/conf/db/'.$this->type.'/';

  }//end public function setSchema()

  /**
   * @return  String
   */
  public function getTableSql()
  {
    return $this->parsedTables;
  }//end public function getTableSql()

  /**
   * @return String
   */
  public function getSequenceSql()
  {
    return $this->parsedSequences;
  }//end public function getSequenceSql()

  /**
   * @return String
   */
  public function getKeySql()
  {
    return $this->parsedKeys;
  }//end public function getKeySql()

  /**
   * @return String
   */
  public function setOutputFolder( $folder )
  {
    $this->outputFolder =  $folder.'/';
  }//end public function setOutputFolder( $folder )

  /**
   * @return string
   */
  public function setRegistry( $registry )
  {
    $this->registry = $registry;
  }//end public function setRegistry

////////////////////////////////////////////////////////////////////////////////
// Logic
////////////////////////////////////////////////////////////////////////////////

  /**
   *
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
   * parse all quotes
   * @return void
   */
  public function parseQuotes()
  {


    foreach( $this->ddlXml->tables->table as $tab )
    {

      if( !isset( $tab->mal->meta ) or $this->ignoreMeta )
      {
        $tabName = (string)$tab['name'];

        $this->registry->setActiv($tabName);


        $quote = '$this->entityMeta[\''.$tabName.'\'] = array
('.NL;

        foreach( $tab->row as $row )
          $quote .= $this->parseMapRow( $row );

        $quote .= ');'.NL.NL;

        $quote .= '$this->entityReferences[\''.$tabName.'\'] = array
('.NL;

/*
<embededTables>
<table type="pre" binding="free" relation="oneToOne" refTab="rental_article" refId="id_article" >wawi_article</table>
<table type="pre" binding="free" relation="oneToMany" refTab="process_process" refId="id_status" >process_status</table>

<table
  type="post" binding="free" relation="manyToMany" refTab="rental_article"
  refId="" connectTable="rental_transaction_entry" connectIdTable="id_transaction"
  connectIdRef="id_article" >rental_transaction</table>
<table type="post" binding="connected" relation="manyToOne" refTab="rental_article" refId="id_article" >wawi_article_obj</table>
</embededTables>
*/

        $refRowid = '';
        $refElse = '';

        foreach( $this->ddlXml->tables->table as $subTab  )
        {

          if( isset( $subTab->mal->embededTables ) )
          {

            $emb = $subTab->mal->embededTables;

            foreach( $emb->table as $embTab )
            {

              $rel = strtolower($embTab['relation']);

              if( (string)$embTab['refTab'] != $tabName  )
              {
                if( $rel == Bdl::MANY_TO_MANY &&   (string)$embTab['refTab'] ==  $tabName )
                {
                 $entityName = SParserString::subToCamelCase( (string)$embTab['connectTable'] );
                 $refRowid .= "array( 'type' => 'manyToOne', 'entity' => '$entityName' , 'refId' => '".$embTab['connectIdTable']."' , 'delete' => true ),".NL;
                }

                continue;
              }

              if( $rel == 'manytomany' )
              {
                $entityName = SParserString::subToCamelCase( (string)$embTab['connectTable'] );
                $refRowid .= "array( 'type' => 'manyToOne', 'entity' => '$entityName' , 'refId' => '".$embTab['connectIdRef']."' , 'delete' => true ),".NL;
                continue;
              }

              $t = $this->registry->names[(string)$embTab];

              if( $embTab['binding'] == 'free' )
                $delete = 'false';
              else
                $delete = 'true';

              $entityName = SParserString::subToCamelCase( (string)$embTab );

              if( $embTab['type'] == 'pre' )
              {
                $refElse .= "'".$embTab['refId']."' => array( 'type' => '".$embTab['relation']."', 'entity' => '$entityName' , 'refId' => 'rowid' , 'delete' => $delete ),".NL;
              }
              else
              {
                $refRowid .= "array( 'type' => '".$embTab['relation']."', 'entity' => '$entityName' , 'refId' => '".$embTab['refId']."' , 'delete' => $delete ),".NL;
              }
              //'id_people' => array( 'type' => 'oneToOne', 'entity' => 'CorePeople' , 'refId' => 'rowid' , 'delete' => true ),
            }
          }
        }//end foreach

        if( $refRowid != '' )
          $quote .= "'rowid' => array(".NL.$refRowid.'),'.NL;

        if( $refElse != '' )
          $quote .= $refElse.NL;

        $quote .= ');'.NL;

        $modName = SParserString::getModname($tabName,true);

        $this->quotesPool[$modName][$tabName] = $quote;
      }

    }//end foreach

  }//end public function parseAllQuotes()

  /**
   * Enter description here...
   * @param simpleXml $rowXml
   */
  public function parseMapRow( $rowXml )
  {

    $name = (string)$rowXml['name'];
    $type = strtolower((string)$rowXml['type']);

    $validator = strtolower((string)$rowXml['validator']);

    if( isset($rowXml['maxSize']) && trim($rowXml['maxSize']) != '' )
      $maxsize = (string)$rowXml['maxSize'];
    else
      $maxsize = 'null';

    if( isset($rowXml['minSize']) && trim($rowXml['minSize']) != '' )
      $minsize = (string)$rowXml['minSize'];
    else
      $minsize = 'null';

    if( (string)$rowXml['notNull'] == 'true' )
      $notnull = 'true';
    else
      $notnull = 'false';

    $row = "'".$name."' => array( ";

    $row .= '\''.$validator.'\', ' // Type
        .' '.$notnull.', ' // Allows Empty Value?
        .' '.$maxsize.', ' // Max Size
        .' '.$minsize.','; // Min Size
    $row .= $this->quotesMap[$type]." ), ".NL;

    return $row;

  }//end public function parseMapRow

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

    return highlight_string(
      '--- DBMS TYPE '.$this->dbmsName.' --'.NL.NL.$this->parsedSequences.
      $this->parsedTables.$this->parsedKeys,true);

  }//end public function highlight()

  /**
   *
   */
  public function write( $outputFolder = null )
  {


    if($outputFolder)
      $this->setOutputFolder($outputFolder);

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

} // end abstract class LibCartridgeDdl
