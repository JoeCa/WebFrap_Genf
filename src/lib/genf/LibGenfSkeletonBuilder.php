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
class LibGenfSkeletonBuilder
  extends LibGenfBuild
{
  
  /**
   * default constructor
   * creates a default instance if not yet set
   */
  public function __construct()
  {

    if( !static::$instance  )
      static::$instance = $this;

    // if lib bdl Parser is availabe load
    if( Webfrap::classLoadable( 'LibBdl' ) )
      $this->bdlRegistry = new LibBdl( $this );

    $this->protocol = new LibGenfProtocol();

  }//end public function __construct */

  /**
   * @param array $modelPaths
   * @return void
   */
  public function buildSkeletonTree( $modelPaths )
  {

    $this->tree = LibGenfTree::init( $this, $this->arch, $this->archVersion );
    
    $defCartridges = array
    (
      'entity'     => 'entity',
      'module'     => 'module',
      'management' => 'management',
      'component'  => 'component',
      'profile'    => 'profile',
      'desktop'    => 'desktop',
      'page'       => 'page',
      'widget'     => 'widget',
      'menu'       => 'menu',
      'service'    => 'service',
      'message'    => 'message',
      'process'    => 'process',
      'action'     => 'action',
      'role'       => 'role',
      'event'      => 'event',
      'action'     => 'action',
      'item'       => 'item',
      'enum'       => 'enum',
      'docu'       => 'docu',
    );
    

    $loadedCartTypes = $this->projectNode->getCartridgeTypes( $defCartridges );
    $this->tree->setCartridgeTypes( $loadedCartTypes );

    $this->tree->createTree( $modelPaths );
      

  }//end protected function buildTree */
  
  /**
   * @param string $deployPath
   * @return void
   */
  public function buildSkeleton( $deployPath )
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
   * @param SimpleXmlElement $project the xml project description
   * @param string $deployPath 
   */
  public function loadSkeletonProject( $project, $deployPath )
  {

    $this->parseVars( $project );
    
    $this->pathOutput   = $deployPath;
    
    $this->projectPath  = $deployPath;
    
    $this->project      = $project;

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

    if( isset( $project->sandbox ) && 'false' == trim($project->sandbox) )
    {
      $this->sandbox = false;
    }

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
    
  }//end public function loadSkeletonProject */
  

} // end class LibGenfSkeletonBuilder

