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
class LibCartridgeGlobalDdlPostgresql
  extends LibCartridgeBdlDdl
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $type     = 'postgresql';

  /**
   * The Name of the DBMS
   * @var String
   */
  protected $dbmsName = 'Postgresql';

  /**
   * @var array
   */
  protected $data = array();

  /**
   * @var array
   */
  public $useOid      = false;


////////////////////////////////////////////////////////////////////////////////
// init
////////////////////////////////////////////////////////////////////////////////

  /**
   * (non-PHPdoc)
   * @see src/lib/LibCartridge#init()
   */
  protected function init()
  {
    $this->multiple = LibDbAdmin::getInstance('Postgresql')->getMultiple();
  }//end protected function init

 /**
   * @return void
   */
  public function parse()
  {


    if(!$this->pathOutput)
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }

    //$this->parseSequences();
    $this->buildSqlTables();
    $this->buildForeignKeys();

    foreach( $this->root as $entity )
    {

      if(!$this->root->setActiv( $entity ))
        continue;

      $name         = $entity->getName();
      
      if( $this->builder->sandbox )
      {
        $folderKey = 'genf';
      }
      else
      {
        $folderKey = 'hand';
      }
      
      $folderPath   = $folder.$folderKey.'/'.$name->lower('module').'/data/ddl/postgresql/'.$this->schema.'/';
      
      if( !isset($this->indices[$name->lower('module')]['indices']) )
        $this->indices[$name->lower('module')]['indices'] = '';

      $code = $this->buildIndices($entity);

      $this->indices[$name->lower('module')]['indices'] .= $code;

      $this->writeFile( $code, $folderPath, $name->name.'_index.sql' );

    }//end foreach

    foreach( $this->indices as $module => $code )
    {
      $tmpPath   = $folder.$folderKey.'/'.$module.'/data/ddl/postgresql/'.$this->schema.'/';
      $this->writeFile( $code['indices'], $tmpPath, 'module_'.$module.'_index.sql' );
    }

    /*
    foreach( $this->sequences as $domainName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedSequences[$domainName]))
          $this->parsedSequences[$domainName] = '-- SEQUENCES --'.NL.NL;

        $this->parsedSequences[$domainName] .= $tab;
      }
    }
    */

    foreach( $this->tables as $domainName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedTables[$domainName]))
          $this->parsedTables[$domainName] = '-- TABLES --'.NL.NL;

        $this->parsedTables[$domainName] .= $tab;
      }
    }


    foreach( $this->tables as $domainName => $tables )
    {
      foreach( $this->foreignKeys as $key )
      {
        if(!isset($this->parsedKeys[$domainName]))
          $this->parsedKeys[$domainName] = '-- FOREIGN KEYS --'.NL.NL;

        $this->parsedKeys[$domainName] .= $key;
      }
    }


  }//end public function parse

  /**
   * @return void
   */
  public function tmpParse()
  {

    $this->buildSqlTables( true );
    $this->buildForeignKeys();

  }//end public function tmpParse  */

  /**
   * @param string $outputFolder the folder where to save the ddl files
   */
  public function write(  )
  {

    if(!$this->pathOutput)
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }
    
    if( $this->builder->sandbox )
    {
      $key = 'genf';
    }
    else 
    {
      $key = 'hand';
    }


    $fullDump = '';


    foreach( $this->parsedTables as $domainName => $parsed )
    {

      $path = $folder.$key.'/'.$domainName.'/data/ddl/postgresql/'.$this->schema.'/';

      $this->writeFile( $parsed, $path, $domainName.'_table.sql' );

      // create a full dump
      $fullDump .= $parsed;

    }//end foreach */



    /*
    foreach( $this->parsedSequences as $domainName => $parsed )
    {
      $path = $this->outputFolder.'genf/'.$domainName.$this->subPath;
      if(!SFilesystem::createFolder($path))
      {
        Error::report('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$domainName.'_sequence.sql' , $parsed ))
      {
        Error::report
        (
        'Failed to write '.$path.$domainName.'_sequence.sql'
        );
      }

      // create a full dump
      //$fullDump = $parsed.$fullDump;

    }//end foreach( $this->parsedSequences as $domainName => $parsed )
    */


    foreach( $this->parsedKeys as $domainName => $parsed )
    {

      $path = $folder.$key.'/'.$domainName.'/data/ddl/postgresql/'.$this->schema.'/';

      $this->writeFile( $parsed, $path, $domainName.'_keys.sql' );

      // create a full dump
      $fullDump .= $parsed;

    }//end foreach( $this->parsedKeys as $domainName => $parsed )

    foreach( $this->indices as $code )
    {
      $fullDump .= $code['indices'];
    }

    $path = $folder.$key.'/all/data/ddl/postgresql/'.$this->schema.'/';

    $seqStart = LibDbAdminPostgresql::SEQ_START;

    $sqlDump = <<<CODE
CREATE SCHEMA {$this->schema} AUTHORIZATION {$this->owner};
SET SEARCH_PATH TO {$this->schema};

CREATE SEQUENCE {$this->schema}.entity_oid_seq START {$seqStart} INCREMENT BY 1 ;
ALTER TABLE  {$this->schema}.entity_oid_seq OWNER TO {$this->owner};

CODE;

    $sqlDump .= $fullDump .NL;

    if( $this->appendDump && file_exists($this->appendDump) )
      $sqlDump .= SFiles::read($this->appendDump);

    // a Full dump with all tables
    $this->writeFile( $sqlDump, $path, 'FullDump.sql' );


  }//end public function write  */

////////////////////////////////////////////////////////////////////////////////
// parser
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @return void
   *
   * <sequences>
   *  <sequence name="name" start="0" increment="1"  />
   * </sequences>
   */
  public function parseSequences()
  {


    $sequences = array();

    foreach( $this->nodes as $entity )
    {
      foreach( $entity as $attribute )
      {
        if( $sequence = $attribute->sequence() )
        {
          $sequences[$sequence] = $entity->source;
        }
      }
    }


    foreach( $sequences as  $sequence => $tableName )
    {

      $ddl = '';


      $ddl .= '-- Sequence: '.$sequence.'-- '.NL;
      $ddl .= 'CREATE SEQUENCE '.$sequence
        .' START 1 '
        .' INCREMENT BY 1 ;'.NL;

      if( $this->owner )
        $ddl .= 'ALTER TABLE  '.$sequence.' OWNER TO '.$this->owner.';'.NL;

      $ddl .= NL.'---~~Seperator~~---'.NL;
      $ddl .= NL;

      $domainName = SParserString::getDomainName($tableName,true);

      $this->sequences[$domainName][$sequence] = $ddl;

    }//end foreach( $sequences->sequence as $seq )

    //GRANT ALL ON TABLE  $schemaName.finance_customer_rowid_seq    TO GROUP webfrapverwaltung;

  }//end public function createSequences */


  /**
   *
   *
   * @return void
   *
   */
  public function buildSqlTables( $simple = false )
  {

    foreach( $this->node as $entity )
    {

      $name = $entity->name;
      //$this->parseInsertEntity($tabName);

      $table = '-- Table: '.$name->source.'--'.NL;
      $table .= 'CREATE TABLE '.$name->source.' ('.NL;


      foreach( $entity as $attribute )
         $table .= $this->buildSqlTableRow( $attribute , $entity->name );

      $table .='PRIMARY KEY ( '.$this->builder->rowidKey.' )'.NL;

      $table        .= ');'.NL;

      if( $this->owner )
      {
        $table .= 'ALTER TABLE '.$name->source.' OWNER TO '.$this->owner.';'.NL;
      }

      $table .= NL;
      $table .= NL.'---~~Seperator~~---'.NL.NL;


      ///TODO meta data for GRANTS!
      //GRANT ALL ON TABLE  $schemaName.finance_customer TO GROUP webfrapverwaltung;

      $this->tables[$name->lower('module')][$name->source] = $table;

    }

  }//end public function parseSqlTable */

  /**
   *
   * @param LibGenfNodeAttribute $attribute
   * @param LibGenfName $name
   * @return string
   *
   * input:
   * <row name="rowid" type="int" size="" notNull="false" sequence="groupware_project_rowid" default="" unique="false"  />
   */
  public function buildSqlTableRow( $attribute , $name )
  {

    $row = $attribute->name().' ';

    if( $size = $attribute->size() )
    {

      $type = $attribute->dbType();

      if( $type == 'numeric' )
       $size = str_replace( '.' , ',', $attribute->size() );

      else
       $size = $attribute->size();

      if( in_array( $type , $this->multiple )   )
      {

        $type = str_replace( array('[',']') , array('','') , $type  );
        $row .= $type."(".$size.")[] ";
      }
      else
      {
        $row .= $type."(".$size.") ";
      }


    }
    else
    {
      $row .= $attribute->dbType().' ';
    }

    if( $attribute->required() )
      $row .= ' NOT NULL ';

    if( $sequence = $attribute->sequence()   )
    {

      if( is_string( $sequence ) )
      {
        $row .= " DEFAULT nextval('".$sequence."') ";
      }
      else
      {
        $sequName = $name->source.'_'.$attribute->name().'_seq';
        $row .= " DEFAULT nextval('".$sequName."') ";
      }

    }
    elseif( $attribute->name( $this->builder->rowidKey ) )
    {

      if( $this->useOid )
      {
        $seqName = Db::SEQUENCE;
      }
      else
      {
        $seqName = $name->source.'_'.$attribute->name().'_seq';
      }

      $row .= " DEFAULT nextval('".$seqName."'::regclass) ";

    }
    elseif( $default =  $attribute->defaultValue() )
    {
      $row .= " DEFAULT '{$default}' ";
    }

    if( $attribute->unique() )
      $row .= ' UNIQUE ';

    $row .= ', '.NL;

    return $row;

  }//end public function buildSqlTableRow */



  /**
   * Parser for the types
   * @return void
   *
   * <key srcTable="finance_company" srcCol="" targetTable="finance_department" targetCol=""   />
   */
  public function buildForeignKeys( )
  {

    /// TODO Foreign keys wieder unterstützen
    foreach( $this->node as $entity )
    {

      $tabName = $entity->name();

      foreach( $entity as $attribute  )
      {

        if(!$target = $attribute->target())
          continue;

        $attrName = $attribute->name();

        $ddl = '-- Foreign Key for: '.$tabName.'--'.NL;
        $ddl .= 'ALTER TABLE '.$tabName.' ADD FOREIGN KEY ';

        $ddl .= '('.$attrName.')' ;


        $ddl .= ' REFERENCES '.$this->schema.'.'.$tabName
          .' on delete restrict on update cascade;'.NL;

        $ddl .= NL.'---~~Seperator~~---'.NL;

        $this->foreignKeys[] = $ddl;


      }

    }//end foreach

  }//end public function parseAllForeignKeys */




/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * erstellen aller nötigen indices für eine bestimmte tabelle
   *
   * @param LibGenfTreeNodeEntity $entity
   * @return string
   */
  public function buildIndices( $entity )
  {

    $code = $this->buildSearchIndices($entity);


    return $code;


  }//end public function buildIndices */



  /**
   * erstellen der sql indice für alle such
   *
   * @param LibGenfTreeNodeEntity $entity
   * @return string
   */
  public function buildSearchIndices( $entity )
  {

    $name = $entity->name;
    
    $code = NL.NL.'-- indices for '.$entity->name.' -- '.NL;

    foreach( $entity as $attribute )
    {
      // only create indices for search fields
      if( !$attribute->search() )
        continue;

      $attrName = $attribute->name;

      $type = $attribute->type();

      if( in_array( $type, array('text') ) )
      {
        $code .= " CREATE INDEX {$name->name}_{$attrName->name}_search_idx ON {$name->name} ( upper({$attrName->name}) ); ".NL;
      }
      elseif( in_array( $type, array('int','numeric') ) )
      {
        $code .= " CREATE INDEX {$name->name}_{$attrName->name}_search_idx ON {$name->name} ( {$attrName->name} ); ".NL;
      }
      elseif( $attribute->fk() )
      {
        $code .= " CREATE INDEX {$name->name}_{$attrName->name}_fk_idx ON {$name->name} ( {$attrName->name} ); ".NL;
      }

    }

    return $code;

  }//end public function createSequences */


} // end class LibCartridgeWbfDdlPostgresql

