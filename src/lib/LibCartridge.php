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
abstract class LibCartridge
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the builder class
   *
   * @var LibGenfBuild
   */
  protected $builder        = null;

  /**
   * the parser tree
   *
   * @var LibGenfTree
   */
  protected $tree           = null;

  /**
   * the parser tree
   *
   * @var LibGenfTreeRoot
   */
  protected $root           = null;

  /**
   * te activ node
   *
   * @var LibGenfTreeRoot
   */
  protected $node           = null;

  /**
   * the project
   * @var array
   */
  protected $project        = null;


  /**
   * the simplexml node for the cartridge from the project description
   * @var SimpleXmlElement $cartridge
   */
  protected $cartridge      = null;

  /**
   * collecting all used lang entries
   * @var LibCartridgeWbfI18n
   */
  protected $i18nPool       = null;

  /**
   * @var string
   */
  protected $classType      = null;

  /**
   * @var string
   */
  protected $nodeType       = null;

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
   * @var string
   */
  protected $pathOutput     = null;

  /**
   *
   * @var string
   */
  protected $ws       = '';

  /**
   *
   * @var int
   */
  protected $wsFactor = 2;

  /**
   *
   * @var int
   */
  protected $wsCount  = 1;

  /**
   *
   * @var LibGenfEnv
   */
  protected $env  = null;

  /**
   *
   * @var string
   */
  protected $className  = null;

////////////////////////////////////////////////////////////////////////////////
// Magic
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder the Filepath of the Metamodel to parse
   * @param SimpleXMLElement $xml
   */
  public function __construct( $builder, $cartridge )
  {

    $this->builder    = $builder;
    $this->tree       = $builder->getTree();
    $this->project    = $builder->getProject();
    $this->root       = $this->tree->getRootNode( $this->nodeType );

    // dirty hack hrhr to get a better naming
    $this->node       = $this->root;
    $this->cartridge  = $cartridge;
    $this->i18nPool   = LibCartridgeI18n::getInstance();

    $this->init();

  }//end public function __construct */

  /**
   * @return string
   */
  public function __toString()
  {
    return $this->parse();
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param  String $folder
   */
  public function setOutputFolder( $folder )
  {

    if( $folder[(strlen($folder)-1)] != '/' )
      $folder .= '/';

    $this->pathOutput =  $folder;

  }//end public function setOutputFolder */

  /**
   *
   * @return LibBdlCodeParser
   */
  public function getCodeCompiler( )
  {
    return $this->builder->bdlRegistry->getCodeCompiler();
  }//end public function getCodeCompiler */


  /**
   *
   * @return LibBdlFilterParser
   */
  public function getFilterParser( )
  {
    return $this->builder->bdlRegistry->getFilterParser();
  }//end public function getFilterParser */

  /**
   *
   * @return LibBdlAclCompiler
   */
  public function getAclCompiler( )
  {
    return $this->builder->bdlRegistry->getAclCompiler( );
  }//end public function getAclCompiler */


  /**
   * @param string $key
   * @param LibGenfEnv $env
   * @param string $methodRequired Name einer Methode die vorhanden sein muss
   *   Wird verwendet wenn der Generator ein bestimmtes Interface implementieren muss
   *  Um das Ganze abzukürzen wird einfach auf die Existenz einer Methode geprüft
   *  Das mit dem Interface kommt vielleicht noch bei bedarf
   *  
   * @return LibGenfGenerator
   */
  public function getGenerator( $key, $env = null, $methodRequired = null )
  {

    if(!$env)
      $env = $this->env;

    $generator = $this->builder->getGenerator( $key, $env );
    
    if( !$methodRequired )
      return $generator;
      
    if( method_exists( $generator, $methodRequired ) )
      $generator;
      
    return null;
    
  }//end public function getGenerator */

////////////////////////////////////////////////////////////////////////////////
// Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  //public abstract function parse();

  /**
   *
   */
  public function build()
  {
  }//end public function build */

  /**
   * Enter description here...
   *
   */
  public function write()
  {

    if(!$this->pathOutput)
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }

    // den Genf Code Wegschreiben
    foreach( $this->genfCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName     = $this->classType.SParserString::subToCamelCase($name);
        $classPath    = SParserString::getClassPath($fileName,false);
        $folderPath   = $folder.'genf/'.$mod.'/src/'.$classPath;
        $this->writeFile( $code, $folderPath, $fileName.'.php' );
      }
    }//end foreach

    // den Hand Code Wegschreiben
    foreach( $this->handCode as $mod => $elements )
    {
      foreach( $elements as $name => $code )
      {
        $fileName     = $this->classType.SParserString::subToCamelCase($name);
        $classPath    = SParserString::getClassPath($fileName,false);
        $folderPath   = $folder.'hand/'.$mod.'/src/'.$classPath;
        $this->writeFile( $code, $folderPath, $fileName.'.php' );
      }
    }//end foreach

  }//end public function write */

  /**
   *
   * Erstellen einer code datei
   *
   * @param string $code
   * @param string $folderPath
   * @param string $filename
   * @param string $phar
   * @param string $pharKey
   *
   */
  protected function writeFile( $code, $folderPath, $filename, $phar = null, $pharKey = null )
  {
    
    try 
    {
    
    $absolute = $folderPath[0]=='/'?true:false;

    if( !file_exists($folderPath) )
    {
      if( !SFilesystem::createFolder( $folderPath,true,$absolute ) )
      {
        
        $msg = I18n::s( 'Failed to create Temp Folder {@folder@}', 'wbf.msg',array('folder'=>$folderPath));
        
        Error::addError($msg);
        Message::addError($msg);
        return;
      }
    }

    if(!SFiles::write( $folderPath.'/'.$filename , $code ))
    {
      Error::addError( 'Failed to write '.$folderPath.'/'.$filename );
      Message::addWarning( 'Failed to write '.$folderPath.'/'.$filename );
    }
    else
    {
      
        /*
        $metaFile = $folderPath.'/'.$filename.'.meta';
        SFiles::write( $metaFile , json_encode($metaData) );
        */
        
      /* 
      if( $phar )
      {
        $metaData = array();
        //$metaData['md5']   = md5($code);
        //$metaData['sha1']  = sha1($code);
        $metaData['author']  = 'Legion <Legio formula simii>';
        $metaData['date']  = date("Y-m-d h:i:s");
        
        $jsonMeta = json_encode($metaData);
        

      
        $phar->addFromString( $pharKey , $code );
        $phar->addFromString( $pharKey.'.meta' , $jsonMeta );
      }
      */
     
      
      Log::debug( 'Wrote: '.$folderPath.'/'.$filename );
    }

    /**/
    if( $this->builder->protocol )
      $this->builder->protocol->logLine( 'Write file: '.$folderPath.'/'.$filename );
    
    }
    catch( Exception $e )
    {
      Log::error( $e->getMessage()  );
    }

  }//end public function writeFile */

  /**
   *
   * @param int $num
   * @return string
   */
  public function wsp( $num )
  {
    return str_pad('', ($num * $this->builder->indentation) );
  }//end public function wsp */

  /**
   * parse the head
   * @param LibGenfBuilder $project
   * @return  string
   */
  public function createCodeHead(  )
  {

    if( $projectHead = $this->builder->getHeader() )
      return '<?php '.NL.$projectHead.NL;
      
/*
 ____      ____  ________  ______   ________  _______          _       _______
|_  _|    |_  _||_   __  ||_   _ \ |_   __  ||_   __ \        / \     |_   __ \
  \ \  /\  / /    | |_ \_|  | |_) |  | |_ \_|  | |__) |      / _ \      | |__) |
   \ \/  \/ /     |  _| _   |  __\'.  |  _|     |  __ /      / ___ \     |  ___/
    \  /\  /     _| |__/ | _| |__) |_| |_     _| |  \ \_  _/ /   \ \_  _| |_
     \/  \/     |________||_______/|_____|   |____| |___||____| |____||_____|

                                       __.;:-+=;=_.
                                    ._=~ -...    -~:
                     .:;;;:.-=si_=s%+-..;===+||=;. -:
                  ..;::::::..<mQmQW>  :::.::;==+||.:;        ..:-..
               .:.:::::::::-_qWWQWe .=:::::::::::::::   ..:::-.  . -:_
             .:...:.:::;:;.:jQWWWE;.+===;;;;:;::::.=ugwmp;..:=====.  -
           .=-.-::::;=;=;-.wQWBWWE;:++==+========;.=WWWWk.:|||||ii>...
         .vma. ::;:=====.<mWmWBWWE;:|+||++|+|||+|=:)WWBWE;=liiillIv; :
       .=3mQQa,:=====+==wQWBWBWBWh>:+|||||||i||ii|;=$WWW#>=lvvvvIvv;.
      .--+3QWWc:;=|+|+;=3QWBWBWWWmi:|iiiiiilllllll>-3WmW#>:IvlIvvv>` .
     .=___<XQ2=<|++||||;-9WWBWWWWQc:|iilllvIvvvnvvsi|\\\'\\?Y1=:{IIIIi+- .
     ivIIiidWe;voi+|illi|.+9WWBWWWm>:<llvvvvnnnnnnn}~     - =++-
     +lIliidB>:+vXvvivIvli_."$WWWmWm;:<Ilvvnnnnonnv> .          .- .
      ~|i|IXG===inovillllil|=:"HW###h>:<lIvvnvnnvv>- .
        -==|1i==|vni||i|i|||||;:+Y1""\'i=|IIvvvv}+-  .
           ----:=|l=+|+|+||+=:+|-      - --++--. .-
                  .  -=||||ii:. .              - .
                       -+ilI+ .;..
                         ---.::....

********************************************************************************
 */

    $project = $this->builder->getProject();

    $head = '<?php
/*******************************************************************************
* Webfrap.net Legion
*
* @author      : '.$project->author.'
* @date        : @date@
* @copyright   : '.$project->copyright.'
* @project     : '.$project->projectName.'
* @projectUrl  : '.$project->projectUrl.'
* @licence     : '.$project->licence.'
*
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/
';

  return $head;


  }//end public function createCodeHead */

  /**
   * @param string $className
   */
  public function getHeadMessage( $className )
  {

      if( $this->builder->sandbox )
    {

      return <<<MESSAGE
 * DO NOT CHANGE THIS CLASS BY HAND
 * ALL CHANGES WILL BE DROPPED BY THE SYSTEM

MESSAGE;

    }
    else
    {

      return <<<MESSAGE
 * Read before change:
 * It's not recommended to change this file inside a Mod or App Project.
 * If you want to change it copy it to a custom project.

MESSAGE;

    }
    
    /*
    if( $this->builder->sandbox )
    {

      return <<<MESSAGE
 * This is the Genf Class, this means this class only contains generated code
 * If you want to extend this class write your coden in {$className}
 *
 * NEVER WRITE CODE IN THIS CLASS
 * THE CONTENT OF THIS CLASS IS NOT PERSISTENT AND CAN CHANGE OFTEN
 * ALL YOUR CHANGES WILL BE OVERWRITEN!!!
 * YOU HAVE BEEN WARNED!!!
MESSAGE;

    }
    else
    {

      return <<<MESSAGE
 * This Class was generated with a Cartridge based on the WebFrap GenF Framework
 * This is the final Version of this class.
 * It's not expected that somebody change the Code via Hand.
 *
 * You are allowed to change this code, but be warned, that you'll loose
 * all guarantees that where given for this project, for ALL Modules that
 * somehow interact with this file.
 * To regain guarantees for the code please contact the developer for a code-review
 * and a change of the security-hash.
 *
 * The developer of this Code has checksums to proof the integrity of this file.
 * This is a security feature, to check if there where any malicious damages
 * from attackers against your installation.
 *
MESSAGE;

    }
    */

  }//end public function getHeadMessage */

  /**
   * parse the footer
   * @return string
   */
  public function createCodeFooter()
  {
    return NL;
  }//end public function createCodeFooter */

  /**
   * parse a code seperator banner with text
   *
   * @param string $content
   * @return string
   */
  public function parseCodeSeperator( $content )
  {

    $code='
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
    ';

    return $code;

  }//end public function parseCodeSeperator */

  /**
   * parse a code seperator banner with text
   *
   * @param string $content
   * @return string
   */
  public function createCodeSeperator( $content )
  {

    $code = '
////////////////////////////////////////////////////////////////////////////////
// '.$content.'
////////////////////////////////////////////////////////////////////////////////
    ';

    return $code;

  }//end public function createCodeSeperator */

  /**
   * @return TCodeStack
   */
  protected function newCodeStack()
  {
    return new TCodeStack();
  }//end protected function newCodeStack */

  /**
   * @overwrite
   * @return void
   */
  protected function init()
  {
  }//end protected function init */


  /**
   * @return string
   */
  public function line( $code )
  {
    return $this->ws.$code.NL;
  }//end public function line */


  /**
   * @return sline
   */
  public function sLine( $code )
  {
    return $this->ws.$code;
  }//end public function line */

  /**
   * @return string
   */
  public function cLine( $code )
  {
    return $code.NL;
  }//end public function cline */

  /**
   * @return string
   */
  public function nl(  )
  {
    return NL;
  }//end public function cline */

  /**
   * @return string
   */
  public function string( $code )
  {
    return '"'.$code.'"';
  }//end public function string */

  /**
   *
   * @param int $count
   */
  public function setWsPadding( $count )
  {
    $this->wsCount = $count;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function setWsPadding */

  /**
   *
   */
  public function wsInc()
  {
    ++$this->wsCount;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function wsInc */

  /**
   *
   */
  public function wsDec()
  {
    --$this->wsCount;
    $this->ws = str_pad(' ',( $this->wsFactor * $this->wsCount ));
  }//end public function wsDec */

/*//////////////////////////////////////////////////////////////////////////////
// concept code
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param string $key
   */
  public function getConceptCode( $management , $key )
  {

    if( !$concepts = $management->getConceptKeys() )
    {
      return null;
    }

    $code = array();

    $conceptGens = array();

    foreach( $concepts as $concept )
    {
      if( $conceptGen = $this->builder->getGenerator( 'Concept'.SParserString::subToCamelCase($concept) ) )
      {
        $conceptGens[] = $conceptGen;
      }
    }

    foreach( $conceptGens as $conceptGen )
    {

      if( !$parsed = $conceptGen->$key( $management ) )
      {
        continue;
      }

      $priority = $conceptGen->priority($key);

      if(!isset($code[$priority]))
        $code[$priority] = array();

      $code[$priority][] = $parsed;

    }//end foreach

    return SParserString::prioArrayToString($code);

  }//end public function getConceptCode */

  /**
   * @param $className
   * @param $full
   */
  public function getClassPath( $className , $full = false )
  {
    return SParserString::getClassPath( $className , $full );
  }//end public function getClassPath */

  /**
   * @param string $message
   */
  public function reportError( $message )
  {
    
    $this->builder->error( "Generator ".get_class($this)." ".$message." ".$this->builder->dumpEnv() );
    
  }//end public function reportError */
  
} // end abstract class LibCartridge
