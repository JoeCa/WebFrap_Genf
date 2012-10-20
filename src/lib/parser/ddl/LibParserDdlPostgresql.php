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
class LibParserDdlPostgresql
  extends LibParserDdlAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $type = 'postgresql';

  /**
   * The Name of the DBMS
   * @var String
   */
  protected $dbmsName = 'Postgresql';



////////////////////////////////////////////////////////////////////////////////
// Parser
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


    if( $this->parseAll )
    {
      $this->parseAllSequences();
    }
    else
    {
      $this->parseMapSequences();
    }

  }//end public function createSequences()

  /**
   *
   * @return void
   *
   * <sequences>
   *  <sequence name="name" start="0" increment="1"  />
   * </sequences>
   */
  public function parseAllSequences()
  {


    $sequences = $this->ddlXml->sequences;

    foreach( $sequences->sequence as $seq )
    {
      if( !isset($seq['meta'] ) or $this->ignoreMeta )
      {
        $tabName = (string)$seq['tabName'];

        $ddl = '';
        $name = $this->schema.'.'.(string)$seq['name'];

        $ddl .= '-- Sequence: '.$name.'--'.NL;
        $ddl .= 'CREATE SEQUENCE '.$name
          .' START '.(string)$seq['start']
          .' INCREMENT BY '.(string)$seq['increment'].' ;'.NL;

        $ddlInstall = $ddl;

        if( $this->owner )
        {
          $ddl .= 'ALTER TABLE  '.$name.' OWNER TO '.$this->owner.';'.NL;
          $ddlInstall .= 'ALTER TABLE  '.$name.' OWNER TO {$dbuser};'.NL;
        }
        $ddl .= NL.'---~~Seperator~~---'.NL;
        $ddl .= NL;

        $ddlInstall .= NL.'---~~Seperator~~---'.NL;
        $ddlInstall .= NL;

        $modName = SParserString::getModname($tabName,true);

        $this->sequences[$modName][(string)$seq['name']] = $ddl;
        $this->sequencesInstall[$modName][(string)$seq['name']] = $ddlInstall;
      }
    }//end foreach( $sequences->sequence as $seq )

    //GRANT ALL ON TABLE  $schemaName.finance_customer_rowid_seq    TO GROUP webfrapverwaltung;

  }//end public function createSequences()

  /**
   *
   * @return void
   *
   * <sequences>
   *  <sequence name="name" start="0" increment="1"  />
   * </sequences>
   */
  public function parseMapSequences()
  {

    $sequences = $this->ddlXml->sequences;

    foreach( $sequences->sequence as $seq )
    {

      $tabName = (string)$seq['tabName'];

      if( isset( $this->toParse[$tabName] ) )
      {
        $ddl = '';
        $name = $this->schema.'.'.(string)$seq['name'];

        $ddl .= '-- Sequence: '.$name.'--'.NL;
        $ddl .= 'CREATE SEQUENCE '.$name
          .' START '.(string)$seq['start']
          .' INCREMENT BY '.(string)$seq['increment'].' ;'.NL;

        if( $this->owner )
        {
          $ddl .= 'ALTER TABLE  '.$name.' OWNER TO '.$this->owner.';'.NL;
        }
        $ddl .= NL;

        $modName = SParserString::getModname($tabName,true);
        $this->sequences[$modName][$tabName] = $ddl;

      }

    }//end foreach( $sequences->sequence as $seq )

    //GRANT ALL ON TABLE  $schemaName.finance_customer_rowid_seq    TO GROUP webfrapverwaltung;

  }//end public function createSequences()

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
  public function parseAllSqlTables( )
  {


    foreach( $this->ddlXml->tables->table as $tab )
    {

      if( !isset($tab->mal->meta) or $this->ignoreMeta )
      {
        $tabName = (string)$tab['name'];

        if( Log::$levelTrace )
          Log::logTrace(__file__,__line__,'parsing '.$tabName);

        $this->parseInsertEntity($tabName);

        $name = $this->schema.'.'.$tabName;
        $nameInstall = '{$db_schema}'.'.'.$tabName;

        $table = '-- Table: '.$name.'--'.NL;
        $table .= 'CREATE TABLE '.$name.' ('.NL;

        $tableInstall = '-- Table: '.$nameInstall.'--'.NL;
        $tableInstall .= 'CREATE TABLE '.$nameInstall.' ('.NL;

        $rows = '';

        foreach( $tab->row as $row )
        {
          $rows .= $this->parseSqlTableRow( $row );
        }

        $table       .= $rows;
        $tableInstall .= $rows;

        $pk = $this->parseSqlTablePk( $tab->primaryKey );
        $table .= $pk;
        $tableInstall .= $pk;

        $table .= ');'.NL;
        $tableInstall .= ');'.NL;

        if( $this->owner )
        {
          $table .= 'ALTER TABLE '.$name.' OWNER TO '.$this->owner.';'.NL;
          $tableInstall .= 'ALTER TABLE '.$name.' OWNER TO {$db_user};'.NL;
        }

        $table .= NL;
        $table .= NL.'---~~Seperator~~---'.NL.NL;

        $tableInstall .= NL;
        $tableInstall .= NL.'---~~Seperator~~---'.NL.NL;

        ///TODO meta data for GRANTS!
        //GRANT ALL ON TABLE  $schemaName.finance_customer TO GROUP webfrapverwaltung;

        $modName = SParserString::getModname($tabName,true);

        $this->tables[$modName][$tabName] = $table;
        $this->tablesInstall[$modName][$tabName] = $tableInstall;

      }//end !isset($tab->mal->meta)

    }

  }//end public function parseSqlTable( )

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
  public function parseMapSqlTables( )
  {

    foreach( $this->ddlXml->tables->table as $tab )
    {

      $tabName = (string)$tab['name'];

      if( isset( $this->toParse[$tabName] ) )
      {
        $name = $this->schema.'.'.$tabName;

        $table = '-- Table: '.$name.'--'.NL;
        $table .= 'CREATE TABLE '.$name.' ('.NL;

        foreach( $tab->row as $row )
        {
          $table .= $this->parseSqlTableRow( $row );
        }

        $table .= $this->parseSqlTablePk( $tab->primaryKey );

        $table .= ');'.NL;


        if( $this->owner )
        {
          $table .= 'ALTER TABLE '.$name.' OWNER TO '.$this->owner.';'.NL;
        }

        $table .= NL;

        ///TODO meta data for GRANTS!
        //GRANT ALL ON TABLE  $schemaName.finance_customer TO GROUP webfrapverwaltung;
        $modName = SParserString::getModname($tabName,true);
        $this->tables[$modName][$tabName] = $table;
      }
    }

  }//end public function parseSqlTable( )

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

    $row = (string)$rowXml['name'].' ';

    if( trim($rowXml['size']) != '' )
    {

      $type = strtolower(trim($rowXml['type']));
      if( $type == 'numeric' )
      {
        $size = str_replace( '.' , ',', $rowXml['size'] );
      }
      else
      {
        $size = trim($rowXml['size']);
      }

      $row .= (string)$rowXml['type']."(".$size.") ";
    }
    else
    {
      $row .= (string)$rowXml['type'].' ';
    }

    if( (string)$rowXml['notNull'] == 'true' )
    {
      $row .= ' NOT NULL ';
    }

    if( trim($rowXml['sequence']) != '' )
    {
      $row .= " DEFAULT nextval('".$this->schema.'.'.(string)$rowXml['sequence']."_seq') ";
    }

    if( (string)$rowXml['unique'] == 'true' )
    {
      $row .= ' UNIQUE ';
    }

    $row .= ', '.NL;

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


    foreach( $this->ddlXml->foreignKeys->key as $key )
    {
      if( !isset($key['meta']) or $this->ignoreMeta )
      {
        $tabName = (string)$key['srcTable'];

        $ddl = '-- Foreign Key for: '.$tabName.'--'.NL;
        $ddl .= 'ALTER TABLE '.$this->schema.'.'.$tabName.' ADD FOREIGN KEY ';

        if( trim($key['srcCol']) != '' )
        {
          $ddl .= '('.(string)$key['srcCol'].')' ;
        }

        $ddl .= ' REFERENCES '.$this->schema.'.'.(string)$key['targetTable']
          .' on delete restrict on update cascade;'.NL;
        $ddl .= NL.'---~~Seperator~~---'.NL;

        $this->foreignKeys[] = $ddl;

        if( $this->createKeyTree )
        {
          $modName = SParserString::getModname($tabName,true);
          $this->keyTree[$modName][$tabName][] = array( 'ddl' =>  $ddl ,'xml' => $key );
        }

      }

    }//end foreach

  }//end public function parseAllForeignKeys( )

  /**
   * Parser for the types
   * @return void
   *
   * <key srcTable="finance_company" srcCol="" targetTable="finance_department" targetCol=""   />
   */
  public function parseMapForeignKeys( )
  {


    foreach( $this->ddlXml->foreignKeys->key as $key )
    {

      $tabName = (string)$key['srcTable'];

      if( isset( $this->toParse[$tabName] ) )
      {
        $ddl = '-- Foreign Key for: '.$tabName.'--'.NL;
        $ddl .= 'ALTER TABLE '.$this->schema.'.'.$tabName
          .' ADD FOREIGN KEY ';

        if( trim($key['srcCol']) != '' )
        {
          $ddl .= '('.(string)$key['srcCol'].')' ;
        }

        $ddl .= ' REFERENCES '.$this->schema.'.'.(string)$key['targetTable']
          .' on delete restrict on update cascade;'.NL;

        $this->foreignKeys[] = $ddl;

        if( $this->createKeyTree )
        {
          $modName = SParserString::getModname($tabName,true);
          $this->keyTree[$modName][$tabName][] = array( 'ddl' =>  $ddl ,'xml' => $key );
        }

      }//end if( isset( $this->toParse[$tabName] ) )

    }//end foreach( $this->ddlXml->foreignKeys->key as $key )


  }//end public function parseMapForeignKeys( )

  /**
   * @return void
   */
  public function parse()
  {


    $this->parseQuotes();
    $this->parseSequences();
    $this->parseSqlTables();
    $this->parseForeignKeys();


    foreach( $this->sequences as $modName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedSequences[$modName]))
        {
          $this->parsedSequences[$modName] = '-- SEQUENCES --'.NL.NL;
        }
        $this->parsedSequences[$modName] .= $tab;
      }
    }

    foreach( $this->sequencesInstall as $modName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedInstallSequences[$modName]))
        {
          $this->parsedInstallSequences[$modName] = '-- SEQUENCES --'.NL.NL;
        }
        $this->parsedInstallSequences[$modName] .= $tab;
      }
    }

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

    foreach( $this->tablesInstall as $modName => $tables )
    {
      foreach( $tables as $tab )
      {
        if(!isset($this->parsedInstallTables[$modName]))
        {
          $this->parsedInstallTables[$modName] = '-- TABLES --'.NL.NL;
        }
        $this->parsedInstallTables[$modName] .= $tab;
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

  }//end public function parse

  /**
   * @param string $outputFolder the folder where to save the ddl files
   */
  public function write( $outputFolder = null )
  {

    if($outputFolder)
    {
      $this->setOutputFolder($outputFolder);
    }

    if(Log::$levelDebug)
      Log::debug(__file__,__line__,'logging in folder: '.$this->outputFolder );

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

      // create a full dump
      $fullDump .= $parsed;

    }//end foreach( $this->parsedTables as $modName => $parsed )

    foreach( $this->parsedInstallTables as $modName => $parsed )
    {

      $path = $this->outputFolder.$modName.$this->subPath;

      if(!SFilesystem::createFolder($path))
      {
        Error::addError('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$modName.'_table_install.sql' , $parsed ))
      {
        Error::addError
        (
        'Failed to write '.$path.$modName.'_table_install.sql'
        );
      }
      else
      {
        Message::addMessage( 'Successfully wrote '.$path.$modName.'_table_install.sql');
      }
    }//end foreach( $this->parsedInstallTables as $modName => $parsed )

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


    foreach( $this->parsedSequences as $modName => $parsed )
    {
      $path = $this->outputFolder.$modName.$this->subPath;
      if(!SFilesystem::createFolder($path))
      {
        Error::addError('Failed to create folder: '.$path);
        return false;
      }

      if(!SFiles::write( $path.$modName.'_sequence_install.sql' , $parsed ))
      {
        Error::addError
        (
        'Failed to write '.$path.$modName.'_sequence_install.sql'
        );
      }
      else
      {
        Message::addMessage(  'Successfully wrote '.$path.$modName.'_sequence_install.sql');
      }
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


    $path = $this->outputFolder.'all/data/ddl/postgresql/';
    if(!SFilesystem::createFolder($path))
    {
      Error::addError('Failed to create folder: '.$path);
      return false;
    }

    // a insert dump for the entity mal
    if(!SFiles::write( $path.'EntityMap.sql' , $this->insertEntitys ))
    {
      Error::addError
      (
      'Failed to write '.$path.'EntityMap.sql'
      );
    }
    else
    {
      Message::addMessage('Successfully wrote '.$path.'EntityMap.sql');
    }

    $fullDump = 'CREATE SCHEMA webfrap AUTHORIZATION webfrapadmin;'
        .NL.$fullDump
        .NL.$this->insertEntitys
        .NL.SFiles::read(PATH_GW.'install/data/database/dump/data.sql');

    // a Full dump with all tables
    if(!SFiles::write( $path.'FullDump.sql' , $fullDump ))
    {
      Error::addError
      (
      'Failed to write '.$path.'FullDump.sql'
      );
    }
    else
    {
      Message::addMessage('Successfully wrote '.$path.'fullDump.sql');
    }


  }//end public function write()

} // end class LibParserDdlPostgresql

