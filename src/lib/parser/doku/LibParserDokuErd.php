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
class LibParserDokuErd
  extends LibParserWbfcodeAbstract
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var String
   */
  protected $basePath = 'data/code_gen_cache/doku/erd/';

  /**
   * messages
   *
   * @var array
   */
  protected $messages = array();

  /**
   * the name of the mod in CamelCase
   *
   * @var string
   */
  protected $modName = null;

  /**
   * the name of the module in lowercase
   *
   * @var string
   */
  protected $lowModName = null;

  /**
   * the full modul name in camel Case
   *
   * @var string
   */
  protected $entityName = null;

  /**
   * the full modul name in lowercase
   *
   * @var string
   */
  protected $lowEntityName = null;

  /**
   * name of the table
   *
   * @var string
   */
  protected $tableName = null;

  /**
   * the name of the mex in CamelCase
   *
   * @var string
   */
  protected $mexName = null;

  /**
   * the name of the mex in lowercase
   *
   * @var string
   */
  protected $lowMexName = null;

  /**
   * the activ Table
   *
   * @var SimpleXmlElement
   */
  protected $activTable = null;

  /**
   * Enter description here...
   *
   * @var null
   */
  protected $indexObject = null;

  /**
   * all available modules
   *
   * @var array
   */
  protected $modules = array();

  /**
   * all available modules
   *
   * @var array
   */
  protected $modulesMap = array();

  /**
   * the index.html file
   *
   * @var string
   */
  protected $indexHtml = '';

  /**
   * the index.html file
   *
   * @var string
   */
  protected $sqlParser = array();

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @return void
   */
  public function parseNames( $name )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($name));

    $this->tableName  = $name;
    $this->entityName = SParserString::subToCamelCase($name);
    $this->lowEntityName = strtolower($this->entityName);
    $this->modName    = SParserString::getModname($name);
    $this->lowModName = strtolower($this->modName);
    $this->mexName    = SParserString::subToCamelCase(SParserString::removeFirstSub($name)) ;
    $this->lowMexName = strtolower($this->mexName);

  }//end public function parseNames( $name )

  /**
   * Enter description here...
   *
   */
  public function loadModules( $xml )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($xml) );

    $tmpMap = array();

    if( isset( $xml->tables ))
    {
      $classes = $xml->tables;

      foreach( $classes->table as $table )
      {
        if( isset( $table->mal->inDoku ) or !isset($table->mal->meta) )
        {

          $tableName = (string)$table['name'];

          // Get the Modul info
          $parts = explode( '_' , $tableName);

          if( !isset($tmpMap[$tableName]) )
          {
            $tmpMap[$parts[0]] = true;
            $this->modules[$parts[0]] = true;
          }

          $this->modulesMap[$parts[0]][] = $tableName;

        }
      }//end foreach

    }//end if

  }//end public function loadModules( $xml )

  /**
   * Enter description here...
   *
   * @param LibParserDdlAbstract $parser
   */
  public function addSqlParser( $type ,  $parser )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($type ,  $parser));

    $this->sqlParser[$type] = $parser;

  }//end public function addSqlParser( $parser )

////////////////////////////////////////////////////////////////////////////////
// Parser Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the sql parser
   * @param string $table
   * @return string
   */
  protected function parseSql( $table )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($table) );

    $code = '<h2>Sql</h2>';

    foreach( $this->sqlParser as $dbName => $parser )
    {
      $code .= '<h3>DBMS '.$dbName.'</h3>'.NL;
      $code .= '<pre class="boxExampleCode">';
      $code .= $parser->getTable($table);

      if( $keys = $parser->getFk($table) )
      {
        foreach($keys as $key)
        {
          $code .= $key['ddl'];
        }
      }

      $code .= '</pre>'.NL;
    }//end foreach( $this->sqlParser as $dbName => $parser )

    return $code;

  }//end public function parseSql( $table )

  /**
   * parse the reference
   * @param string $table
   * @return string
   */
  protected function parseReferences( $table )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($table) );

    $code = '<h2>References</h2>';

    // we only need one of the parsers this data is redundant
    reset($this->sqlParser);
    if(!$parser = current( $this->sqlParser ))
    {
      return $code;
    }

    if( $keys = $parser->getFk($table) )
    {
      $code .= '<table class="dataTable" >'.NL;
      $code .= '<thead class="head">'.NL;
      $code .= '<tr>'.NL;
      $code .= '<th>Reference Table</th>'.NL;
      $code .= '<th>Reference Key</th>'.NL;
      $code .= '</tr>'.NL;
      $code .= '</thead>'.NL;
      $code .= '<tbody>'.NL;
      foreach($keys as $key)
      {
        $code .= $this->parseReference( $key['xml'] );
      }
      $code .= '</tbody>'.NL;
      $code .= '</table>'.NL;
    }

    return $code;

  }//end public function parseReferences( $table )

  /**
   * parse the reference to another table
   * @return string
   */
  protected function parseReference( $key )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($key) );

    $code = '<tr>'.NL;
    $code .= '<td><a href="table_'.$key['targetTable'].'.html" target="main" >'.$key['targetTable'].'</td>'.NL;
    $code .= '<td>'.$key['srcCol'].'</td>'.NL;
    $code .= '</tr>'.NL;

    return $code;

  }//end public function parseReferences( $table )

  /**
   * parse the entity
   * @return string
   */
  protected function parseEntity( )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__ );

    $comment = isset( $this->activTable->mal->comment )
      ?'<p class="boxContent">'.trim($this->activTable->mal->comment).'</p>':'';

    $table = (string)$this->activTable['name'];

    $code = $this->parseHead( 'Doku for table: '.$table );

    $code .= <<<CODE
    <body >
    <div class="content" >
<h2>Tabelle: {$table}</h2>

{$comment}

<table class="dataTable">

  <thead class="head">
    <tr>
      <th title="Col Name">Name</th>
      <th title="Col Title">Type</th>
      <th title="Validator Name used in System">Validator</th>
      <th title="Size of Col">Size</th>
      <th title="Min size for validation">MinSize</th>
      <th title="Max size for validation">MaxSize</th>
      <th title="May the value be null">NotNull</th>
      <th title="the default value for the table">Default</th>
      <th title="name of the sequence">Sequence</th>
      <th title="is this field unique">Unique</th>
      <th title="is this field a primary key">Pk</th>
      <th title="comment for this field" >Comment</th>
    </tr>
  </thead>

  <tbody>

CODE;

    $code .= $this->parseAttributes();

    $code .= <<<CODE
  </tbody>
</table>

CODE;

    $code .= $this->parseReferences($table);
    $code .= $this->parseSql($table);

    $code .= '</div>'.NL;

    $code .= $this->parseFooter();
    $code .= $this->parseFoot();

    return $code;


  }//end protected function parseEntity()

  /**
   * parse the attributes for the table
   *
   * @return string
   */
  protected function parseAttributes()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__ );

    $code = '';

    foreach( $this->activTable->primaryKey->key as $pk )
    {
      $pks[(string)$pk] = true;
    }

    foreach( $this->activTable->row as  $row )
    {

      if( substr( $row['name'] , 0 , 2 ) == 'm_' )
      {
        $class = ' class="meta" ';
        $title = ' title="this is a meta attribute" ';
      }
      else
      {
        $class = '';
        $title = ' title="this is a standard attribute" ';
      }

      $checkedNull   = $row['notNull'] == 'true'? ' checked="checked" ':'';
      $checkedUnique = $row['unique'] == 'true'? ' checked="checked" ':'';
      $checkedPk = isset($pks[(string)$row['name']])? ' checked="checked" ':'';

      $code .= '  <tr '.$class.' '.$title.' >'.NL;
      $code .= '    <td>'.$row['name'].'</td>'.NL;
      $code .= '    <td>'.$row['type'].'</td>'.NL;
      $code .= '    <td>'.$row['validator'].'</td>'.NL;
      $code .= '    <td>'.$row['size'].'</td>'.NL;
      $code .= '    <td>'.$row['minSize'].'</td>'.NL;
      $code .= '    <td>'.$row['maxSize'].'</td>'.NL;
      $code .= '    <td><input type="checkbox" disabled="disabled" '.$checkedNull.' /></td>'.NL;
      $code .= '    <td>'.$row['default'].'</td>'.NL;
      $code .= '    <td>'.$row['sequence'].'</td>'.NL;
      $code .= '    <td><input type="checkbox" disabled="disabled" '.$checkedUnique.' /></td>'.NL;
      $code .= '    <td><input type="checkbox" disabled="disabled" '.$checkedPk.' /></td>'.NL;
      $code .= '    <td>'.$row['comment'].'</td>'.NL;
      $code .= '  </tr>'.NL;
    }

    return $code;

  }//end public function parseAttributes()

  /**
   * @param string
   * @return string
   */
  public function parseHead( $title = 'Webfrap Erd Doku' )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($title));

    $head = <<<CODE
<?xml version="1.0" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>{$title}</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-Script-Type" content="text/javascript" />
<meta http-equiv="content-Style-Type" content="text/css" />
<meta http-equiv="content-language" content="de" />
<link href="doc.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="jquery.js"></script>
</head>

CODE;

  return $head;


  }//end public function parseHead()

  /**
   * @return string
   */
  public function parseFoot()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = '</body>'.NL.'</html>'.NL;

    return $code;

  }//end public function parseFood()

  /**
   * @return string
   */
  protected function parseFooter()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = '<div class="footer" ><a href="http://webfrap.net" target="__new" >Generated by <b>WEB</b><span>FRAP</span>© Erd Documentor</a></div>'.NL;

    return $code;

  }//end public function parseFood()

  /**
   * parse the index file
   *
   */
  protected function parseIndexHtml()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = <<<CODE
<?xml version="1.0" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Doku for the ERD: {$this->xmlFile}</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-Script-Type" content="text/javascript" />
<meta http-equiv="content-Style-Type" content="text/css" />
<meta http-equiv="content-language" content="de" />
</head>

<frameset cols="200px,*">
  <frame src="menu.html" name="menu">
  <frameset rows="120px,*">
  <frame src="top.html" name="top">
  <frame src="start.html" name="main">
  </frameset>
</frameset>

</html>
CODE;

    $this->parsedElements['index'] = $code;

  }//end public function parseIndexHtml()

  /**
   * parse the menu
   *
   */
  protected function parseMenu()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = $this->parseHead( 'Menu' );

    $code .= <<<CODE

    <body style="background-color:#B4C0C0;" >

    <div class="content" >
  <h2><a href="start.html" target="main" >Overview</a></h2>

  <ul class="treeMenu" >

CODE;

    //ksort($this->modules);

    foreach( $this->modules as $modName => $tmp )
    {

      $code .= <<<CODE
    <li>
    <h4><a href="modul_{$modName}.html" target="main" >{$modName}</a></h4>

    <ul>

CODE;

    $code .= NL;

      //asort($this->modulesMap[$modName]);

      foreach( $this->modulesMap[$modName] as $table )
      {
        $code .= '<li><a href="table_'.$table.'.html" target="main" >'.$table.'</a></li>'.NL;
      }

      $code .= '</ul></li>'.NL;

    }//end foreach( $this->modules as $modName => $tmp )

    $code .= '</ul>'.NL.'</div>'.NL;

    $code .= $this->parseFooter();
    $code .= $this->parseFoot();

    $this->parsedElements['menu'] = $code;

  }//end public function parseMenu()

  /**
   * Enter description here...
   *
   */
  protected function parseTop()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = $this->parseHead( "Menu" );

    $code .= <<<CODE

    <body style="background-color:#B4C0C0;" >
    <div class="content" >

  <h1><a href="http://webfrap.net" target="__top" ><b>WEB</b><span>FRAP</span></a> <a href="start.html" target="main">ERD Documentor</a></h1>
  <h3><a href="start.html" target="main">Modules from Model</a></h3>

CODE;

    $code .= '<ul class="topMenu" >'.NL;
    foreach( $this->modules as $modName => $tmp )
    {
      $code .= '<li><a href="./modul_'.$modName.'.html" target="main">'.$modName.'</a></li>'.NL;
    }
    $code .= '</ul>'.NL;

    $code .= '</div>'.NL;

    $code .= $this->parseFoot();

    $this->parsedElements['top'] = $code;

  }//end public function parseTop()

  /**
   * Enter description here...
   *
   */
  protected function parseStart()
  {

    $code = $this->parseHead( 'Start' );

    $code .= <<<CODE

    <body>
    <div class="content"  >

  <h3>Modul / Table Overview</h3>

  <table class="dataTable" >
  <thead class="head">
    <tr>
      <th>Modul</th>
      <th>Table</th>
      <th>Number Tables</th>
    </tr>
  </thead>
  <tbody>

CODE;

    foreach( $this->modules as $modName => $tmp )
    {

      $anzTables = count($this->modulesMap[$modName]);

      $code .= <<<CODE
    <tr class="head" >
    <td><a href="modul_{$modName}.html" target="main" >{$modName}</a></td>
    <td></td>
    <td>{$anzTables}</td>
    </tr>

CODE;


      foreach( $this->modulesMap[$modName] as $table )
      {
        $code .= <<<CODE
    <tr>
    <td></td>
    <td><a href="table_{$table}.html" target="main" >{$table}</a></td>
    <td></td>
    </tr>

CODE;

      }//end foreach( $this->modulesMap[$modName] as $table )

    }//end foreach( $this->modules as $modName => $tmp )

    $code .='</tbody>'.NL;
    $code .='</table>'.NL;
    $code .='</div>'.NL;

    $code .= $this->parseFooter();
    $code .= $this->parseFoot();

    $this->parsedElements['start'] = $code;

  }//end public function parseStart()

  /**
   * parse the modules
   *
   */
  protected function parseModules()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $modelName = SFiles::getRawFilename($this->xmlModel['name']);
    $modeller = strtolower($this->xmlModel['modeller']);
    $modPics = array();

    $picFolder = PATH_GW.'data/model/'.$modeller.'/'.$modelName;

    if( file_exists($picFolder) and is_dir($picFolder) )
    {
      $folder = new LibFilesystemFolder($picFolder);
      foreach( $folder->getFiles() as $file)
      {
        $fName = $file->getName();

        if( $pos = strpos($fName,'_'))
        {
          $tmp = explode('_',$fName);
          $modName = $tmp[0];
        }
        else
        {
          $modName = substr($fName,0,strrpos($fName,'.'));
        }

        if(Log::$levelTrace)
          Log::logTrace(__file__,__line__,'got file: '.$fName.' for module: '.$modName );

        $modPics[$modName][] = $file;
      }
    }
    else
    {
      Log::warn(__file__,__line__,'found no picfolder for:'.$picFolder );
    }

    ksort($this->modules);

    foreach( $this->modules as $modName => $tmp )
    {
      $code = $this->parseHead( "Start" );

      $code .= <<<CODE

    <body>
    <div class="content" >
    <h3>Modul: {$modName}</h3>

    <ul>

CODE;

      asort($this->modulesMap[$modName]);

      foreach( $this->modulesMap[$modName] as $table )
      {
        $code .= '<li><a href="table_'.$table.'.html" target="main" >'.$table.'</a></li>'.NL;
      }

      $code .= '</ul>'.NL;
      $code .= '</div>'.NL;

      if( isset($modPics[$modName]) )
      {
        $code .= $this->parseModelPics($modPics[$modName]);
      }

      $code .= $this->parseFooter();
      $code .= $this->parseFoot();

      $this->parsedElements['modul_'.$modName] = $code;
    }//end foreach( $this->modules as $modName => $tmp )


  }//end public function parseModules()


  /**
   * add the module pics to the modul pages
   * @param array<LibFilesystemFile>
   * @return string
   */
  protected function parseModelPics( $pics )
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__,array($pics) );


    $code = '<h3>Er Diagramm</h3>';

    foreach( $pics as $pic )
    {
      $pic->copy( $this->outputFolder.'images/'.$pic->getName()  );
      $code .= '<div style="margin-top:30px;" >'.NL;
      $code .= '<h4 >'.$pic->getName().'</h4>'.NL;
      $code .= '<a href="./images/'.$pic->getName().'" title="click for open" target="main" ><img src="./images/'.$pic->getName().'" alt="Er Diagramm '.$pic->getName().'" style="width:800px" /></a>'.NL;
      $code .= '</div>'.NL;
    }

    return $code;

  }//end protected function parseModelPics( $pics )


  /**
   * Parse the licence
   *
   * @return string
   */
  protected function parseLicence()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    return <<<CODE
/*******************************************************************************

 ____      ____  ________  ______   ________  _______          _       _______
|_  _|    |_  _||_   __  ||_   _ \ |_   __  ||_   __ \        / \     |_   __ \
  \ \  /\  / /    | |_ \_|  | |_) |  | |_ \_|  | |__) |      / _ \      | |__) |
   \ \/  \/ /     |  _| _   |  __'.  |  _|     |  __ /      / ___ \     |  ___/
    \  /\  /     _| |__/ | _| |__) |_| |_     _| |  \ \_  _/ /   \ \_  _| |_
     \/  \/     |________||_______/|_____|   |____| |___||____| |____||_____|



Autor     : {$this->author}
Copyright : {$this->copyright}
Licence   : {$this->licence}
Changes:

*******************************************************************************/
CODE;


  }//end public function parseLicence()

  /**
   * parse all tables
   * @return void
   */
  protected function parseAll()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    foreach( $this->xmlModel->tables->table as $table )
    {
      $name = (string)$table['name'];
      $this->indexTables[$name] = $table;
    }

    foreach( $this->xmlModel->tables->table as $table )
    {

      if( isset( $table->mal->inDoku ) or !isset($table->mal->meta) )
      {

        $this->parseNames( trim((string)$table['name']) );
        $this->malParser->setActivTable($table);
        $this->activTable = $table;

        $name = (string)$table['name'];
        $this->parsedElements['table_'.$name] = $this->parseEntity();
      }

    }//end foreach( $this->xmlModel->tables->table as $table )

    $this->parseModules();
    $this->parseMenu();
    $this->parseIndexHtml();

    $this->parseTop();
    $this->parseStart();

  }//end protected function parseAll()

  /**
   * parse by map
   * @return void
   *
   */
  protected function parseByMap()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    foreach( $this->xmlModel->tables->table as $tab )
    {
      $name = (string)$tab['name'];
      $this->indexTables[$name] = $tab;
    }

    foreach( $this->xmlModel->tables->table as $tab )
    {
      $name = (string)$tab['name'];

      if( isset( $this->mapToParse[$name] ))
      {
        $this->activTable = $tab;
        $this->parsedElements[$name] =  $this->parseEntity($tab);
      }
    }

  }//end protected function parseByMap()

////////////////////////////////////////////////////////////////////////////////
// Implement Abstract Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the parser method
   * @return void
   */
  public function parse()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    if( $this->parseAll )
    {
      $this->parseAll();
    }
    else
    {
      $this->parseByMap();
    }

  }//end public function parse()

  /**
   * highlight the code and return it
   * @return string
   */
  public function highlight()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);

    $code = '';

    foreach( $this->parsedElements as $name => $data )
    {
      if( Log::$levelTrace )
        Log::logTrace(  __file__, __line__,
          'appending '.$this->classType.'file '.$name );

      $code .= '<h4>Dokumentation for Table '.$name.'</h4>';
      $code .= $data;

    }

    return $code;

  }//end  public function highlight()

  /**
   * the write method
   *
   */
  public function write()
  {
    if(Log::$levelDebug)
      Log::start(__file__,__line__,__method__);


    foreach( $this->parsedElements as $fileName => $code )
    {

      $folderPath = $this->outputFolder;
      $path = $this->outputFolder.$fileName.'.html';

      if( !file_exists($folderPath) )
      {
        if(!SFilesystem::createFolder($folderPath))
        {
          Error::addError
          (
          I18n::s('wbf.msg.failedToCreateFolder',array($folderPath))
          );
          return;
        }
      }

      if(!SFiles::write( $path , $code ))
      {
        Error::addError
        (
        'Failed to write '.$path
        );
        Message::addError( $fileName.'.html konnte nicht geschrieben werden');
      }
      else
      {
        Message::addMessage($fileName.'.html wurde erfolgreich erstellt.');
      }

    }//end foreach

    // copy doc an jquery
    $css    = new LibFilesystemFile(PATH_GW.'templates/themes/webfrap/doc/doc.css');
    $css->copy($folderPath.'doc.css');

    $jquery = new LibFilesystemFile(PATH_GW.'templates/themes/webfrap/doc/jquery.js');
    $jquery->copy($folderPath.'jquery.js');

  }//end public function write()

} // end abstract class LibParserDokuErd

