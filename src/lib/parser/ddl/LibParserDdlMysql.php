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
class LibParserDdlMysql
  extends LibParserDdlAbstract
{

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $type = 'mysql';

  /**
   * The Name of the DBMS
   * @var String
   */
  protected $dbmsName = 'Mysql';


////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * <table name="groupware_project" >
   *  <row name="rowid" type="int" size="" notNull="false" sequence="groupware_project_rowid" default="" unique="false"  />
   *  <row name="acronym" type="varchar" size="150" notNull="false" sequence="" default="" unique="false"  />
   *  <row name="name" type="varchar" size="250" notNull="false" sequence="" default="" unique="false"  />
   *  <primaryKey>
   *   <key>rowid</key>
   *  </primaryKey>
   * </table>
   *
   * @return void
   *
   */
  public function parseSqlTables( )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    if( $this->parseAll )
    {
      $this->parseAllSqlTables();
    }
    else
    {
      $this->parseMapSqlTables();
    }

  }//end public function parseSqlTable( )

  /**
   * parse only the tables in the map
   *
   */
  public function parseMapSqlTables()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

      foreach( $this->ddlXml->tables->table as $tab )
    {

      $tabName = (string)$tab['name'];

      if( isset( $this->toParse[$tabName] ) )
      {

        $name = $tabName;

        $table = '-- Table: '.$name.'--'.NL;
        $table .= 'CREATE TABLE '.$name.' ('.NL;

        foreach( $tab->row as $row )
        {
          $table .= $this->parseSqlTableRow( $row );
        }

        $table .= $this->parseSqlTablePk( $tab->primaryKey );

        $table .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8;'.NL;

        $table .= NL;

        ///TODO meta data for GRANTS!
        //GRANT ALL ON TABLE  $schemaName.finance_customer TO GROUP webfrapverwaltung;

        $this->tables[(string)$tab['name']] = $table;
      }
    }
  }

  /**
   * parse all sql tables
   *
   */
  public function parseAllSqlTables()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    foreach( $this->ddlXml->tables->table as $tab )
    {
      if( !isset( $tab->mal->meta) or $this->ignoreMeta  )
      {
        $tabName = (string)$tab['name'];

        $name = $tabName;

        $table = '-- Table: '.$name.'--'.NL;
        $table .= 'CREATE TABLE `'.$name.'` ('.NL;

        foreach( $tab->row as $row )
        {
          $table .= $this->parseSqlTableRow( $row );
        }

        $table .= $this->parseSqlTablePk( $tab->primaryKey );

        $table .= ') ENGINE=InnoDB DEFAULT CHARSET=utf8;'.NL;
        $table .= NL.'---~~Seperator~~---'.NL;

        $table .= NL;

        ///TODO meta data for GRANTS!
        //GRANT ALL ON TABLE  $schemaName.finance_customer TO GROUP webfrapverwaltung;
        $modName = SParserString::getModname($tabName,true);
        $this->tables[$modName][$tabName] = $table;

      }//end if( !isset( $tab->mal->meta) or $this->ignoreMeta  )

    }//end foreach( $this->ddlXml->tables->table as $tab )

  }//end public function parseAllSqlTables()

  /**
   *
   * @param Simplexml $rowXml
   * @return String
   *
   * input:
   * <row name="rowid" type="int" size="" notNull="false" sequence="groupware_project_rowid" default="" unique="false"  />
   */
  public function parseSqlTableRow( $rowXml )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($rowXml));

    $type = strtoupper((string)$rowXml['type']);

    $row = '`'.(string)$rowXml['name']."` ";

    if( trim($rowXml['size']) != '' )
    {
      $row .= $type."(".(string)$rowXml['size'].") ";
    }
    else
    {

      // if this is char that should be in most cases a boolean
      if($type=='CHAR')
      {
        Log::warn('No Size for char, so we expect that this should emulate a boolean and take size 1');
        $row .= $type."(1) ";
      }
      elseif($type=='VARCHAR')
      {// ok sombody forgot to set a size we take 150, this must be enough
        Log::warn('No Size for varchar so we take 150');
        $row .= $type."(150) ";
      }
      else
      {
        $row .= (string)$rowXml['type'].' ';
      }


    }


    if( trim($rowXml['sequence']) != '' )
    {
      $row .= " AUTO_INCREMENT ";
    }//end if( trim($rowXml['sequence']) != '' )
    else
    {
      if( (string)$rowXml['notNull'] == 'true' )
      {
        $row .= ' NOT NULL ';
      }
      else
      {
        $row .= ' NULL ';
      }

      if( (string)$rowXml['unique'] == 'true' )
      {
        $row .= " UNIQUE ";
      }

    }//end if( trim($rowXml['sequence']) != '' )



    $row .= ", ".NL;

    return $row;

  }//end public function parseSqlTableRow()

  /**
   *
   * @param Simplexml $pkXml
   * @return String
   *
   * input:
   * <primaryKey>
   *  <key>rowid</key>
   * </primaryKey>
   *
   */
  public function parseSqlTablePk( $pkXml )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__,array($pkXml));

    $ddl ='PRIMARY KEY (';
    $ddl .= SParserString::xmlToComSepStr( $pkXml , 'key' );
    $ddl .= ' )'.NL;

    return $ddl;

  }//end public function parseSqlTablePk( $pkXml )

  /**
   * Parser for the types
   * @return void
   *
   * <key srcTable="finance_company" srcCol="" targetTable="finance_department" targetCol=""   />
   */
  public function parseForeignKeys( )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    if( $this->parseAll )
    {
      $this->parseAllForeignKeys();
    }
    else
    {
      $this->parseMapForeignKeys();
    }

  }//end public function parseForeignKeys

  /**
   * Parser for the types
   * @return void
   *
   * <key srcTable="finance_company" srcCol="" targetTable="finance_department" targetCol=""   />
   */
  public function parseAllForeignKeys( )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    foreach( $this->ddlXml->foreignKeys->key as $key )
    {
      if( !isset($key['meta']) or $this->ignoreMeta )
      {
        $tabName = (string)$key['srcTable'];

        $ddl = '-- Foreign Key for: '.$tabName.'--'.NL;
        $ddl .= 'ALTER TABLE '.$tabName
          .' ADD FOREIGN KEY ';

        if( trim($key['srcCol']) != '' )
        {
          $ddl .= '('.(string)$key['srcCol'].')' ;
        }

        $ddl .= ' REFERENCES '.$this->schema.'.'.(string)$key['targetTable']
          .' on delete restrict on update cascade;'.NL;
        $ddl .= NL.'---~~Seperator~~---'.NL;
        $ddl .= NL;

        $this->foreignKeys[] = $ddl;

        if( $this->createKeyTree )
        {
          $modName = SParserString::getModname($tabName,true);
          $this->keyTree[$modName][$tabName][] = array( 'ddl' =>  $ddl ,'xml' => $key );
        }
      }
    }

  }//end public function parseForeignKeys

  /**
   * Parser for the types
   * @return void
   *
   * <key srcTable="finance_company" srcCol="" targetTable="finance_department" targetCol=""   />
   */
  public function parseMapForeignKeys( )
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    foreach( $this->ddlXml->foreignKeys->key as $key )
    {

      $tabName = (string)$key['srcTable'];

      if( isset( $this->toParse[$tabName] ) )
      {
        $ddl = '-- Foreign Key for: '.$tabName.'--'.NL;
        $ddl .= 'ALTER TABLE '.$tabName.' ADD FOREIGN KEY ';

        if( trim($key['srcCol']) != '' )
        {
          $ddl .= '('.(string)$key['srcCol'].')' ;
        }

        $ddl .= ' REFERENCES '.$this->schema.'.'.(string)$key['targetTable']
          .' on delete restrict on update cascade;'.NL;

        $this->foreignKeys[] = $ddl;

        if( $this->createKeyTree )
        {
          $this->keyTree[$tabName][] = array( 'ddl' =>  $ddl ,'xml' => $key );
        }

      }

    }//end foreach( $this->ddlXml->foreignKeys->key as $key )

  }//end public function parseForeignKeys

  /**
   * @return void
   */
  public function parse()
  {
    if( Log::$levelDebug )
      Log::start(__file__,__line__,__method__);

    $this->parseQuotes();
    $this->parseSqlTables();
    $this->parseForeignKeys();

    foreach( $this->tables as $modName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedTables[$modName]))
        {
          $this->parsedTables[$modName] = '-- TABLES --'.NL.NL;
        }
        $this->parsedTables[$modName] .= $tab;
      }
    }

    foreach( $this->tables as $modName => $tables )
    {
      foreach( $this->foreignKeys as $key )
      {
        if(!isset($this->parsedKeys[$modName]))
        {
          $this->parsedKeys[$modName] = '-- FOREIGN KEYS --'.NL.NL;
        }
        $this->parsedKeys[$modName] .= $key;
      }
    }

    //$ddl = $this->parsedTables.$this->parsedKeys;

  }//end public function parse

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
          Error::addError('Failed to write '.$path.$name.'.php');
          Message::addError( 'Failed to write '.$path.$name.'.php' );
        }
        else
        {
          Message::addMessage( 'Successfully wrote '.$path.$name.'.php');
        }

      }//end foreach( $quotes as $name => $data )

    }//end foreach( $this->quotesPool as $modName => $quotes )

  }//end public function write()

} // end class LibParserDdlMysql

