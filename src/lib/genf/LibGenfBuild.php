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

if( !defined('MARK_NEW_CODE') )
  define('MARK_NEW_CODE',false);

/**
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfBuild
{
////////////////////////////////////////////////////////////////////////////////
// Static Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * default instance
   * @var LibGenfBuild
   */
  protected static $instance = null;

  /**
   * Das user Objekt
   * @var User
   */
  protected $user = null;
  
  /**
   * Das user Objekt
   * @var LibDbConnection
   */
  protected $db = null;
  
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * the author from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $author        = null;

  /**
   * the copyright from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $copyright     = null;

  /**
   * the licence from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $licence       = null;

  /**
   * Definiert auf welche Zielgruppe das Tool compiliert werden soll
   * 
   * startup
   * kmu
   * department
   * corp
   * 
   * @var string
   */
  public $targetGroup       = null;
  
  /**
   * Soll Mockup Code Mitgeneriert werden?
   * @var boolean
   */
  public $mockup = false;
  
  /**
   * the version from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $version       = null;

  /**
   * the revision from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $revision      = null;
  
  /**
   * @var LibGenfArchitecture
   */
  public $archNode     = null;

  /**
   * the architecture name from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $arch          = null;

  /**
   * the version of the architecture from the BDL Project description
   * this value is taken from the project description
   * @var string
   */
  public $archVersion   = null;

  /**
   * the name of the model language
   * this value is taken from the project description
   * @var string
   */
  public $model         = null;

  /**
   * the version of the model language
   * this value is taken from the project description
   * @var string
   */
  public $modelVersion  = null;

  /**
   * the key to identify the actual project.
   * this key is also used in the project map to get the path of the actual
   * project description
   * @var string
   */
  public $projectKey    = null;

  /**
   * @var string
   */
  public $appKey    = null;

  /**
   * the human readbable name of the project
   * this value is taken from the project description
   * @var string
   */
  public $projectName   = null;

  /**
   * @var string
   */
  public $projectTitle  = null;

  /**
   * @var string
   */
  public $projectUrl    = null;

  /**
   *
   * @var string
   */
  public $projectPath   = null;

  /**
   * Wo soll die Includeliste gespeichert werden die nötig ist im alle Module
   * in die deployt wurde einzubinden
   * @var string
   */
  public $pathIncludeList   = null;
  
  /**
   * where should the source be safed
   * @var string
   */
  public $pathOutput    = null;

  /**
   * should there be created sandboxcode and plain files for handcode
   * or single codefiles with the generated code
   *
   * @var string
   */
  public $sandbox    = true;

  /**
   *
   * @var list with all languages
   */
  public $languages     = array();

  /**
   *
   * @var string
   */
  public $langDefault   = 'en';

  /**
   *
   * @var string
   */
  public $langCode      = 'en';

  /**
   * Einrückung des Codes
   * @var int
   */
  public $indentation   = 2;

  /**
   * the name for the default pk used in the projec, is by default rowid as in
   * webfrap
   * @var string
   */
  public $rowidKey      = 'rowid';

  /**
   * @var string
   */
  public $i18n          = null;


  /**
   * @var LibResponse
   */
  public $response      = null;


  /**
   * the actual activ repository. is used for imports to set a systemwide
   * variable to check from where the actual bdl file was imported
   *
   * use this variable only in createTree from LibGenfTree!
   *
   * @var string
   */
  public $activRepo      = null;
  
  /**
   * Der aktuell aktive node
   * @var LibGenfTreeNode
   */
  public $activNode      = null;
  
  /**
   * Der aktuell aktive node
   * @var LibGenfTreeNode
   */
  public $activeRepo      = null;
  
////////////////////////////////////////////////////////////////////////////////
// public objects
////////////////////////////////////////////////////////////////////////////////

  /**
   * interpreter object
   * @var LibGenfInterpreter
   */
  public $interpreter       = null;

  /**
   * specific name parser object
   * @var LibGenfNameParser
   */
  public $nameParser        = null;

  /**
   * @var LibBdl
   */
  public $bdlRegistry       = null;

  /**
   * @var LibGenfTree
   */
  public $tree              = null;

  /**
   * index for all loaded cartridges
   * @var array
   */
  public $cartIndex         = array();

  /**
   * index for all loaded cartridges
   * @var LibGenfProtocol
   */
  public $protocol         = null;

////////////////////////////////////////////////////////////////////////////////
// Protected Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * list of all variables defined in the project description
   * @var array
   */
  protected $vars               = array();
  
  /**
   * Setting Flag
   * @var array
   */
  protected $settings           = array();

  /**
   * all available build objects
   * @var array<LibGenfBuild(*)>
   */
  protected $builder            = array();

  /**
   * all available build objects
   * @var array<LibGenfBuild(*)>
   */
  protected $componentParser    = array();

  /**
   * all available build objects
   * @var array<LibGenfParser(*)>
   */
  protected $parserPool         = array();

  /**
   * Das Simplexml Element für die Projektbeschreibung
   * @var SimpleXmlElement
   */
  protected $project            = null;

  /**
   * Das Node Objekt für einen einfacherer Zugriff auf das Projekt
   * @var LibGenfTreeNodeProject
   */
  public  $projectNode            = null;

  /**
   * cache dynamic class names. so the system don't need to check every time
   * if x classtypes exists
   * @var array
   */
  protected $classCache         = array();

  /**
   * @var string
   */
  protected $headerContent      = null;

  /**
   * @var array
   */
  protected $concepts           = array();

  /**
   * @var array
   */
  protected $messages           = array();
  
  /**
   * whitelist mit cartridges
   * @var array
   */
  protected $cartridgeFilter      = array();
  
  /**
   * @var array
   */
  protected $modPhars             = array();

  /**
   * @var boolean
   */
  public $forceOverwrite          = false;


////////////////////////////////////////////////////////////////////////////////
// Magic Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * default constructor
   * creates a default instance if not yet set
   */
  public function __construct()
  {

    if( !self::$instance  )
      self::$instance = $this;

    // if lib bdl Parser is availabe load
    /*
    if( Webfrap::classLoadable('LibBdlParser') )
      $this->bdlParser = new LibBdlParser();
    */

    // if lib bdl Parser is availabe load
    if( Webfrap::classLoadable( 'LibBdl' ) )
      $this->bdlRegistry = new LibBdl( $this );

    $this->protocol = new LibGenfProtocol();

  }//end public function __construct */

  /**
   * Makes sense, normaly you don'nt want to run more than on builder the same
   * time.
   *
   * @return LibGenfBuild
   */
  public static function getInstance()
  {
    return self::$instance;
  }//end public static function getInstance */
  
  /**
   * Makes sense, normaly you don'nt want to run more than on builder the same
   * time.
   *
   * @return LibGenfBuild
   */
  public static function getDefault()
  {
    return self::$instance;
  }//end public static function getInstance */
  
////////////////////////////////////////////////////////////////////////////////
// resource getter + setter
////////////////////////////////////////////////////////////////////////////////


  /**
   * @setter self::user
   * @param User $user
   */
  public function setUser( User $user )
  {
    
    $this->user = $user;
    
  }//end public function setUser */
  
  /**
   * @getter self::user
   * @return User
   */
  public function getUser( )
  {
    
    if( !$this->user )
      $this->user = User::getActive();
    
    return $this->user;
    
  }//end public function getUser */
  

  /**
   * @setter self::$db
   * @param LibDbConnection $db
   */
  public function setDb( LibDbConnection $db )
  {
    
    $this->db = $db;
    
  }//end public function setDb */
  
  /**
   * @getter self::$db
   * @return LibDbConnection
   */
  public function getDb( )
  {
    
    if( !$this->db )
      $this->db = Db::getActive();
    
    return $this->db;
    
  }//end public function getDb */
  
  
  /**
   * @setter self::cartridgeFilter
   * @param array $cartridgeFilter
   */
  public function setCartridgeFilter( $cartridgeFilter )
  {
    
    $this->cartridgeFilter = $cartridgeFilter;
    
  }//end public function setCartridgeFilter */
    
  /**
   * @getter self::cartridgeFilter
   * @return array
   */
  public function getCartridgeFilter( )
  {

    return $this->cartridgeFilter;
    
  }//end public function getCartridgeFilter */
    
  /**
   * @param string $key
   * @param string $path
   * 
   * @return Phar
   */
  public function getModulePhar( $key, $path )
  {
    
    if( isset($this->modPhars[$key]) )
      return $this->modPhars[$key];
    

    if( file_exists($path) )
      SFilesystem::mkdir($path);
      
    $this->modPhars[$key] = new TDummy( $path.'/'.$key.'.phar' ); 
    $this->modPhars[$key]->startBuffering( );
    $this->modPhars[$key]->setSignatureAlgorithm( Phar::MD5 );
    
    
    return $this->modPhars[$key];
    
  }//end public function getModulePhar */
  
  
////////////////////////////////////////////////////////////////////////////////
// getter and setter methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return SimpleXmlElement
   */
  public function getProject()
  {
    return $this->project;
  }//end public function getProject */

  /**
   * @return LibGenfTreeNodeProject
   */
  public function getProjectNode()
  {

    return $this->projectNode;

  }//end public function getProjectNode */


  /**
   *
   * @return LibGenfTree
   */
  public function getTree()
  {
    return $this->tree;
  }//end public function getTree */

  /**
   * @param string
   * @return LibGenfTreeRoot
   */
  public function getRoot( $nodeType )
  {
    return $this->tree->getRootNode( strtolower($nodeType) );
  }//end public function getRoot */

  /**
   * @return string
   */
  public function getPathOutput()
  {

    if( !$this->pathOutput )
      $this->pathOutput = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';

    return $this->pathOutput;

  }//end public function getPathOutput */

  /**
   * get the defined header for the code output or use the default header
   * which is hardcoded in the code
   * @return string
   */
  public function getHeader()
  {

    if( ! isset($this->project->header) )
      return null;

    if( !$this->headerContent )
    {
      $headerPath = $this->replaceVars( trim($this->project->header) );

      if( !file_exists($headerPath) )
        return null;

      $this->headerContent = $this->replaceVars(file_get_contents($headerPath));

    }

    return $this->headerContent;

  }//end public function getHeader */

  /**
   * get the default title
   */
  public function getTitle( $lang = 'en' )
  {

    if( isset( $this->project->title ) )
      return $this->interpreter->i18nText( $this->project->title, $lang );
    else
      return 'Some WebFrap Project';

  }//end public function getTitle */


  /**
   * @param string $key
   */
  public function globalConcept( $key )
  {

    $key = strtolower($key);
    return array_key_exists($key,$this->concepts)
      ? $this->concepts[$key]
      : null;

  }//end public function globalConcept */

  /**
   * @return array
   */
  public function globalConceptKeys()
  {
    if($this->concepts)
      return array_keys($this->concepts);
    else
      return array();
  }//end public function globalConceptKeys */

  /**
   * @return array
   */
  public function getDefaultDump()
  {

    if( isset( $this->project->data_dump ) )
    {
      return $this->replaceVars( trim($this->project->data_dump) );
    }
    else
      return null;

  }//end public function getDefaultDump */

  /**
   *
   */
  public function cartridgeLoaded( $key )
  {
    return isset( $this->cartIndex[$key] );
  }//end public function cartridgeLoaded */

  /**
   * @return LibResponse
   */
  public function getResponse()
  {

    if( !$this->response )
      $this->response = Response::getActive();

    return $this->response;

  }//end public function getResponse */

////////////////////////////////////////////////////////////////////////////////
// mapper method
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $query a xpath query
   * @param DOMNode $refNode a node create realtive queries
   */
  public function xpath( $query , $refNode = null )
  {

    if( is_object($refNode) && $refNode instanceof DOMNode  )
    {
      return $this->tree->modelXpath->evaluate( $query, $refNode );
    }
    else
    {
      return $this->tree->modelXpath->evaluate( $query );
    }

  }//end public function xpath */

////////////////////////////////////////////////////////////////////////////////
// getter for specific objects
////////////////////////////////////////////////////////////////////////////////

  /**
   * get a build object
   * if not exists the system tries to create a new by checking
   * version, architecture and nodetype
   *
   * @param string $type
   * @return LibGenfBuildAbstract
   */
  public function getBuilder( $type )
  {
    
    if( isset( $this->builder[$type] ) )
    {
      $builder = $this->builder[$type];
    }
    else
    {

      if( WebFrap::classLoadable('LibGenfBuild'.$this->arch.$this->archVersion.$type) )
      {
        $builderClass = 'LibGenfBuild'.$this->arch.$this->archVersion.$type;
      }
      elseif( WebFrap::classLoadable('LibGenfBuild'.$this->arch.$type) )
      {
        $builderClass = 'LibGenfBuild'.$this->arch.$type;
      }
      elseif( WebFrap::classLoadable('LibGenfBuild'.$type) )
      {
        $builderClass = 'LibGenfBuild'.$type;
      }
      else
      {
        Error::addError('Unsupported Cartridgetype: '.$type);
        return null;
      }

      $builder = new $builderClass( $this , $this->pathOutput );
      $this->builder[$type] = $builder;

    }

    return $builder;

  }//end public function getBuilder */

  /**
   * request a nameparser object, to be able to parse the architecture specific names
   * @return LibGenfNameParser
   */
  public function getNameParser(  )
  {
    if( !is_null($this->nameParser) )
      return $this->nameParser;

    if( WebFrap::classLoadable('LibGenfNameParser'.$this->arch.$this->archVersion) )
    {
      $parserClass = 'LibGenfNameParser'.$this->arch.$this->archVersion;
    }
    else if( WebFrap::classLoadable('LibGenfNameParser'.$this->arch) )
    {
      $parserClass = 'LibGenfNameParser'.$this->arch;
    }
    else
    {
      $parserClass = 'LibGenfNameParser';
    }

    $this->nameParser = new $parserClass( );
    return $this->nameParser;

  }//end public function getNameParser */


  /**
   * @param string $pre
   * @param string $post
   */
  public function getModelClass( $pre, $post = '' )
  {

    $pre  = ucfirst( $pre );
    $post = ucfirst( $post );

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['genf_model_class'][$pre.$post] ) )
      return $this->classCache['genf_model_class'][$pre.$post];

    $className = 'LibGenf'.$pre.$this->model.$this->modelVersion.$post;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenf'.$pre.$this->model.$post;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenf'.$pre.$post;

        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Model Class: '.$pre.$post.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }

      }
    }

    $this->classCache['genf_model_class'][$pre.$post] = $className;
    return $className;

  }//end public function getModelClass */

  /**
   * @param $pre
   * @param $post
   */
  public function getArchClass( $pre , $post = '' )
  {

    $pre  = ucfirst( $pre );
    $post = ucfirst( $post );

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['genf_arch_class'][$pre.$post] ) )
      return $this->classCache['genf_arch_class'][$pre.$post];

    $className = 'LibGenf'.$pre.$this->arch.$this->archVersion.$post;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenf'.$pre.$this->arch.$post;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenf'.$pre.$post;

        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Arch Class: '.$className.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }
      }
    }

    $this->classCache['genf_arch_class'][$pre.$post] = $className;
    return $className;

  }//end public function getArchClass */

  /**
   * @param $classType
   */
  public function getNodeClass( $classType, $throwError = true )
  {

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['node'][$classType] ) )
      return $this->classCache['node'][$classType];

    $className = 'LibGenfTreeNode'.$this->model.$this->modelVersion.$classType;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenfTreeNode'.$this->model.$classType;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenfTreeNode'.$classType;

        if( !WebFrap::classLoadable($className) )
        {

          if( $throwError )
          {
            $this->dumpError
            ( 
              'Requested nonexisting Node Class: '.$classType
                .'. Please Check if this class is missing, or if this is a type error.' 
            );
            return null;
          }
          else
          {
            return null;
          }

        }

      }

    }

    $this->classCache['node'][$classType] = $className;
    return $className;

  }//end public function getNodeClass */


  /**
   * get the classname for a specific parser
   * @param $classType
   */
  public function getParser( $type )
  {

    $type = ucfirst($type);

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['node_parser'][$type] ) )
      return $this->classCache['node_parser'][$type];

    $className = 'LibParser'.$this->model.$this->modelVersion.$type;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibParser'.$this->model.$type;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibParser'.$type;

        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Parser: '.$type.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }
      }
    }

    $parser = new $className;
    $this->classCache['node_parser'][$classType] = $parser;
    return $parser;

  }//end public function getParser */


  /**
   * get the classname for a specific parser
   * @param $classType
   */
  public function getGenerator( $type, $env = null, $required = true )
  {

    $type = ucfirst( $type );

    $className = 'LibGenerator'.$this->arch.$this->archVersion.$type;

    if( !WebFrap::classLoadable( $className ) )
    {
      
      $className = 'LibGenerator'.$this->arch.$type;

      if( !WebFrap::classLoadable( $className ) )
      {
        $className = 'LibGenerator'.$type;

        if( !WebFrap::classLoadable( $className ) )
        {
          
          $debugData = '';
          
          if( $this->activNode )
            $debugData = $this->activNode->debugData();
          
          if( $required )
          {
            $this->error
            (
              'Requested nonexisting Generator: '
                .$type.'. Please check if this class is missing, or if this is a type error. '
                .$debugData.Debug::backtrace()
            );
          }
          
          return null;
        }
      }
      
    }//end if( !WebFrap::classLoadable($className) )

    if( Log::$levelDebug )
      Log::debug( 'Found Generator: '.$className );

    $generator = new $className( $this );

    //$this->classCache['code_gen'][$type] = $generator;

    if( $env )
      $generator->setEnv( $env );

    return $generator;

  }//end public function getGenerator */

  /**
   * @param $classType
   */
  public function getNodelistClass( $classType )
  {

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['nodelist'][$classType] ) )
      return $this->classCache['nodelist'][$classType];

    $className = 'LibGenfTreeNodelist'.$this->model.$this->modelVersion.$classType;

    if( !WebFrap::classLoadable( $className ) )
    {
      $className = 'LibGenfTreeNodelist'.$this->model.$classType;

      if( !WebFrap::classLoadable( $className ) )
      {
        $className = 'LibGenfTreeNodelist'.$classType;

        if( !WebFrap::classLoadable( $className ) )
        {
          Error::report
          (
            'Requested nonexisting Nodelist Class: '.$classType
              .'. Please Check if this class is missing, or if this is a type error.'
          );
          return null;
        }

      }
    }

    $this->classCache['nodelist'][$classType] = $className;
    return $className;

  }//end public function getNodelistClass */

  /**
   * @param string $classType
   */
  public function getDefinitionClass( $classType )
  {

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['definition'][$classType] ) )
      return $this->classCache['definition'][$classType];

    $className = 'LibGenfDefinition'.$this->model.$this->modelVersion.$classType;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenfDefinition'.$this->model.$classType;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenfDefinition'.$classType;

        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Definition Class: '.$classType.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }

      }
    }

    $this->classCache['definition'][$classType] = $className;
    return $className;

  }//end public function getDefinitionClass */

  /**
   * @param string $classType
   */
  public function getConceptClass( $classType )
  {

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['concept'][$classType] ) )
      return $this->classCache['concept'][$classType];

    $className = 'LibGenfConcept'.$this->model.$this->modelVersion.$classType;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenfConcept'.$this->model.$classType;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenfConcept'.$classType;

        // no message
        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Concept Class: '.$classType.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }
      }
    }

    $this->classCache['concept'][$classType] = $className;
    return $className;

  }//end public function getConceptClass */

  /**
   * @param string $classType
   */
  public function getRootClass( $classType )
  {

    $className = 'LibGenfTreeRoot'.$this->model.$this->modelVersion.$classType;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenfTreeRoot'.$this->model.$classType;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenfTreeRoot'.$classType;

        if( !WebFrap::classLoadable($className) )
        {
          Error::addError('Requested nonexisting Root Class: '.$classType.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }

      }
    }

    return $className;

  }//end public function getRootClass */

  /**
   * @param $classType
   * @param boolean $optional
   */
  public function getCartridgeClass( $classType , $optional = true )
  {

    ///TODO check wie array_key_exists?
    if( isset( $this->classCache['cartridge'][$classType] ) )
      return $this->classCache['cartridge'][$classType];

    $className = 'LibCartridge'.$this->arch.$this->archVersion.$classType;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibCartridge'.$this->arch.$classType;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibCartridge'.$classType;

        if( !WebFrap::classLoadable($className) )
        {
          if($optional)
            return null;

          Error::addError('Requested nonexisting Cartridge Class: '.$classType.'. Please Check if this class is missing, or if this is a type error.');
          return null;
        }

      }
    }

    $this->classCache['cartridge'][$classType] = $className;
    return $className;

  }//end public function getCartridgeClass */

  /**
   * @param string $cartridgeName
   * @param string $root
   */
  public function getSubCartridge( $cartridgeName, $root = null )
  {

    if( $className = $this->getCartridgeClass( $cartridgeName ) )
    {
      $cartridge = new $className( $this );
      
      if( $root )
        $cartridge->setRoot( $root );
        
      return $cartridge;
    }

    return null;

  }//end public function getSubCartridge */

  /**
   * @param string $type
   * @return LibCartridgeComponent
   */
  public function getComponentParser( $type )
  {

    if( isset( $this->componentParser[$type]  ) )
      return $this->componentParser[$type];

    $className = $this->getCartridgeClass( 'Component'.ucfirst($type) );
      
    if( $className )
    {
      $parser                       = new $className( $this );
      $this->componentParser[$type] = $parser;
      return $parser;
    }

    return null;

  }//end public function getComponentParser */

  /**
   * @return void
   */
  public function loadInterpreter()
  {

    $className = 'LibGenfInterpreter'.$this->model.$this->modelVersion;

    if( !WebFrap::classLoadable($className) )
    {
      $className = 'LibGenfInterpreter'.$this->model;

      if( !WebFrap::classLoadable($className) )
      {
        $className = 'LibGenfInterpreter';
      }
    }

    $this->interpreter  = new $className( $this, $this->tree  );

  }//end public function loadInterpreter */

////////////////////////////////////////////////////////////////////////////////
// main Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  public function build(  )
  {

    // make shure that the garbage collection works
    if( !gc_enabled() )
      gc_enable();

    $user = User::getInstance();
    $start = time();
    
    Log::init( 'Start Generator Time: '.date('H:i:s') );

    //TODO implement safeold with the model
    try
    {

      $this->i18n = LibCartridgeI18n::createInstance( $this );

      // languages aus dem Projekt auslesen
      $this->i18n->setLang( $this->languages );

      $cartridgesRef = $this->project->cartridges;

      $cartridges = array();

      foreach( $cartridgesRef->cartridge as $cartridge )
      {

        if( !isset( $cartridge['type'] ) )
        {
          Error::addError('Requested untpyed Cartridge. Please check Project Description' );
          continue;
        }
        
        // cartidge Filter implementiert
        if( $this->cartridgeFilter )
        {
          if( ! in_array(trim($cartridge['class']), $this->cartridgeFilter) )
            continue;
        }

        $cartridges[trim($cartridge['class'])] = $cartridge;
        $this->cartIndex[trim($cartridge['class'])] = true;

      }

      foreach( $cartridges as $cartridge )
      {

        $type = ucfirst(strtolower($cartridge['type']));

        if( !$builder = $this->getBuilder($type) )
          continue;
        
        $builder->build( $cartridge );

        // force garbage collection now
        gc_collect_cycles(true);

      }//end foreach

      ///TODO bring i18n back to run
      /*
      // parsen der sprachen
      $this->i18n->setTree( $tree );
      */

      $this->i18n->setPathOutput( $this->pathOutput );
      $this->i18n->parse();
      $this->i18n->write();
    
      
      foreach( $this->modPhars as $phar )
      {
        $phar->stopBuffering();
      }

    }
    catch( LibParser_Exception $e )
    {
      Message::addError('Failed to parse the model: '.$this->projectName .' cause: '.$e->getMessage());

      Error::report
      (
        'Failed to parse the model: '.$this->projectName
      );
      
      Log::init( 'Generator aborted: '.date('H:i:s') );

      return false;
    }
    
    Log::init( 'Generator finished: '.date('H:i:s').' duration: '.($start-time()).' seconds' );

    return true;

  }//end public function build */

  /**
   * deploy the generated code to the locations that are defined in the
   * project description
   * @return boolean
   */
  public function deploy(   )
  {


    // first check modules to deploy
    /*
      <import>
        <modul name="core"  deploy="custom" />
    */

    // deploy modules
    if( isset($this->project->import->module) )
    {
      foreach( $this->project->import->module as $module )
      {
        if( !isset($module['deploy']) && 'custom' != trim($module['deploy']) )
        {
          $this->deployModule( $module  );
        }
      }
    }


    /*
      <deployment>
          <mod name="*" >{$PATH_PROJECT}</mod>
     */

    // custom deployment
    if( isset($this->project->deployment->mod) )
    {
      foreach( $this->project->deployment->mod as $module )
      {

        if( '*' == $module['name'] )
        {
          if( isset( $module->repository ) )
          {
            foreach( $module->repository as $repository )
            {
              $this->deployCustomAll( $module, $repository->path  );
              $this->deploycustomModulRepository( $module, $repository  );
            }
          }
          else
          {
            if( isset($module->path) )
            {
              $this->deployCustomAll( $module, $module->path );
            }
            else
            {
              $this->deployCustomAll( $module );
            }
          }
        }
        else
        {
          $fallback = true;

          if( isset( $module->path ) )
          {
            foreach( $module->path as $path )
            {
              $this->deploycustomModulPath( $module, $path  );
            }

            $fallback = false;
          }
          
          if( isset( $module->path_test ) )
          {
            foreach( $module->path_test as $path )
            {
              $this->deploycustomModulPath( $module, $path, '-test'  );
            }

            $fallback = false;
          }

          if( isset( $module->repository ) )
          {
            foreach( $module->repository as $repository )
            {
              $this->deploycustomModulRepository( $module, $repository  );
            }

            $fallback = false;
          }
          
          if( isset( $module->package ) )
          {
            foreach( $module->package as $package )
            {
              $this->deploycustomModulPackage( $module, $package  );
            }

            $fallback = false;
          }

          if( $fallback )
          {
            $this->deploycustomModul( $module  );
          }
          
        }
      }
    }

    return true;

  } // end public function deploy */

  /**
   *
   * @param SimpleXMLElement $projectXml
   * @return unknown_type
   */
  public function clean(  )
  {

    /// some security checks to be shure to clean only inside the real cache output

    $realCoderoot = realpath(PATH_ROOT);
    $realOutput   = realpath( $this->pathOutput);

    if( ! substr( $realOutput, 0 , strlen($realCoderoot) ) == $realCoderoot )
    {
      Message::addError('Tried to clean a cachefolder ('.$realCoderoot.' : '.$realOutput.') outside of the Coderoot. This is not permitted cause of security reasons');
      return;
    }

    if(SFilesystem::cleanFolder( $this->pathOutput  ))
      Message::addMessage(I18n::s('wbf.msg',array($this->pathOutput)));

    else
      Message::addError('wbf.msg',array($this->pathOutput));


  } // end public function clean */


  /**
   * clean all database dumps
   * @return boolean
   */
  public function cleanDatabase(  )
  {

    foreach( $this->project->databases->connection as  $connection)
    {

      //TODO implement safeold with the model
      try
      {

        $dbType   = ucfirst((string)$connection['type']);
        $conName  = (string)$connection['name'];
        $schema   = (string)$connection->dataschema;

        $dbConf   = array();
        $dbConf['class']    = (string)$connection['driver'];

        $dbConf['dbhost']   = (string)$connection->host;
        $dbConf['dbport']   = (string)$connection->port;
        $dbConf['dbname']   = (string)$connection->db;
        $dbConf['dbuser']   = (string)$connection->user;
        $dbConf['dbpwd']    = (string)$connection->pwd;
        $dbConf['dbschema'] = 'public';

        $dbWork       = Db::connectDb( $conName , $dbConf );
        $dbAdminClass = 'LibDbAdmin'.$dbType;
        $dbAdmin      = new $dbAdminClass( $dbWork );
        //$dbAdmin = new LibDbAdminPostgresql( $dbWork );

        $dbAdmin->setOwner( (string)$connection->owner ) ;

        if( $dbAdmin->schemaExists( (string)$connection->dataschema ) )
        {
          $dbWork->exec( "DROP SCHEMA {$schema} CASCADE;" );
        }


      }
      catch( LibDb_Exception $e )
      {
        Message::addError( 'Datenbankfehler: '.$e->getMessage() );
        continue;
      }

    }


  }//end public function syncDatabase */

  /**
   * synchronize the database with the model
   * @return boolean
   */
  public function syncDatabase(  )
  {

    /*
      <databases>
        <connection driver="Postgresql" type="Postgresql" name="webfrap_all" >
          <host>localhost</host>
          <db>webfrap_gw_full</db>
          <port>5432</port>
          <dataschema>webfrap</dataschema>
          <owner>webfrapadmin</owner>
          <user>webfrapadmin</user>
          <pwd>webfrapadmin</pwd>
     */


    foreach( $this->project->databases->connection as  $connection)
    {


      //TODO implement safeold with the model
      try
      {


        $dbType   = ucfirst((string)$connection['type']);
        $conName  = (string)$connection['name'];

        $schema   = (string)$connection->dataschema;

        $dbConf   = array();
        $dbConf['class']    = (string)$connection['driver'];

        if( isset($connection['singleSeq']) && (string)$connection['singleSeq'] == 'false'  )
        {
          $tableSeq = true;
        }
        else
        {
          $tableSeq = false;
        }

        $dbConf['dbhost']   = (string)$connection->host;
        $dbConf['dbport']   = (string)$connection->port;
        $dbConf['dbname']   = (string)$connection->db;
        $dbConf['dbuser']   = (string)$connection->user;
        $dbConf['dbpwd']    = (string)$connection->pwd;

        $dbConf['dbschema'] = 'public';


        $dbWork = Db::connectDb( $conName , $dbConf );

        $protocol = new LibProtocolFile(PATH_GW.'tmp/'.$dbConf['dbname'].'.sql');
        $dbWork->setProtocol($protocol);

        $dbAdminClass = 'LibDbAdmin'.$dbType;

        $dbAdmin      = new $dbAdminClass( $dbWork );
        //$dbAdmin = new LibDbAdminPostgresql( $dbWork );

        $dbAdmin->setOwner( (string)$connection->owner ) ;

        if( !$dbAdmin->schemaExists( (string)$connection->dataschema ) )
        {

          $dbWork->exec( "CREATE SCHEMA {$schema} AUTHORIZATION {$connection->owner}" );

          $dbWork->setSearchPath( $schema );
          $dbAdmin->setSchemaName( $schema );

          if( !$tableSeq )
          {

            $seqName = Db::SEQUENCE;

            $dbWork->exec( "CREATE SEQUENCE {$schema}.{$seqName} START ".LibDbAdminPostgresql::SEQ_START." INCREMENT BY 1 " );
            $dbWork->exec( "ALTER TABLE  {$schema}.{$seqName} OWNER TO {$connection->owner}" );
          }

        }
        else
        {
          $dbWork->setSearchPath( $schema );
          $dbAdmin->setSchemaName( $schema );
        }

        $entities = $this->tree->getRootNode('Entity');

        foreach( $entities as $entity )
        {

          try
          {
            foreach( $entity as $attribute )
            {
              if( $sequence = $attribute->sequence() )
              {
                if( is_string($sequence) && !$dbAdmin->sequenceExists($sequence) )
                {
                  $dbAdmin->createSequence( $sequence , null, 1 );
                }
              }
            }

            $tableName = $entity->name->source;

            if( $dbAdmin->tableExists($tableName)  )
            {
              $dbAdmin->syncEntityTable( $tableName, $entity);
            }
            else
            {
              $dbAdmin->createEntityTable( $tableName, $entity , $tableSeq );
            }
          }
          catch( LibDb_Exception $e )
          {
            Message::addError( 'Failed to Sync Entity: '.$tableName.' cause: '.$e->getMessage() );
            continue;
          }

        }

        $dbWork->resetProtocol();

      }
      catch( LibDb_Exception $e )
      {
        Message::addError( 'Datenbankfehler: '.$e->getMessage() );
        continue;
      }

    }


  }//end public function syncDatabase */


  public function loadProjectDump()
  {


    if( $path = $this->getDefaultDump() )
    {

      if( $dataDump = SFiles::read($path) )
      {

        foreach( $this->project->databases->connection as  $connection)
        {


          //TODO implement safeold with the model
          try
          {


            $dbType   = ucfirst((string)$connection['type']);
            $conName  = (string)$connection['name'];

            $schema   = (string)$connection->dataschema;

            $dbConf   = array();
            $dbConf['class']    = (string)$connection['driver'];

            if( isset($connection['singleSeq']) && (string)$connection['singleSeq'] == 'false'  )
            {
              $tableSeq = true;
            }
            else
            {
              $tableSeq = false;
            }

            $dbConf['dbhost']   = (string)$connection->host;
            $dbConf['dbport']   = (string)$connection->port;
            $dbConf['dbname']   = (string)$connection->db;
            $dbConf['dbuser']   = (string)$connection->user;
            $dbConf['dbpwd']    = (string)$connection->pwd;

            if( isset($connection->dataSchema) )
              $dbConf['dbschema'] = (string)$connection->dataSchema;

            $dbWork = Db::connectDb( $conName , $dbConf );

            $dbWork->exec( $dataDump );


          }
          catch( LibDb_Exception $e )
          {
            Message::addError( 'Datenbankfehler: '.$e->getMessage() );
            continue;
          }

        }


      }

    }

  }//end public function loadProjectDump */

  /**
   *
   * @return unknown_type
   */
  public function createDbPatch( )
  {

    $revision = trim($this->project->revision);

    foreach( $this->project->databases->connection as  $connection)
    {

      //TODO implement safeold with the model
      try
      {

        $dbType   = ucfirst((string)$connection['type']);
        $conName  = (string)$connection['name'];

        $schema   = (string)$connection->dataschema;

        $dbConf   = array();
        $dbConf['class']    = (string)$connection['driver'];

        if( isset($connection['singleSeq']) && (string)$connection['singleSeq'] == 'false'  )
        {
          $tableSeq = true;
        }
        else
        {
          $tableSeq = false;
        }

        $dbConf['dbhost']   = (string)$connection->host;
        $dbConf['dbport']   = (string)$connection->port;
        $dbConf['dbname']   = (string)$connection->db;
        $dbConf['dbuser']   = (string)$connection->user;
        $dbConf['dbpwd']    = (string)$connection->pwd;

        $dbConf['dbschema'] = 'public';


        $dbWork = Db::connectDb( $conName , $dbConf );


        $dbAdminClass = 'LibDbAdmin'.$dbType;

        $dbAdmin      = new $dbAdminClass( $dbWork );

        // don't sync just patch
        $dbAdmin->setSyncFlag(false);
        $dbAdmin->setPatchFlag(true);

        $dbAdmin->setMultiSeq( $tableSeq );

        //$dbAdmin = new LibDbAdminPostgresql( $dbWork );
        $dbAdmin->setOwner( (string)$connection->owner ) ;

        if( !$dbAdmin->schemaExists( $schema ) )
        {

          $dbAdmin->createSchema( null, $schema );

          //$dbWork->exec( "CREATE SCHEMA {$schema} AUTHORIZATION {$connection->owner}" );
          //$dbWork->setSearchPath( $schema );

          $dbAdmin->setSearchPath( $schema );

          if( !$tableSeq )
          {
            $dbAdmin->createMainSequence( $schema );
          }

        }
        else
        {
          $dbAdmin->setSearchPath( $schema );

          //$dbWork->setSearchPath( $schema );
          //$dbAdmin->setSchemaName( $schema );
        }

        foreach( $registry->entities as $entity )
        {

          $tableName = (string)$entity['name'];

          if( $dbAdmin->tableExists($tableName)  )
          {
            $this->syncTable( $tableName, $entity , $dbAdmin );
          }
          else
          {
            $this->createTable( $tableName, $entity , $dbAdmin , $tableSeq );
          }

        }

        $patchData = $dbAdmin->getPatch();

        $patchPath = $this->projectPath.'data/ddl/patch_'.'rev_'.$revision.'_'.$conName.'.sql';

        if(file_exists( $this->projectPath.'data/ddl/' ))
          SFilesystem::createFolder( $this->projectPath.'data/ddl/' );

        Message::addMessage( 'Create Patch : '.$patchPath );
        SFiles::write( $patchPath , $patchData );

      }
      catch( LibDb_Exception $e )
      {
        Message::addError( 'Datenbankfehler: '.$e->getMessage() );
        continue;
      }

    }// end foreach


  }//end public function createDbPatch */

  /**
   *
   * @param string $schema
   * @return unknown_type
   */
  public function recreateDatabase( $schema )
  {

    $db = Db::getActive();

    if( $error = $db->ddlQuery('DROP SCHEMA '.$schema.' cascade;') )
      Message::addError('Failed to delete Schema: '.$error );

    if( $error = $db->ddlQuery(SFiles::read( PATH_GW.'data/ddl/'.$schema.'/postgresql/FullDump.sql')) )
      Message::addError('Failed to recreate the Database: '.$error);
    else
      Message::addMessage('Sucessfully recreated Database');


  }//end public function recreateDatabase */

////////////////////////////////////////////////////////////////////////////////
// help methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param SimpleXmlElement $project the xml project description
   */
  public function loadProject( $project  )
  {

    $this->parseVars($project);

    $this->project      = $project;
    
    //$this->projectNode  = new LibGenfProject( $project );

    $this->projectNode  = new LibGenfTreeNodeProject( $project );

    $this->author     = isset($project->author)
      ? $this->replaceVars(trim($project->author))
      : null;

    $this->copyright  = isset($project->copyright)
      ? $this->replaceVars(trim($project->copyright))
      : null;

    $this->licence    = isset($project->licence)
      ? $this->replaceVars(trim($project->licence))
      : null;

    if( isset($project->languages) )
    {
      foreach( $project->languages->lang as $lang )
      {
        $this->languages[] = strtolower($this->replaceVars(trim($lang)));
      }

      $this->langDefault   = isset($project->languages['default'])
        ? $this->replaceVars(trim($project->languages['default']))
        : 'en';

      $this->langCode      = isset($project->languages['code'])
        ? $this->replaceVars(trim($project->languages['code']))
        : 'en';

    }

    $this->version  = isset($project->version)
      ? trim($this->replaceVars($project->version))
      : null;
    $this->revision = isset($project->revision)
      ? trim($this->replaceVars($project->revision))
      : null;

    $this->arch         = isset($project->architecture['key'])
      ? ucfirst($this->replaceVars(trim($project->architecture['key'])))
      : 'Wbf';
      
    $archClassName = 'LibGenfArchitecture_'.$this->arch;
    if( Webfrap::classLoadable( $archClassName ) )
      $this->archNode = new $archClassName( $this );

    $this->archVersion  = isset($project->architecture['version'])
      ? SWbf::versionToString($this->replaceVars((string)$project->architecture['version']))
      : null;

    $this->model        = isset($project->model['key'])
      ? ucfirst($this->replaceVars(trim($project->model['key'])))
      : 'Bdl';
    $this->modelVersion = isset($project->model['version'])
      ? SWbf::versionToString($this->replaceVars((string)$project->model['version']))
      : null;

    $this->projectKey  = isset($project['name'])
      ? $this->replaceVars((string)$project['name'])
      : null;

    $this->appKey  = isset($project->key)
      ? $this->replaceVars((string)$project->key)
      : 'webfrap';

    $this->projectTitle = isset($project['title'])
      ? $this->replaceVars((string)$project['title'])
      : null;

    $this->projectName  = isset($project->name)
      ? $this->replaceVars((string)$project->name)
      : null;
    $this->projectUrl = isset($project->url)
      ? $this->replaceVars((string)$project->url)
      : null;
      
    $this->targetGroup = isset($project->target_group)
      ? $this->replaceVars((string)$project->target_group)
      : 'kmu';
      
    $this->mockup = ( isset($project->mockup) && 'true' == trim($project->mockup) )
      ? true
      : false;

    if( isset( $project->sandbox ) && 'false' == trim($project->sandbox) )
    {
      $this->sandbox = false;
    }

    if( !isset($project->path->path_output) )
    {
      $this->pathOutput = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $this->pathOutput = $this->replaceVars((string)$project->path->path_output);
    }

    $this->projectPath = isset($project->path->path_gw)
      ? $this->replaceVars((string)$project->path->path_gw)
      : $this->pathOutput;
      
    $this->pathIncludeList = isset($project->path->path_include_list)
      ? $this->replaceVars((string)$project->path->path_include_list)
      : $this->pathOutput.'conf/include/gmod/';

    $this->addProjectVars();


    if( isset( $project->concepts->concept ) )
    {
      foreach( $project->concepts->concept as $concept )
      {
        
        $conceptName  = trim($concept['name']);
        $conceptKey   = strtolower($conceptName);
        
        // check if a concept is global disabled
        if( isset($concept['disable']) )
        {
          $this->concepts[$conceptKey] = false;
          continue;
        }

        $className = $this->getNodeClass( 'Concept'.SParserString::subToCamelCase($conceptName) , false );

        if( Webfrap::classLoadable($className) )
        {
          // fourth parameter means this is a global
          $this->concepts[$conceptKey] = new $className( $concept , null, array(), true );
        }
        else
        {
          // if we have no node we set a boolean flag
          $this->concepts[$conceptKey] = true;
        }

      }
    }

    if( isset( $project->settings->var ) )
    {
      foreach( $project->settings->var as $var )
      {
        
        $key    = trim($var['key']);
        $value  = trim($var['value']);
        
        if( 'false' == $value  )
          $value = false;
        
        // if we have no node we set a boolean flag
        $this->settings[$key] = $value;
      }
    }
    
  }//end public function loadProject */

  
  /**
   * @param string $key Flagkey
   * @param string $subKey Key des Attributes, wenn nicht vorhanden wird der Textwert genommen
   * @param string $def Default Value welcher verwendet wird wenn keine Daten zur Flag vorhanden sind
   * @return string
   */
  public function getArchitectureFlag( $key, $subKey = null, $def = null )
  {
    
    if( !isset( $this->project->architecture->$key ) )
    {
      return $def;
    }
    
    $keyNode = $this->project->architecture->$key;

    if( $subKey )
    {
      return ( isset( $keyNode[$subKey] ) )
        ? trim( $keyNode[$subKey] )
        : $def;
    }
    else 
    {
      return trim( $keyNode );
    }
    
  }//end public function getArchitectureFlag */
  
  /**
   * @param boolean $sync 
   * @return void
   */
  public function buildTree( $sync = false )
  {

    $this->tree = LibGenfTree::init( $this, $this->arch, $this->archVersion, $sync );
    
    $loadedCartTypes = $this->projectNode->getCartridgeTypes();
    
    $this->tree->setCartridgeTypes( $loadedCartTypes );

    if( $sync )
      $this->tree->createSyncTree( );
    else
      $this->tree->createTree( );

  }//end protected function buildTree */



  /**
   * @return string
   */
  public function getLanguages()
  {
    return $this->languages;

  }//end public function getLanguages */


  /**
   * @param string $filePath
   * @return string
   */
  public function loadRepoDocument( $filePath )
  {

    if( !$this->activeRepo )
    {
      Error::addError( 'called '.__method__.' but '.__class__.'::activeRepo was not set' );
      return null;
    }

    $file = $this->activeRepo.'/'.$filePath;

    if( !file_exists($file) )
    {
      Error::addError( 'Failed to load the requested Document. File: '.$file.' not exists!' );
      return null;
    }

    //Log::debug('Loaded file: '.$file);

    $tmpXml = new DOMDocument('1.0', 'utf-8');
    $tmpXml->preserveWhitespace  = false;
    $tmpXml->formatOutput        = true;

    // if load failes return a empty array
    if(!$tmpXml->load( $file ))
    {
      Error::report( 'Failed to load the requested Document. File: '.$file.' is invalid!' );
      return array();
    }
    
    // potentiell vorhandene x includes laden
    $tmpXml->xinclude();

    return $tmpXml;

  }//end public function loadRepoDocument */
  
  /**
   * @param string $filePath
   * @return string
   */
  public function loadIncludeDocument( $filePath )
  {

    $file = PATH_ROOT.$filePath;

    if( !file_exists($file) )
    {
      Error::addError( 'Failed to load the requested Document. File: '.$file.' not exists!' );
      return null;
    }

    //Log::debug('Loaded file: '.$file);

    $tmpXml = new DOMDocument('1.0', 'utf-8');
    $tmpXml->preserveWhitespace  = false;
    $tmpXml->formatOutput        = true;

    // if load failes return a empty array
    if(!$tmpXml->load( $file ))
    {
      Error::report( 'Failed to load the requested Document. File: '.$file.' is invalid!' );
      return array();
    }
    
    // potentiell vorhandene x includes laden
    $tmpXml->xinclude();

    return $tmpXml;

  }//end public function loadIncludeDocument */

////////////////////////////////////////////////////////////////////////////////
// getter & var Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * parse the variables from the project description
   * @param SimpleXmlElement $projectXml
   * @return void
   */
  public function parseVars( $projectXml )
  {

    // zuerst leeren
    $this->vars = array();

    // replace most important path vars
    foreach( $projectXml->var as $var )
    {
      $this->vars['{$'.trim($var['name']).'}'] = str_replace
      (
        array('{$PATH_FW}','{$PATH_GW}','{$PATH_ROOT}'),
        array(PATH_FW,PATH_GW,PATH_ROOT),
        trim($var['value'])
      );
    }

    // add most important path vars
    $this->vars['{$PATH_FW}']     = PATH_FW;
    $this->vars['{$PATH_GW}']     = PATH_GW;
    $this->vars['{$PATH_ROOT}']   = PATH_ROOT;


  }//end public function parseVars */

  /**
   * add special vars to the project
   */
  public function addProjectVars( )
  {

    $list = array
    (
      'author',
      'copyright',
      'licence',
      'version',
      'revision',
      'arch',
      'archVersion',
      'model',
      'modelVersion',
      'projectKey',
      'projectName',
      'projectTitle',
      'projectUrl',
      'pathOutput',
      'langDefault',
      'langCode',
      'rowidKey',
    );

    foreach( $list as $var )
    {
      $this->vars["{\$$var}"] = $this->$var;
    }

  }//end public function addProjectVars */

  /**
   * get a variable from the project description
   * @param string $key
   * @return string the project variable
   */
  public function getVar( $key )
  {
    
    return isset( $this->vars['{$'.$key.'}'] ) 
      ? $this->vars['{$'.$key.'}'] 
      : null;
     
  }//end public function getVar */
  
  /**
   * get a variable from the project description
   * @param string $key
   * @return string the project variable
   */
  public function setting( $key, $defValue = null )
  {
    
    return isset( $this->settings[$key] ) 
      ? $this->settings[$key] 
      : $defValue;
     
  }//end public function setting */

  /**
   * @param $string
   * @return string
   */
  public function replaceVars( $string )
  {
    
    return str_replace( array_keys($this->vars) , array_values($this->vars) , $string  );
    
  }//end public function replaceVars */

  /**
   * request a specific path from the project description
   * @param string $name
   */
  public function getPath( $name )
  {

    $pathName = 'path_'.strtolower($name);

    if( isset( $this->project->$pathName ) )
      return trim( $this->project->$pathName );
    else
      return null;

  }//end public function getPath */
  
  /**
   * @return array
   */
  public function getMultiple()
  {
    
    return LibDbAdmin::getInstance()->getMultiple();
    
  }//end public function getMultiple */
  
////////////////////////////////////////////////////////////////////////////////
// zugriff auf resources
////////////////////////////////////////////////////////////////////////////////

  /**
   * shortcut to get an entity
   * 
   * @param string $name
   * @return LibGenfTreeNodeEntity
   */
  public function getEntity( $name )
  {
    
    return $this->getRoot( 'Entity' )->getEntity($name);
    
  }//end public function getEntity */

  /**
   * shortcut to get an entity
   * 
   * @param string $name
   * @return LibGenfTreeNodeModule
   */
  public function getModule( $name )
  {
    
    return $this->getRoot( 'Module' )->getModule( $name );
    
  }//end public function getModule */
  
  /**
   * shortcut to get a management
   * 
   * @param string $name
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement( $name )
  {
    
    return $this->getRoot( 'Management' )->getManagement( $name );
    
  }//end public function getManagement */
  
  /**
   * shortcut to get a management
   * 
   * @param string $name
   * @return LibGenfTreeNodeComponent
   */
  public function getComponent( $name, $type )
  {
    
    return $this->getRoot( 'Component' )->getComponent( $name, $type );
    
  }//end public function getComponent */
  
  /**
   * shortcut to get a management
   * 
   * @param SimpleXmlElement $node
   * @return LibGenfTreeNodeItem
   */
  public function getItem( $node )
  {
    
    return $this->getRoot( 'Item' )->getItem( $node );
    
  }//end public function getItem */
  
  /**
   * shortcut to get an action
   * 
   * @param string $name
   * @return LibGenfTreeNodeAction
   */
  public function getAction( $name )
  {
    
    return $this->getRoot( 'Action' )->getAction( $name );
    
  }//end public function getAction */
  
  /**
   * Component Rootnode getter Shortcut
   * @return LibGenfTreeRootComponent
   */
  public function getComponentRoot( )
  {
    
    return $this->getRoot( 'Component' );
    
  }//end public function getComponentRoot */
  
  /**
   * shortcut to get a management
   * 
   * @return LibGenfTreeRootManagement
   */
  public function getManagementRoot( )
  {
    
    return $this->getRoot( 'Management' );
    
  }//end public function getManagementRoot */
  
  /**
   * shortcut to get an entity
   * 
   * @return LibGenfTreeRootEntity
   */
  public function getEntityRoot( )
  {
    
    return $this->getRoot( 'Entity' );
    
  }//end public function getEntityRoot */
  
  /**
   * @return array
   */
  public function getInjectGenerators( $env )
  {

    $generators = array();

    $generators[] = $this->getGenerator( 'ProcessInjector', $env );
    $generators[] = $this->getGenerator( 'EventActionClass', $env );
    $generators[] = $this->getGenerator( 'ConceptInjector', $env );

    return $generators;

  }//end public function getInjectGenerators */

////////////////////////////////////////////////////////////////////////////////
// protected methode
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param SimpleXmlElement $module
   * @return boolean
   */
  protected function deployModule( $module )
  {

  }

  /**
   *
   * @param SimpleXmlElement $module
   * @return boolean
   */
  protected function deployCustomAll( $module, $path = null )
  {

    $response = $this->getResponse();

    Log::debug('Called Full Deploy');

    $pathSource = $this->getPathOutput();

    if( $path )
    {
      $pathDeploy = $this->replaceVars(trim($path));
    }
    else
    {
      if( '' != trim($module) )
        $pathDeploy = $this->replaceVars(trim($module));
      else
        $pathDeploy = $this->getPath('deploy');
    }


    Log::debug('$pathSource :'.$pathSource);
    Log::debug('$pathDeploy :'.$pathDeploy);

    if( !is_dir( $pathDeploy.'sandbox/' ) )
    {
      $abs = $pathDeploy[0] == '/' ? true:false;

      if( !SFilesystem::createFolder($pathDeploy,true, $abs) )
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to create the Folder {@folder@}.',
            'wbf.msg',
            array('folder'=>$pathDeploy)
          )
        );
        return;
      }
    }

    $folder = new LibFilesystemFolder( $pathSource.'genf' );
    $mods   = $folder->getFolders(false);

    foreach( $mods as $modPath )
    {
      if(SFilesystem::copy( $modPath, $pathDeploy.'sandbox/') )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src' => $modPath,
              'target' => $pathDeploy.'sandbox/',
            )
          )
        );
      }
      else
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src'    => $modPath,
              'target' => $pathDeploy.'sandbox/'
            )
          )
        );
      }
    }

    $folder = new LibFilesystemFolder( $pathSource.'hand' );
    $mods   = $folder->getFolders(false);

    foreach( $mods as $modPath )
    {

      // if defined overwrite the handcode in src will be overwritten to
      // else the code will be just merged, means creat if not exist but not
      // overwritten
      if( isset($module['overwrite']) && 'all' == trim($module['overwrite']) )
        $copy = 'copy';
      else
        $copy = 'merge';

      if( SFilesystem::$copy( $modPath, $pathDeploy ) )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src' => $modPath,
              'target' => $pathDeploy,
            )
          )
        );
      }
      else
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              $modPath,
              $pathDeploy
            )
          )
        );
      }
    }

  }//end protected function deployCustomAll */

  /**
   *
   * @param SimpleXmlElement $module
   * @return boolean
   */
  protected function deploycustomModul( $module )
  {

    $response = $this->getResponse();

    $modName    = trim($module['name']);
    $pathSource = $this->getPathOutput();

    if( '' != trim($module) )
      $pathDeploy = $this->replaceVars(trim($module));
    else
      $pathDeploy = $this->getPath('deploy');

    if( !is_dir( $pathDeploy.'sandbox/' ) )
    {
      $abs = $pathDeploy[0] == '/' ? true:false;

      if( !SFilesystem::createFolder($pathDeploy,true, $abs) )
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to create the Folder {@folder@}.',
            'wbf.msg',
            array('folder'=>$pathDeploy)
          )
        );
        return;
      }
    }

    $folderGenf = $pathSource.'genf/'.$modName.'/' ;
    
    if( file_exists( $folderGenf ) )
    {
      if( SFilesystem::copy( $folderGenf, $pathDeploy.'sandbox/' ) )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src'      => $folderGenf,
              'target'  => $pathDeploy,
            )
          )
        );
      }
      else
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              $folderGenf,
              $pathDeploy
            )
          )
        );
      }
    }

    $folderHand = $pathSource.'hand/'.$modName.'/' ;
    if( file_exists($folderHand) )
    {

      // if defined overwrite the handcode in src will be overwritten to
      // else the code will be just merged, means creat if not exist but not
      // overwritten
      if( isset($module['overwrite']) && 'all' == trim($module['overwrite']) )
        $copy = 'copy';
      else
        $copy = 'merge';

      if( SFilesystem::$copy( $folderHand, $pathDeploy ) )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src'     => $folderHand,
              'target'  => $pathDeploy,
            )
          )
        );
      }
      else
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              $folderHand,
              $pathDeploy
            )
          )
        );
      }

    }//end if

  }//end protected function deploycustomModul */


  /**
   *
   * @param SimpleXmlElement $module
   * @param string $path
   * @return boolean
   */
  protected function deploycustomModulPath( $module, $path, $postFix = '' )
  {

    $response = $this->getResponse();

    $modName    = trim($module['name']).$postFix;
    $pathSource = $this->getPathOutput();
    
    
    if( isset( $module['key'] ) )
    {
      SFilesystem::touch( $this->pathIncludeList.'/'.trim($module['key']) );
    }

    if( '' != trim($path) )
      $pathDeploy = $this->replaceVars(trim($path));
    else
      $pathDeploy = $this->getPath('deploy');

    if( !is_dir( $pathDeploy.'sandbox/' ) )
    {
      $abs = $pathDeploy[0] == '/' ? true:false;

      if( !SFilesystem::createFolder($pathDeploy,true, $abs) )
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to create the Folder {@folder@}.',
            'wbf.message',
            array('folder'=>$pathDeploy)
          )
        );
        return;
      }
    }

    $folderGenf = $pathSource.'genf/'.$modName.'/' ;
    if( file_exists($folderGenf) )
    {
      if(SFilesystem::copy( $folderGenf, $pathDeploy.'sandbox/') )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src'    => $folderGenf,
              'target' => $pathDeploy,
            )
          )
        );
      }
      else
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              $folderGenf,
              $pathDeploy
            )
          )
        );
      }
    }

    $folderHand = $pathSource.'hand/'.$modName.'/' ;
    if( file_exists($folderHand) )
    {

      // if defined overwrite the handcode in src will be overwritten to
      // else the code will be just merged, means creat if not exist but not
      // overwritten
      if( isset($module['overwrite']) && 'all' == trim($module['overwrite']) )
        $copy = 'copy';
      else
        $copy = 'merge';

      if( SFilesystem::$copy( $folderHand, $pathDeploy ) )
      {
        $response->addMessage
        (
          $response->i18n->l
          (
            'Sucessfully copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              'src' => $folderHand,
              'target' => $pathDeploy,
            )
          )
        );
      }
      else
      {
        $response->addError
        (
          $response->i18n->l
          (
            'Failed to copied folder {@src@} to {@target@}.',
            'wbf.message',
            array
            (
              $folderHand,
              $pathDeploy
            )
          )
        );
      }

    }//end if

  }//end protected function deploycustomModul */

  /**
   * @param SimplexmlElement $module
   * @param SimplexmlElement $repo
   * @return boolean
   */
  protected function deploycustomModulRepository( $module, $repo )
  {

    $repoclass = 'LibRcs'.ucfirst(trim($repo['class']));

    $path = $this->replaceVars(trim($repo->path));
    $url  = $this->replaceVars(trim($repo->url));

    $user  = $this->replaceVars(trim($repo->url['user']));
    $passwd  = $this->replaceVars(trim($repo->url['passwd']));
    
    $displayUser = isset( $repo['display_user'] )
      ? trim($repo['display_user'])
      : 'Legion <legion@webfrap.net>';

      
    $message = '- this is an auto commit for synchronizing the repository with the master';

    if( isset( $repo->message ) )
    {
      $message = trim($repo->message);
    }

    $request = Request::getActive();
      
    if( $msg = $request->param( 'message', Validator::TEXT ) )
    {
      $message = $msg;
    }
    
    $rcs = new $repoclass();
    $rcs->init( $path, $url, $displayUser, $user, $passwd );

    //$rcs->pull( $url );

    // expect to get a path here
    $this->deploycustomModulPath( $module, trim($repo->path) );

    $rcs->commit( $message );
    
    if( isset( $repo['url'] ) && isset($repo['deploy']) )
    {
      $rcs->push( trim($repo['url']) );
    }

    $rcs->close();

  }//end protected function deploycustomModulRepository */

  
  /**
   * @param SimplexmlElement $module
   * @param SimplexmlElement $package
   * @return boolean
   */
  protected function deploycustomModulPackage( $module, $package )
  {

    $path = $this->replaceVars( trim( $package->path ) );
    
    if( file_exists( $path ) )
    {
      SFilesystem::delete( $path );
    }
    
    $phar = new TDummy( $path );
    
    $response   = $this->getResponse();

    $modName    = trim($module['name']);
    $pathSource = $this->getPathOutput();

    if( '' != trim($module) )
      $pathDeploy = $this->replaceVars( trim($module));
    else
      $pathDeploy = $this->getPath('deploy');


    //$folderGenf = $pathSource.'genf/'.$modName.'/' ;
    $folderHand = $pathSource.'hand/'.$modName.'/' ;
    
    $phar->buildFromDirectory( $folderHand );

  }//end protected function deploycustomModulPackage */

  /**
   * just commit a given repository
   * @param SimplexmlElement $module
   * @param SimplexmlElement $repo
   * @return boolean
   */
  protected function commitcustomModulRepository( $module, $repo )
  {

    $repoclass = 'LibRcs'.ucfirst(trim($repo['class']));

    $path = $this->replaceVars(trim($repo->path));
    $url  = $this->replaceVars(trim($repo->url));

    $user  = $this->replaceVars(trim($repo->url['user']));
    $passwd  = $this->replaceVars(trim($repo->url['passwd']));

    $rcs = new $repoclass();
    $rcs->init( $path, $url, 'Legion <legion@webfrap.net', $user, $passwd );

    //$rcs->pull( $url );
    $rcs->commit( '- this is an auto commit for synchronizing the repository with the master' );
    //$rcs->push( $url );

    $rcs->close();

  }//end protected function deploycustomModulRepository */



/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @param string $message
   */
  public function notice( $message )
  {
    if(DEBUG)
      Debug::console( 'NOTICE: '.$message );

    Message::addMessage( $message );

    $this->messages[] = array('warn',$message);
  }//end public function warn */

  /**
   * @param string $message
   */
  public function warn( $message )
  {
    if(DEBUG)
      Debug::console( 'WARN: '.$message );

    Message::addWarning( $message );

    $this->messages[] = array('warn',$message);
  }//end public function warn */

  /**
   * @param string $message
   */
  public function error( $message )
  {

    if(DEBUG)
      Debug::console( 'ERROR: '.$message );

    Message::addError( $message );

    $this->messages[] = array( 'error', $message );

  }//end public function error */
  
  /**
   * @param string $message
   */
  public function dumpError( $message, $toDump = null )
  {

    $message .= $this->dumpEnv();
    
    if(DEBUG)
      Debug::console( 'ERROR: '.$message, $toDump );

    Message::addError( $message );

    $this->messages[] = array( 'error', $message );

  }//end public function error */
  
  /**
   * @param string $message
   */
  public function dumpInfo( $message, $toDump = null )
  {

    $message .= $this->dumpEnv();
    $message .= Debug::dumpToString( $toDump );
    
    if( DEBUG )
      Debug::console( 'INFO: '.$message );

    Message::addMessage( $message );

    $this->messages[] = array( 'info', $message );

  }//end public function dumpInfo */

  /**
   * 
   */
  public function getDebugDump()
  {
    return array
    (
      $this->projectKey
    );
  }//end public function getDebugDump */
  
  /**
   * @return string
   */
  public function dumpEnv()
  {
    
    if(  $this->activNode )
      return NL.$this->activNode->debugData().NL.Debug::backtrace().NL.NL;
    else 
      return 'No ENV?!? '.Debug::backtrace();
      
  }//end public function dumpEnv */


} // end class LibGenfBuild

