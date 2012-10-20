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
 * @subpackage GenF
 */
class GenfDb_Controller
  extends Controller
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * ignore accesslist everything is free accessable
   * @var boolean
   */
  protected $fullAccess = true;

  /**
   *
   * @var array
   */
  protected $callAble = array
  (
  'listconnection',
  'fullexport',
  'listdbtables',
  'dumptable',
  'dumptables',
  );

////////////////////////////////////////////////////////////////////////////////
//Logic: Meta Model
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  public function listConnection()
  {

    $view = $this->getView();

    $view->setTemplate( 'genf/db/TableConnection'   );
    $table = $view->newItem( 'tableConnection' , 'TableGenfDbconnection' );

  }//end public function listConnection */

  /**
   * @return void
   */
  public function fullExport( )
  {


    $db = $this->getDataConnection();
    $infoDb = $this->getDbInfomrmationConnection( $db );

    $type = $db->getParserType();
    $className = 'LibDbAdmin'.ucfirst($type);

    $dbAdmin = new $className( $infoDb );

    $tables = $dbAdmin->getDbTables( $conConf['dbname'] );


    $serializer = LibSerializerDbtoxml::getInstance();

    if( !file_exists( $path ) )
      SFilesystem::createFolder( $path );

    foreach( $tables as $table  )
    {
      $sql = 'SELECT * FROM '.$table['name'];

      $data = $db->select($sql)->getAll();

      try
      {
        $meta = $db->getQuotesData( $table['name'] );
      }
      catch( Exception $e )
      {
        $meta = $dbAdmin->getTableQuotes( $conConf['dbname'] , $table['name']  );
      }

      $xml = $serializer->serializeDb( $table['name'] , $data , $meta , strtolower( $table['encoding'] ) );

      if( SFiles::write( $path.$table['name'].'.xml',  $xml ) )
      {
        Message::addMessage( 'Successfully exported: '.$path.$table['name'].'.xml' );
      }
      else
      {
        Message::addError( 'Failed to exported: '.$path.$table['name'].'.xml' );
      }

    }

    Debug::console( 'number calls: ',$serializer->counter  );

  } // end public function fullExport */

  /**
   *
   *
   */
  public function listDbTables()
  {

    $data         = array();
    $connections  = array();
    $path         = null;
    $conConf      = array();
    $conKey       = null;

    $db = $this->getDataConnection( &$data, &$connections, &$path, &$conConf, &$conKey   );


    $view = $this->view->newWindow( 'DbTables' );
    $view->editAble = false;

    $view->setTemplate( 'genf/db/DbTables'   );
    $table = $view->newItem( 'dbTables' , 'TableGenfDbTables' );
    $table->con    = $conKey;

    $view->addVar( 'dbKey', $conKey );


    $infoDb = $this->getDbInfomrmationConnection( $db );

    $admin = new LibDbAdminPostgresql( $infoDb );

    $data = $admin->getDbTables( $db->getDatabaseName() , $db->getSchemaName() );

    $table->addData( $data );

  }//end public function listDbTables*/

  /**
   */
  public function dumpTable( )
  {

    $data         = array();
    $connections  = array();
    $path         = null;
    $conConf      = array();
    $conKey       = null;

    $db = $this->getDataConnection( &$data , &$connections , &$path, &$conConf, &$conKey  );

    $infoDb = $this->getDbInfomrmationConnection( $db );

    $type       = $db->getParserType();
    $className  = 'LibDbAdmin'.ucfirst($type);

    $dbAdmin = new $className( $infoDb );

    if(!$tableName = $this->request->get( 'table', 'cname' ))
    {
      Message::addError( 'Invalider Request' );
      return;
    }

    $serializer = LibSerializerDbtoxml::getInstance();

    if( !file_exists( $path ) )
      SFilesystem::createFolder( $path );

    $sql = 'SELECT * FROM '.$tableName;

    $data = $db->select($sql)->getAll();

    try
    {
      $meta = $db->getQuotesData( $tableName );
    }
    catch( Exception $e )
    {
      $meta = $dbAdmin->getTableQuotes( $conConf['dbname'] ,$tableName  );
    }

    $encoding = $dbAdmin->getTableEncoding( $conConf['dbname'] ,$tableName  );

    $xml = $serializer->serializeDb( $tableName, $data, $meta , strtolower( $table['encoding'] ) );

    if( SFiles::write( $path.$tableName.'.xml',  $xml ) )
    {
      Message::addMessage( 'Successfully exported: '.$path.$tableName.'.xml' );
    }
    else
    {
      Message::addError( 'Failed to exported: '.$path.$tableName.'.xml' );
    }


  }//end public function dumpTable */

  /**
   */
  public function dumpTables( )
  {

    $data         = array();
    $connections  = array();
    $path         = null;
    $conConf      = array();
    $conKey       = null;

    $db = $this->getDataConnection( &$data , &$connections , &$path, &$conConf, &$conKey  );

    $infoDb = $this->getDbInfomrmationConnection( $db );

    $type       = $db->getParserType();
    $className  = 'LibDbAdmin'.ucfirst($type);

    $dbAdmin = new $className( $infoDb );


    $serializer = LibSerializerDbtoxml::getInstance();

    if( !file_exists( $path ) )
      SFilesystem::createFolder( $path );

    $tables = array_keys( $this->request->post( 'tables' , 'cname' ) ) ;

    foreach( $tables as $tableName )
    {
      $sql = 'SELECT * FROM '.$tableName;

      $data = $db->select($sql)->getAll();

      try
      {
        $meta = $db->getQuotesData( $tableName );
      }
      catch( Exception $e )
      {
        $meta = $dbAdmin->getTableQuotes( $conConf['dbname'] ,$tableName  );
      }

      $encoding = $dbAdmin->getTableEncoding( $conConf['dbname'] ,$tableName  );

      $xml = $serializer->serializeDb( $tableName, $data, $meta , strtolower( $table['encoding'] ) );

      if( SFiles::write( $path.$tableName.'.xml',  $xml ) )
      {
        Message::addMessage( 'Successfully exported: '.$path.$tableName.'.xml' );
      }
      else
      {
        Message::addError( 'Failed to exported: '.$path.$tableName.'.xml' );
      }
    }


  }//end public function dumpTable */


  /**
   * @param LibDbConnection
   *
   */
  protected function getDbInfomrmationConnection( $db  )
  {

    $type = $db->getParserType();

    switch( $type )
    {
      case 'mysql':
      {
        // information schema für mysql laden
        return Db::connectDb( 'mysql_info' );
        break;
      }
      case 'postgresql':
      {
        return $db;
        break;
      }
      default:
      {
        return $db;
      }

    }//end switch

  }//end protected function getDbInfomrmationConnection */

  /**
   * @param LibDbConnection
   *
   */
  protected function getDataConnection( &$data, &$connections, &$path, &$conConf, &$dbName, $import = false  )
  {

      //AJAX Only Method
    if(!$dbName = $this->request->get( 'objid' , 'cname' ) )
    {
      Message::addError('Invalid Request');
      return;
    }

    // connection Map einbinden
    include PATH_GW.'conf/map/dbConnection/connectionMap.php';

    if( !isset($data[$dbName]) )
    {
      Message::addError('Invalid Request');
      return;
    }

    $path = $data[$dbName][2];

    if( isset( $connections[$dbName] ) )
    {
      $conConf = $connections[$dbName];
    }
    elseif ( !$import && isset( $connections[$dbName.'_export'] )  )
    {
      $conConf = $connections[$dbName.'_export'];
    }
    elseif ( $import && isset( $connections[$dbName.'_import'] )  )
    {
      $conConf = $connections[$dbName.'_import'];
    }
    else
    {
      Message::addError('Invalide Konfiguration');
      return;
    }

    try
    {
      return Db::connectDb( 'hand_'.$dbName, $conConf );
    }
    catch( LibDb_Exception $e )
    {
      Message::addError('Failed to create the export connection');
      return null;
    }

  }//end protected function getDataConnection */


} // end class GenfDb_Controller

