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
class LibGenfTreeNodeManagement
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var array
   */
  protected $categories = array();

  /**
   *
   * @var LibGenfTreeNodelistReferenceManagement
   */
  protected $references = null;

  /**
   *
   * @var array
   */
  protected $concepts   = array();

  /**
   *
   * @var array
   */
  protected $conceptKeys  = array();

  /**
   *
   * @var LibGenfTreeNodeEntity
   */
  public $entity        = null;

  /**
   *
   * @var LibGenfTreeNodeManagement
   */
  public $management    = null;

  /**
   *
   * @var LibGenfTreeNodeSemantic
   */
  public $semantic      = null;

  /**
   *
   * @var LibGenfTreeNodeManagementUi
   */
  public $ui            = null;

  /**
   *
   * @var LibGenfTreeNodeHeadManagement
   */
  public $head          = null;

  /**
   * @var LibGenfTreeNodeEventList
   */
  public $events        = null;

  /**
   *
   * @var array<LibGenfTreeNodeManagementProcess>
   */
  public $processes     = array();

  /**
   * @var LibGenfTreeNodeManagementDataProfile
   */
  public $dataProfile   = null;
  
  /**
   * @var string
   */
  public $type   = 'default';

  
/*//////////////////////////////////////////////////////////////////////////////
// load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->entity     = $this->builder->getEntity( trim($this->node['src']) );

    $this->name = new LibGenfNameManagement
    (
      $this->node,
      array( 'interpreter' => $this->builder->interpreter  )
    );
    
    if( isset( $this->node['type'] ) )
    {
      $this->type = trim($this->node['type']);
    }

    // only exists if subnode exists
    if( isset( $this->node->ui ) )
    {

      $uiClassName        = $this->builder->getNodeClass( 'ManagementUi' );
      
      /* @var $mgmtUi LibGenfTreeNodeManagementUi */
      $mgmtUi             = new $uiClassName( $this->node->ui );
      $mgmtUi->management = $this;
      $this->ui           = $mgmtUi;

      $entityUi = $this->entity->getUi();

      if( $entityUi )
      {

        $entityUi = clone $entityUi;
        $entityUi->management = $this;

        $mgmtUi->setFallback( $entityUi );
      }

    }

    $classRef         = $this->builder->getNodelistClass( 'ReferenceManagement' );
    $this->references = new $classRef
    (
      $this->node ,
      array
      (
        'name'        =>  trim($this->node['name']),
        'management'  =>  $this
      )
    );

    $this->references->setManagement( $this );


    // only exists if subnode exists
    if( isset( $this->node->semantic ) )
    {
      $semClassName     = $this->builder->getNodeClass( 'Semantic' );
      $this->semantic   = new $semClassName( $this->node->semantic );
    }

    if( isset( $this->node->head ) )
    {
      $this->head             = new LibGenfTreeNodeManagementHead( $this->node->head );
      $this->head->management = $this;
    }

    if( isset( $this->node->data_profile ) )
    {
      $this->dataProfile      = new LibGenfTreeNodeManagementDataProfile( $this->node->data_profile );
    }

    if( isset( $this->node->events ) )
    {
      $this->events             = new LibGenfTreeNodeEventList( $this->node->events );
    }
    elseif( $this->entity->events )
    {
      $this->events             = $this->entity->events;
    }

    if( !isset( $this->node->processes ) )
    {
      $this->processes = $this->entity->getProcesses();
    }
    else
    {
      $className  = $this->builder->getNodeClass( 'ManagementProcess' );

      foreach( $this->node->processes->process as $process )
        $this->processes[trim($process['name'])] = new $className( $process );
    }


    // only exists if subnode exists
    if( isset( $this->node->concepts->concept ) )
    {
      foreach( $this->node->concepts->concept as $concept )
      {
        $key = ucfirst( trim( $concept['name'] ) );

        $globalConcept = $this->builder->globalConcept(strtolower($key));

        // check if a concept is disabled
        if( false === $globalConcept )
          continue;

        $className = $this->builder->getNodeClass( 'Concept'.SParserString::subToCamelCase($key) );
        if( Webfrap::loadable($className) )
        {
          $this->concepts[strtolower($key)] = new $className( $concept );
        }
        else
        {
          Debug::console('missing node for concept '.$key);
          $this->concepts[strtolower($key)] = true;
        }
      }
    }

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function __toString()
  {
    return 'management: '.$this->name;
  }//end public function __toString */

////////////////////////////////////////////////////////////////////////////////
// categories
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param string $key
   * @param string $mgmtAction
   * @return boolean
   */
  public function shouldIRender( $key, $mgmtAction = null )
  {
    
    // wenn eine mgmt action mit übergeben wurde diese zuerst checken
    if( $mgmtAction )
    {
      $ui = $this->getUi();
      if( $ui && !$ui->mgmtAction( $mgmtAction ) )
      {
        return false;
      }
    }

    // prüfen ob wir in einem router sind und trotzdem rendern sollen
    $routeRenderListMap = array
    (
      'list',
      'selection'
    );
    
    if( in_array( $this->type, $routeRenderListMap ) )
    {
      if( $route = $this->concept( 'route' ) )
      {
        if( !$route->hasListElement( $this->type ) )
          return false;
      }
    }
    
    /*
    if( 'acl' == $key || 'acl_dset' == $key || 'acl_domain' == $key )
    {
      if( !( 'd-1' == $this->entity->relevance || 'd-2' == $this->entity->relevance )  )
        return false;
    }
    */
    
    // render only if this is the default node
    $mapOnlyDefNode = array
    (
      'maintenance'
    );
    
    if( in_array( $key, $mapOnlyDefNode  ) )
    {
      if( !$this->isDefaultNode() )
        return false;
    }
    
    // do not render on routes
    /*
    $mapNoRoute = array
    (
      'maintenance'
    );
    
    if( in_array( $key, $mapOnlyDefNode  ) )
    {
      if( !$this->isDefaultNode() )
        return false;
    }
    */
    
    // treetable spezifische checks
    if( 'treetable' == $key )
    {
      if( !$this->concept( 'Tree' ) )
        return false;
    
      if( $route = $this->concept( 'route' ) )
      {
        if( !$route->hasListElement( 'treetable' ) )
          return false;
      }
    }
    
    $relevanceMap = array
    (
    
      // domaindata
      'd-1' => array // haupt datensätze
      (
        'crud',
        'acl',
        'acl_dset',
        'acl_domain',
        'list',
        'table',
        'treetable',
        'selection',
        'maintenance',
        'export',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      'd-2' => array
      (
        'crud',
        'acl',
        'acl_dset',
        'acl_domain',
        'list',
        'table',
        'treetable',
        'selection',
        'maintenance',
        'export',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      'd-3' => array
      (
        'crud',
        'selection',
        'access_protocol_list',
      ),
      'd-3-m' => array
      (
        'crud',
        'selection',
        'access_protocol_list',
        'global_menu',
      ),
      
      // coredata
      'c-1' => array
      (
        'crud',
        'list',
        'table',
        'treetable',
        'selection',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      'c-2' => array
      (
        'crud',
        'selection',
        'access_protocol_list',
      ),
      
      // Systemdata mit eigenen acls
      's-1' => array
      (
        'crud',
        'acl',
        'acl_dset',
        'acl_domain',
        'list',
        'table',
        'export',
        'treetable',
        'selection',
        'maintenance',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      's-2' => array // sekundäre daten die über das window element zugewiesen werden
      (
        'crud',
        'list',
        'table',
        'treetable',
        'selection',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      's-2-s' => array // für types, status, categories und sonstige metadaten die 
      ( // vor allem über selectboxes oder autocomplete geladen werden
        'crud',
        'list',
        'table',
        'treetable',
        'global_menu'
      ),
      's-3' => array // crud & selection
      (
        'crud',
        'selection',
        'access_protocol_list'
      ),
      /**
       * Werden nur als referenzen eingebunden
       */
      's-3-r' => array 
      (
        'crud',
        'selection',
        'access_protocol_list',
      ),
      's-4' => array // nothing
      (
      
      ),
    );
    
    if( $this->entity->relevance )
    {
      if( isset( $relevanceMap[$this->entity->relevance]  ) )
      {
        if( !in_array( $key , $relevanceMap[$this->entity->relevance] ) )
        {
          return false;
        }
      }
    }
    
    
    // prüfen auf den type des managements
    // für manche management typen werden nicht alle features generiert
    $renderMap = array
    (
      'default' => array
      ( 
        'crud',
        'acl',
        'acl_dset',
        'acl_domain',
        'list',
        'table',
        'treetable',
        'selection',
        'maintenance',
        'export',
        'global_menu',
        'access_protocol_list',
        'access_protocol_dataset'
      ),
      'selection' => array
      ( 
        'selection'
      ),
      'viewer' => array
      ( 
        'crud'
      ),
      'meta' => array
      ( 
        'crud'
      ),
    );
    
    if( !isset( $renderMap[$this->type] ) )
    {
      $this->builder->dumpError( 'got unkown type render request '.$this->type , $this );
      return false;
    }
    
    // export nur rendern wenn als exportable geflaggt oder
    // wenn expliziet eine export sektion existiert
    if( 'export' === $this->type )
    {
      if( !$this->concept('exportable') && !$this->hasListUi( 'export' ) )
        return false;
    }
    
    return in_array( $key, $renderMap[$this->type] );
    
  }//end public function shouldIRender */
  
  /**
   * @return LibGenfTreeNodeEntity
   */
  public function getEntity()
  {
    
    return $this->entity;
  }//end public function getEntity */

  /**
   * @return int
   */
  public function countFields( $inCat = array(), $exclude = false )
  {
    
    return $this->entity->countFields( $inCat, $exclude );
  }//end public function countFields */

  /**
   * @return LibGenfTreeNodeSemantic
   */
  public function getSemantic()
  {
    
    return $this->semantic;
  }//end public function getSemantic */

  /**
   * @return LibGenfTreeNodeUi
   */
  public function getUi()
  {
    
    return $this->ui;
  }//end public function getUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function getListUi( $context )
  {
    
    if( !$this->ui )
      return null;

    return $this->ui->getListUi( $context );

  }//end public function getListUi */
  
  /**
   * @param string $context
   * @return LibGenfTreeNodeUiListing
   */
  public function hasListUi( $context )
  {
    
    if( !$this->ui )
      return null;

    return $this->ui->hasListUi( $context );

  }//end public function hasListUi */

  /**
   * @param string $context
   * @return LibGenfTreeNodeUiForm
   */
  public function getFormUi( $context )
  {

    if( !$this->ui )
      return null;

    return $this->ui->getFormUi( $context );

  }//end public function getFormUi */

  /**
   * @param string $context
   * @return boolean
   */
  public function hasFormContext( $context )
  {

    if( !$this->ui )
      return false;

    $formUi = $this->ui->getFormUi( $context );
    
    if( !$formUi )
      return false;
      
    return $formUi->hasContext( $formUi );
    
  }//end public function hasFormContext */
  
  /**
   * @return LibGenfTreeNodeHead
   */
  public function getHead()
  {

    if( $this->head  )
      return $this->head;
    else
      return $this->entity->head;

  }//end public function getHead */

  /**
   * @return LibGenfNameManagement
   */
  public function getName()
  {
    
    return $this->name;
  }//end public function getName */

  /**
   * @return boolean
   */
  public function isDefaultNode()
  {
    
    return ($this->name->name == $this->entity->name->name)?true:false;
  }//end public function isDefaultNode */

  /**
   * @return boolean
   */
  public function isRouter()
  {
    
    return false;
  }//end public function isRouter */


  /**
   * Der Type des Management Nodes
   * 
   * values:
   * 
   * default
   * 
   * @return string
   */
  public function getType()
  {
    
    if( !isset( $this->node['type'] ) )
    {
      return 'default';
    }
    else 
    {
      return trim( $this->node['type'] );
    }
    
  }//end public function getType */
  
  /**
   * Der Type des Management Nodes
   * 
   * values:
   * 
   * default
   * 
   * @return boolean
   */
  public function isType( $types )
  {
    
    if( !is_array( $types ) )
      $types = array( $types );
      
    $type = $this->getType();
   
    return in_array( $type, $types );
    
  }//end public function isType */
  
  /**
   * @return boolean
   */
  public function isSortable()
  {

    if( $this->entity->getAttribute('m_order') )
      return true;
    else
      return false;

  }//end public function isSortable */

  /**
   * Prüfen ob eine ACL Maske für diesen Management Knoten generiert werden soll
   * Wenn von einer anderen Maske Geerbt wird, dann wird keine eigene Maske benötigt
   *
   * @return boolean
   */
  public function hasAclMask()
  {

    // was auch immer angegeben wurde, für den default node wollen wir erst mal
    // IMMER eine maske, könnte sich später aber ändern
    if( $this->name->name == $this->entity->name->name )
      return true;

    return isset( $this->node->access['inherit'] )?false:true;

  }//end public function hasAclMask */

  /**
   *
   * @return array
   */
  public function getCategories()
  {

    if( !$this->categories )
    {
      if( isset( $this->node->categories['main'] ) )
      {
        $this->categories[(string)$this->node->categories['main']] = (string)$this->node->categories['main'];
      }
      else
      {
        $this->categories['default'] = 'default';
      }

      if( isset($this->node->categories->category) )
      {
        foreach( $this->node->categories->category as $cat )
        {
          $this->categories[trim($cat['name'])] = $cat;
        }
      }
    }

    return $this->categories;

  }//end public function getCategories */

  /**
   *
   * @return array
   */
  public function getSubCategories( $catname, $context )
  {

    $fields         = $this->getCategoryFields( $catname, $context );
    $subcategories  = array();

    foreach ( $fields as $field )
    {
      if( $subCats = $field->getSubCategories() )
      {
        foreach( $subCats as $key => $subcat )
        {
          $subcategories[$key] = $subcat;
        }
      }
    }

    return $subcategories;


  }//end public function getSubCategories */

  /**
   * @return array
   */
  public function getCategoryFields( $catname, $context = null )
  {

    $fields     = $this->getFields( $context, $catname );
    $catFields  = array();

    foreach ( $fields as $field )
    {
      if( $catname == $field->mainCategory( false ) )
        $catFields[] = $field;
    }

    return $catFields;

  }//end public function getCategoryFields */

  /**
   * @param string/array $checkCat
   */
  public function inCategory( $checkCat )
  {

    $categories = $this->getCategories();

    if( is_array($checkCat) )
    {
      foreach( $checkCat as $check )
      {
        if( isset($categories[$check]) )
        {
          return true;
        }
      }
    }

    return false;

  }//end public function inCategory */

  /**
   * @return array
   */
  public function getProfiles()
  {

    if( !isset( $this->node->profiles ) )
      return null;

    $profiles = array();

    foreach( $this->node->profiles as $profile )
      $profiles[trim($profile['name'])] =  $profile;


    return $profiles;

  }//end public function getProfiles */


  /**
   * @return array<LibGenfTreeNodeManagementProcess>
   */
  public function getProcesses()
  {

    return $this->processes;

  }//end public function getProcesses */

  /**
   * Den Access Node anfragen.
   * Wenn auf dem Management Node kein Access Node liegt wird
   * der Accessnode der Entity zurückgegeben
   *
   * @return LibGenfTreeNodeAccess
   */
  public function getAccess( )
  {

    if( !isset( $this->node->access ) )
    {
      $this->entity->getAccess();
    }

    $className            = $this->builder->getNodeClass( 'Access' );
    return new $className( $this->node->access, $this->entity );

  }//end public function getAccess */


  /**
   * @return LibGenfTreeNodeAccessLevel
   */
  public function getAccessLevel()
  {

    $node = null;

    if( isset($this->node->access->levels) )
      $node = $this->node->access->levels;

    $className = $this->builder->getNodeClass( 'AccessLevel' );

    return new $className( $node );

  }//end public function getAccessLevel */


  /**
   * @param string $name
   * @return LibGenfTreeNodeManagementService or null
   */
  public function getService( $name )
  {

    $service    = $this->node->xpath( './services/service[@name="'.$name.'"]' );

    if( !$service )
      return null;

    $className  = $this->builder->getNodeClass( 'ManagementService' );

    return new $className( $service[0] );

  }//end public function getService */

/*//////////////////////////////////////////////////////////////////////////////
// references
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return boolean
   */
  public function hasReferences( )
  {

    return $this->references->hasReferences();

  }//end public function hasReferences */

  /**
   * get all references
   * @return LibGenfTreenodelistReference
   */
  public function getReferences( )
  {

    return $this->references;

  }//end public function getReferences */

  /**
   * @param string $key
   * @param SimpleXmlElement $customNode
   *
   * @return LibGenfTreeNodeReference
   */
  public function getReference( $key, $customNode = null )
  {

    // check if the management has a own description for the reference
    if( $ref = $this->references->getReference( $key ) )
    {
      if( $customNode )
      {
        $ref = clone $ref;
        $ref->customize( $customNode );
        $ref->tabName = new LibGenfNameDefault( $customNode );
      }

      return $ref;
    }

    // request default reference from the entity
    $entRef = $this->entity->getReference( $key );

    if( !$entRef )
      return null;

    $entRef = clone $entRef;
    $entRef->management = $this ;

    $this->builder->warn( "REQUESTED entref ".$key.' in management '.$this->debugData().' '.Debug::backtrace() );

    if( $this->name->source != $this->name->name )
    {
      $entRef->setSrcMask( $this->name->name );
    }

    if( $customNode )
    {
      $entRef->customize( $customNode );
    }

    return $entRef;

  }//end public function getReference */

  /**
   * @param string $key
   *
   * @return boolean
   */
  public function referenceExists( $key )
  {

    return $this->references->referenceExists( $key );

  }//end public function referenceExists */

  /**
   * @return array
   */
  public function getMultiRefs( )
  {

    return $this->references->getMultiRefs();

  }//end public function getMultiRefs */

  /**
   * @return array
   */
  public function getSingleRefs( )
  {

    return $this->references->getSingleRefs();

  }//end public function getSingleRefs */

/*//////////////////////////////////////////////////////////////////////////////
// events
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $key
   */
  public function event( $key )
  {

    if( isset( $this->node->events->$key ) )
    {
      return trim($this->node->events->$key);
    }
    else
    {
      return null;
    }

  }//end public function event */


  /**
   * @param string $context
   */
  public function getEvent( $context )
  {

    if( isset( $this->node->events->$context ) )
    {
      return trim($this->node->events->$context);
    }
    else
    {
      return null;
    }

  }//end public function getEvent */

  /**
   * @param string $classType
   * @param string $method
   * @param string $on
   *
   * @return array<LibGenfTreeNodeEvent>
   */
  public function getEvents( $classType, $method, $on )
  {

    $events = $this->node->xpath( './events/event[@class="'.$classType.'" and @method="'.$method.'" and @on="'.$on.'"]' );

    if( !$events )
    {
      if( $this->events )
        return $this->events->getEvents( $classType, $method, $on );
      
      return array();
    }


    $className  = $this->builder->getNodeClass( 'Event' );

    $eventList = array();

    foreach( $events as $event )
    {
      $eventList[] = new $className( $event );
    }

    return $eventList;

  }//end public function getEvent */


  /**
   * @param string $key
   */
  public function ui( $key )
  {

    if( isset( $this->node->ui->$key ) )
    {
      return trim( $this->node->ui->$key );
    }
    else
    {
      return null;
    }

  }//end public function ui */

  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function concept( $key )
  {

    $key = strtolower($key);

    // if no local concept
    if( !isset($this->concepts[$key]) )
    {

      if( $concept = $this->entity->concept($key) )
        return $concept;

      // check for a global concept
      return $this->builder->globalConcept($key);
    }
    else
    {
      return $this->concepts[$key];
    }

  }//end public function concept

  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function getConcept( $key )
  {

    $key = strtolower($key);

    // if no local concept
    if( !isset($this->concepts[$key]) )
    {

      if( $concept = $this->entity->concept($key) )
        return $concept;

      // check for a global concept
      return $this->builder->globalConcept($key);
    }
    else
    {
      return $this->concepts[$key];
    }

  }//end public function getConcept

  /**
   * @param string $key
   * @return LibGenfTreeNode
   *
   */
  public function hasConcept( $key )
  {
    $key = strtolower($key);

    // if no local concept
    if( isset($this->concepts[$key]) )
    {
      return true;
    }

    if( $concept = $this->entity->concept($key) )
      return true;

    // check for a global concept
    if( is_null( $this->builder->globalConcept($key) ) )
      return false;
    else
      return true;


  }//end public function hasConcept

  /**
   *
   * @return array
   */
  public function getConceptKeys()
  {

    if( is_array( $this->conceptKeys ) )
      return $this->conceptKeys;

    $this->conceptKeys = array_unique
    (
      array_merge
      (
        array_keys($this->concepts),
        $this->builder->globalConceptKeys()
      )
    );

    return $this->conceptKeys;

  }//end public function getConceptKeys */

/*//////////////////////////////////////////////////////////////////////////////
// references
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $context
   * @param array $useCategories
   *
   * @TODO implement the use of $useCategories
   */
  public function getFormCategories( $context , $useCategories = null )
  {

    $catNodes   = array();
    $categories = $this->extractEntityCategories();

    $categoryClass      = $this->builder->getNodeClass( 'CategoryManagement' );
    $contextCategories  = new TArray();

    foreach( $categories as $catName => $category )
    {

      $mgmtCategory = new $categoryClass
      (
        $category->getNode(),
        null,
        array( 'category' => $category  )
      );

      $contextCategories[$catName] = $mgmtCategory;

    }//end foreach

    return $contextCategories;

  }//end public function getFormCategories */


  /**
   * @return array
   */
  public function extractEntityCategories()
  {

    $categories = $this->entity->getCategories();

    if( is_null($categories) )
      $categories = new TArray();


    if( $references = $this->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        $targetMgmt = $reference->targetManagement();

        if($entityCategories = $targetMgmt->entity->getCategories())
        {

          foreach( $entityCategories as $catName => $catObj )
          {

            if( !isset($categories[$catName]) )
              $categories[$catName] = $catObj;

          }

        }

      }//end foreach

    }//end if

    return $categories;

  }//end public function extractEntityCategories */

/*//////////////////////////////////////////////////////////////////////////////
// advanced getter
//////////////////////////////////////////////////////////////////////////////*/


  /**
   * @param string $context
   * @param string/array $category
   * @param boolean $noReference get fields should ignore fields from one to one References
   * @return array
   */
  public function getFields( $context = null, $category = null, $noReference = false  )
  {

    $cols   = array();
    $cols   = $this->appendCategoryFields( $cols, $this, $category );

    if( $noReference )
      return $cols;

    if( $references = $this->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        if( $context && $reference->exclude( $context ) )
        {
          continue;
        }

        $targetMgmt = $reference->targetManagement();
        $cols       = $this->appendCategoryFields( $cols, $targetMgmt, $category, $reference );

      }//end foreach
    }

    return $cols;

  }//end public function getFields */


  /**
   * Ein Attribute anhand des TargetKeys erfragen.
   *
   * Wenn mehrere Attribute auf das gleiche Target verweisen, und kein Alias
   * definiert wurde wird das erste Attribute das gefunden wurde zurückgegeben
   *
   * Das ist nicht zwangsläufig das erwartete, also immer schön den target key
   * pflegen
   *
   * @param string $targetKey
   * @return array
   */
  public function getFieldByTargetKey( $targetKey  )
  {

    foreach( $this->entity as $attr )
    {
      if( $targetKey == $attr->targetKey() )
      {
        return new TContextAttribute( $attr, $this );
      }
    }

    return null;

  }//end public function getFieldByTargetKey */

  /**
   * method to request fields in a specific context from the management
   * returns also fields from one to one references if no prevented by
   * $noReference = false
   *
   * @param string $context the requested context
   * @param boolean $asText should be returned as text or attribute node
   * @param boolean $noReference should the reference fields also be returned
   *
   * @return LibGenfTreenodelistAttribute
   */
  public function getContextFields( $context = 'text', $asText = true, $noReference = false  )
  {

    $fields   = array();
    $fields   = $this->appendContextFields( $fields, $this, $context );

    if( $noReference )
      return $fields;

    if( $references = $this->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        if( $context && $reference->exclude($context) )
        {
          continue;
        }

        $targetMgmt = $reference->targetManagement();
        $fields     = $this->appendContextFields( $fields, $targetMgmt, $context, $reference );

      }//end foreach
    }

    return $fields;

  }//end public function getContextFields */

  /**
   * @param string $context
   * @param string/array $category
   * @param LibGenfTreeNodeUiListing $uiList
   * @param array $additionalFields
   * @return array
   */
  public function getListingFields
  (
    $context   = null,
    $category  = null,
    $uiList    = null,
    $additionalFields = array()
  )
  {

    if( !$uiList )
    {
      if( $context )
        $uiList = $this->getListUi( $context );
    }

    $colorSource = null;
    if( $uiList )
    {
      $colorSource = $uiList->getColorSource();
    }

    // prüfen ob farbinformationen mitgejoint werden sollen
    if( $colorSource )
    {

      $colFieldName = $colorSource->getAttrField();

      if( !$attrSrc = $colorSource->getAttrSource()  )
      {
        $attrSrc = null;
      }

      $colRefField = $this->getField( $colFieldName, $attrSrc );

      if( !$colRefField )
      {
        $this->builder->error
        (
          "Requested Color Information over a nonexisting Field: {$colFieldName} "
            .$this->debugData().' '.__METHOD__
        );
      }
      else
      {

        if( $targetMgmt = $colRefField->targetManagement( ) )
        {

          $targetKey  = $colRefField->targetKey( );

          if( $bgField = $colorSource->getBackgroundField() )
          {
            if( $targetMgmt->hasField( $bgField ) )
            {
              $additionalFields[$targetKey][$bgField] = $bgField;
            }
            else
            {
              $this->builder->error
              (
                "Missing Missing Background Color Field: {$bgField} "
                  .$this->debugData().' '.__METHOD__
              );
            }
          }

          if( $textField = $colorSource->getTextField() )
          {
            if( $targetMgmt->hasField( $textField ) )
            {
              $additionalFields[$targetKey][$textField] = $textField;
            }
            else
            {
              $this->builder->error
              (
                "Missing Missing Text Color Field: {$textField} "
                  .$this->debugData().' '.__METHOD__
              );
            }
          }

          if( $borderField = $colorSource->getBackgroundField() )
          {
            if( $targetMgmt->hasField( $borderField ) )
            {
              $additionalFields[$targetKey][$borderField] = $borderField;
            }
            else
            {
              $this->builder->error
              (
                "Missing Missing Border Color Field: {$borderField} "
                  .$this->debugData().' '.__METHOD__
              );
            }
          }

        }
        else
        {
          $this->builder->error
          (
            "Missing the target Management for Color Information Field: {$colFieldName} "
              .$this->debugData().' '.__METHOD__
          );
        }
      }

    }


    if( $uiList )
    {
      if( $fields = $uiList->getFields( ) )
      {
        $cols = $this->sortListFields( $this, $fields, $uiList->context, $additionalFields );
        
        // sortierung hinzufügen
        $sortCols = $uiList->getSortCols();
        if( $sortCols )
        {
          foreach( $sortCols as /* @var LibGenfTreeNodeUiListOrderCol $sortCol */ $sortCol )
          {
            
            // by ref wird also benötigt
            $attr = $sortCol->getOrderAttributeByMgmt( $this );
            
            if( !$attr )
            {
              Debug::console( 'Did not get an order attribute' );
              continue;
            }
            
            if( $attr->ref )
            {
              $key = $attr->ref->name->name.'-'.$attr->name->name;
            }
            else if( $attr->namespace )
            {
              $key = $attr->namespace->name->name.'-'.$attr->name->name;
            }
            else
            {
              $key = $attr->management->name->name.'-'.$attr->name->name;
            }
            
            if( isset( $cols[$key] ) )
              continue;
            
            $attr->variante = 'by-sort';

            $cols[$key] =  $attr;
          }
        }
        
        return $cols;
      }
    }


    $cols   = array();
    $cols   = $this->appendContextFields( $cols, $this, $context );

    // zusätzliche felder hinzufügen
    if( $additionalFields )
    {
      foreach( $additionalFields as $source => $addFields )
      {
        foreach( $addFields as $addField )
        {
          if( $attr = $this->getField( $addField, $source ) )
          {

            if( $attr->ref )
            {
              $key = $attr->ref->name->name.'-'.$attr->name->name;
            }
            else if( $attr->namespace )
            {
              $key = $attr->namespace->name->name.'-'.$attr->name->name;
            }
            else
            {
              $key = $attr->management->name->name.'-'.$attr->name->name;
            }

            $attr->variante = 'def-additional';

            $cols[$key] =  $attr;

          }
        }
      }
    }

    if( $references = $this->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        if( $context && $reference->exclude( $context ) )
        {
          continue;
        }

        $targetMgmt = $reference->targetManagement();
        $cols       = $this->appendContextFields( $cols, $targetMgmt, $context, $reference );

      }//end foreach
    }

    return $cols;

  }//end public function getListingFields */

  /**
   * @param string $context
   * @param string/array $category
   * @param LibGenfTreeNodeUiForm $formUi
   * @return array
   */
  public function getFormFields( $context, $category = null, $formUi = null )
  {
  
    if( $formUi )
    {
      if( $fields = $formUi->getFields( ) )
      {

        if( !$fields )
        {
          if( !is_null( $fields ) )
          {
            $this->builder->dumpError( 'Form Layout without Fields' );
            return array();
          }
        }
        else
        {
          return $this->sortFields( $this, $fields,  $context );
        }

      }
    }
    else if( $formUi = $this->getFormUi( $context ) )
    {
      if( $fields = $formUi->getFields( ) )
      {

        if( !$fields )
        {
          if( !is_null( $fields ) )
          {
            $this->builder->dumpError( 'Form Layout without Fields' );
            return array();
          }
        }
        else
        {
          return $this->sortFields( $this, $fields,  $context );
        }

      }
    }

    $fields   = array();
    $fields   = $this->appendFormFields( $fields, $this, $context );

    if( $references = $this->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {

        $targetMgmt  = $reference->targetManagement();
        $fields      = $this->appendFormFields
        (
          $fields,
          $targetMgmt,
          $context,
          $reference
        );

      }//end foreach
    }

    return $fields;

  }//end public function getFormFields */

  /**
   * @param string $context
   * @param string/array $category
   * @param LibGenfTreeNodeUiForm $formUi
   * @return array
   */
  public function getSaveFields( $context, $category = null, $formUi = null )
  {

    if( $formUi )
    {
      if( $fields = $formUi->getSaveFields(  ) )
      {
        if( $fields )
        {
          return $this->sortFields( $this, $fields,  $context );
        }
      }
    }
    else if( $formUi = $this->getFormUi( $context ) )
    {
      if( $fields = $formUi->getSaveFields(  ) )
      {
        if( $fields )
        {
          return $this->sortFields( $this, $fields,  $context );
        }
      }
    }

    $fields   = array();
    $fields   = $this->appendFormFields( $fields, $this, $context );

    if( $references = $this->getSingleRefs( ) )
    {
      // append all tables
      foreach( $references as $reference )
      {

        $targetMgmt = $reference->targetManagement( );
        $fields     = $this->appendFormFields( $fields, $targetMgmt, $context, $reference );

      }//end foreach
    }

    return $fields;

  }//end public function getSaveFields */


  /**
   * @param string $context
   * @param boolean $free
   * @return array
   */
  public function getReadonlyFields( $context, $free = false )
  {

    if( $ui = $this->getFormUi( $context ) )
    {
      if( $fields = $ui->getReadonlyFields() )
      {
        return $this->extractSpecialFieldsMulti( $this, $fields, $context );
      }
    }

    return array();

  }//end public function getReadonlyFields */

////////////////////////////////////////////////////////////////////////////////
// get search cols
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $context
   * @param boolean $free
   * @return array
   */
  public function getSearchCols( $context, $free = false )
  {

    $management = $this;

    if( $ui = $this->getListUi( $context ) )
    {
      if( $fields = $ui->getSearchFields( ) )
      {
        return $this->extractSearchFields( $this, $fields, $context );
      }
    }

    $fields   = array();
    $fields   = $this->appendSearchCols( $fields, $this, $context, $free );

    if( $references = $this->getSingleRefs( ) )
    {

      // append all tables
      foreach( $references as $reference )
      {

        if( $reference->exclude( $context ) )
        {
          continue;
        }

        $targetMgmt   = $reference->targetManagement( );
        $fields       = $this->appendSearchCols( $fields, $targetMgmt, $context, $free );

      }//end foreach

    }//end if

    return $fields;

  }//end public function getSearchCols */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array<SimpleXmlElement> $fields
   * @param string $context
   */
  protected function extractSearchFields( $management, $fields, $context )
  {

    $cols   = array();

    foreach( $fields as $field )
    {
      if( isset( $field['ref'] )  )
      {
        //if($refMgmt = $this->builder->getRoot('Management')->getManagement( $src ))
        if( $refMgmt = $management->getReference( trim( $field['ref'] ) ) )
        {

          if( !$targetMgmt = $refMgmt->targetManagement()  )
          {
            $this->builder->error( "MGMT extract search field: got no target for ref: ".trim($field['ref']).' mgmt ' .$this );
            continue;
          }

          if( $refAttr = $targetMgmt->entity->getAttribute( trim($field['name']) ))
          {

            if( !isset( $cols[$targetMgmt->name->name] ) )
              $cols[$targetMgmt->name->name] = array();

            $attr    = new TContextAttribute( $refAttr, $targetMgmt, $context, $field );
            $attr->ref = $refMgmt;
            $cols[$targetMgmt->name->name][]  =  $attr;
          }
          else
          {
            $this->builder->error
            (
              "MGMT extract search field: reference: "
              .trim($field['ref']).' attribute: '.trim($field['name'])
              .' not exists mgmt: ' .$this
            );
            continue;
          }
        }
        else
        {
          $this->builder->error( "MGMT extract search field: reference: ".trim($field['ref']).' not exists mgmt: ' .$this );
          continue;
        }
      }      
      else if( isset( $field['src'] )  )
      {
        
        $srcKey = trim($field['src']);
        
        if( $refAttr = $management->entity->getAttrByTarget( $srcKey ) )
        {
          $targetMgmt = $refAttr->targetManagement();

          if( !isset( $cols[$srcKey] ) )
            $cols[$srcKey] = array();

          $attr    = new TContextAttribute( $refAttr, $management, $context, $field );
          $attr->target     = $targetMgmt;
          $cols[$srcKey][]  = $attr;
        }
        else
        {
          $this->builder->error
          (
            "MGMT extract search field: target: "
            .trim($field['src']).' attribute: '.trim($field['name'])
            .' not exists mgmt: ' .$this
          );
          continue;
        }
        
      }
      else
      {
        
        if( $attribute = $management->entity->getAttribute( trim($field['name']) ) )
        {
          if( !isset( $cols[$management->name->name] ) )
            $cols[$management->name->name] = array();

          $attr   = new TContextAttribute( $attribute, $management, $context );
          $cols[$management->name->name][] =  $attr;
        }
        else
        {
          $this->builder->error( "MGMT extract search field: attribute: ".trim($field['name']).' not exists mgmt: ' .$this );
          continue;
        }

      }
      
    }//end foreach

    return $cols;

  }//end protected function extractSearchFields */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array<SimpleXmlElement> $fields
   * @param string $context
   */
  protected function extractSpecialFieldsMulti( $management, $fields, $context )
  {

    $cols   = array();

    foreach( $fields as $field )
    {
      if( isset($field['ref'])  )
      {
        //if($refMgmt = $this->builder->getRoot('Management')->getManagement( $src ))
        if( $refMgmt = $management->getReference( trim( $field['ref'] ) ) )
        {
          if( $refAttr = $refMgmt->management->entity->getAttribute( trim($field['name'] ) ) )
          {

            if( !isset( $cols[$refMgmt->name->name] ) )
              $cols[$refMgmt->name->name] = array();

            $attr    = new TContextAttribute( $refAttr, $refMgmt, $context );
            $cols[$refMgmt->name->name][$refAttr->name->name]  =  $attr;
          }
        }
      }
      else
      {
        if( $attribute = $management->entity->getAttribute( trim( $field['name'] ) ) )
        {
          if(!isset($cols[$management->name->name]))
            $cols[$management->name->name] = array();

          $attr   = new TContextAttribute( $attribute, $management, $context );
          $cols[$management->name->name][$attr->name->name] =  $attr;
        }
      }
    }//end foreach

    return $cols;

  }//end protected function extractSpecialFieldsMulti */

  /**
   * @return LibGenfTreeNodeAttribute
   */
  public function getSearchBeginField()
  {

    foreach( $this->entity as $attribute )
    {
      if( $attribute->searchParam( 'begin' ) )
      {
        return $attribute;
      }
    }

    $refrences = $this->getSingleRefs();

    // append all tables
    if( !$refrences )
    {
      foreach( $refrences as $reference )
      {
        $targetEntity = $reference->targetManagement()->entity;

        foreach( $targetEntity as $attribute )
        {
          if( $attribute->searchParam( 'begin' ) )
          {
            return $attribute;
          }
        }
      }//end foreach
    }

    return null;

  }//end public function getSearchBeginField */

////////////////////////////////////////////////////////////////////////////////
// get search cols
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array $fields
   * @param string $context
   *
   * @return array<TContextAttribute>
   */
  protected function extractListCols( $management, $fields, $context )
  {

    $cols   = array();
    $rowIds = array();

    $attribute  = $management->entity->getAttribute( 'rowid' );
    $attr       = new TContextAttribute( $attribute, $management, $context );
    $cols[]     = $attr;

    foreach( $fields as $field )
    {
      if( $src = $field->src()  )
      {

        //if($refMgmt = $this->builder->getRoot('Management')->getManagement( $src ))
        if( $refMgmt = $management->getReference($src) )
        {
          if( $refAttr = $refMgmt->management->entity->getAttribute( $field->fieldName() ) )
          {
            $attr = new TContextAttribute( $refAttr, $refMgmt, $context );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $cols[]     =  $attr;
          }
        }
      }
      else
      {
        if( $attribute = $management->entity->getAttribute( $field->fieldName() ) )
        {
          $attr = new TContextAttribute( $attribute, $management, $context );
          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $cols[]     =  $attr;
        }
      }
    }//end foreach

    return $cols;

  }//end public function extractListCols */


  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array<LibGenfTreeNodeUiListField> $fields
   * @param string $context
   * @param array $additionalFields
   */
  protected function sortFields( $management, $fields, $context, $additionalFields = array() )
  {

    $cols   = array( );
    $rowIds = array( );

    $cols   = $this->appendContextFields( $cols, $management, 'text' );

    // additionalFields sind Felder die nicht aus dem Layout ausgelesen werden
    // sondern z.B durch Konzepte wie Tree ( parent id ) oder ColorSource
    // hinzugefügt werden
    // diese Felder können von den UI Feldern überschrieben, bzw customized werden
    // werden hier jedoch aufgeführt da sie zwingen nötig vorhanden sein müssen
    if( $additionalFields )
    {
      foreach( $additionalFields as $source => $addFields  )
      {
        foreach( $addFields as $addField )
        {

          // wenn die tabelle die quelle ist setzen wir den key auf null zurrück
          if( $attr = $management->getField( $addField, $source ) )
          {

            if( $attr->ref )
            {
              $key = $attr->ref->name->name.'-'.$attr->name->name;
            }
            else if( $attr->namespace )
            {
              $key = $attr->namespace->name->name.'-'.$attr->name->name;
            }
            else
            {
              $key = $attr->management->name->name.'-'.$attr->name->name;
            }


            $attr->variante = 'additional';

            $cols[$key] =  $attr;

          }
          else
          {
            $this->builder->error
            (
              "Missing requested Additional Field: {$addField} ".$this->debugData()
            );
          }
          
        }
      }
    }

    // extrahierte felder zum generieren anpassen
    foreach( $fields as /* @var  $field LibGenfTreeNodeUiListField */ $field )
    {

      // es könnte schon ein fertiges attribut hinterlegt sein
      // wenn eins da ist, wird einfach dieses genommen
      /* @var $attr LibGenfTreeNodeAttribute */
      $attr = $field->getField();
      if( $attr )
      {
        
        //Debug::console( "Existing ATTRIBUTE" );

        if( $attr->ref )
        {
          $key = $attr->ref->name->name.'-'.$attr->name->name;
        }
        else if( $attr->namespace )
        {
          $key = $attr->namespace->name->name.'-'.$attr->name->name;
        }
        else
        {
          $key = $attr->management->name->name.'-'.$attr->name->name;
        }

        if( $fieldName = $field->displayField( ) )
          $attr->fieldName = $fieldName;
          
        $fieldUiElem = $field->getUiElement();
        
        if( $fieldUiElem )
          $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $attr->attrUi, $fieldUiElem );
          
        $fieldLabel = $field->label();
        if( $fieldLabel )
        {
          $attr->label = $fieldLabel;
        }

        $attr->readOnly = $attr->uiElement->readonly();
        $attr->required = $attr->required()?true:$attr->uiElement->required();
        
        $defValue = $field->defaultValue();
        
        if( !is_null($defValue) )
          $attr->defaultValue = $defValue;
          
        $fieldAccess = $field->getAccess();
        if( $fieldAccess )
          $attr->access = $fieldAccess;
        
        $attr->variante = 'existing';

        $cols[$key] =  $attr;

        continue;
      }

      // hier werden alle felder mit dem "src" ( und dem veralteten "ref") Attribute
      // behandelt. Diese Felder liegen auf einer OneToOne Referenz
      if( $src = $field->reference( ) )
      {

        // if($refMgmt = $management-> $this->builder->getRoot('Management')->getManagement( $src ))
        // das one to one referenz objekt holen
        if( $refMgmt = $management->getReference( $src ) )
        {

          $targetMgmt = $refMgmt->targetManagement( );
          $fieldName  = $field->fieldName( );
          $origAttr   = $fieldName;

          // prüfen ob
          if( $fdName = $field->displayField( ) )
          {
            $fieldName = $fdName;
          }

          if( $refAttr = $targetMgmt->entity->getAttribute( $fieldName ) )
          {

            $attr = new TContextAttribute( $refAttr, $refMgmt, $context, $field );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $key = $refMgmt->name->name.'-'.$fieldName;
            $attr->ref = $refMgmt;
  
            $fieldUi = $field->getUiElement();
            
            if( $fieldUi  )
            {
              $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $refAttr->uiElement, $fieldUi );
            }
                
            $attr->readOnly = $attr->uiElement->readonly();
            $attr->required = $attr->required()?true:$attr->uiElement->required();
            
            $defValue = $field->defaultValue();
            
            if( !is_null($defValue) )
              $attr->defaultValue = $defValue;
            
            $fieldLabel = $field->label();
            if( $fieldLabel )
            {
              $attr->label = $fieldLabel;
            }
                
            $fieldAccess = $field->getAccess();
            if( $fieldAccess )
              $attr->access = $fieldAccess;

            $attr->variante = 'field_ref-ref-mgmt';

            $cols[$key] =  $attr;

          }
          else 
          {
            $this->builder->dumpError( "Requested nonexisting field_ref-ref-mgmt Attribute '{$fieldName}'" );
          }

        }//end if( $refMgmt = $management->getReference( $src ) )
        else if( $refAttr = $management->entity->getAttrByTarget( $src ) )
        {
          
          

          $targetMgmt = $refAttr->targetManagement();
          $fieldName  = $field->fieldName();

          if( $fdName = $field->displayField() )
          {
            $fieldName = $fdName;
          }

          if( $refAttr->name->name != $fieldName  )
            continue;

          $attr = new TContextAttribute( $refAttr, $targetMgmt, $context, $field );
          $attr->origTableKey     = $management->name->name;
          $attr->origTableSource  = $management->name->source;

          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $fieldUi = $field->getUiElement();
            
          if( $fieldUi  )
          {
            $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $refAttr->uiElement, $fieldUi );
          }
          
          $attr->readOnly = $attr->uiElement->readonly();
          $attr->required = $attr->required()?true:$attr->uiElement->required();
          
          $defValue = $field->defaultValue();
          
          if( !is_null($defValue) )
            $attr->defaultValue = $defValue;
          
          $fieldLabel = $field->label();
          if( $fieldLabel )
          {
            $attr->label = $fieldLabel;
          }
            
          $fieldAccess = $field->getAccess();
          if( $fieldAccess )
            $attr->access = $fieldAccess;

          $attr->variante = 'field_ref-ref-attr';

          $key = $targetMgmt->name->name.'-'.$fieldName;

          $cols[$key] =  $attr;
        }//end else if( $refAttr = $management->entity->getAttrByTarget( $src ) )
        else 
        {
          $this->builder->dumpError( "Requested nonexisting field_ref-ref-attr Attribute {$fieldName}" );
        }

      }//end if( $src = $field->reference( ) )
      else
      {
        
        $customFieldName = null;
        $customFieldKey  = null;
        
        // ein feld auf der Hauptentity / Hauptmanagement
        if( $fieldName = $field->displayField() )
        {

          if( $attribute = $management->entity->getAttribute( $field->fieldName() ) )
          {

            if( $targetMgmt = $attribute->targetManagement()  )
            {

              if( $trgtAttribute = $targetMgmt->entity->getAttribute( $fieldName ) )
              {
                
                $customFieldName = $fieldName;
                $customFieldKey  = '-'.$fieldName;

                $contextAttr = new TContextAttribute( $trgtAttribute, $targetMgmt, $context, $field );
                $fieldAction = $field->action();
                if( $fieldAction )
                {
                  $contextAttr->fieldAction = $fieldAction;
                }

                $contextAttr->variante = 'field_mgmt-field_attr';
   
                $fieldUi = $field->getUiElement();
                  
                if( $fieldUi  )
                {
                  $contextAttr->uiElement = new LibGenfTreeNodeUiElementMerger( $trgtAttribute->uiElement, $fieldUi );
                }
                
                $contextAttr->readOnly = $contextAttr->uiElement->readonly();
                $contextAttr->required = $contextAttr->required()?true:$contextAttr->uiElement->required();
                
                $defValue = $field->defaultValue();
                
                if( !is_null($defValue) )
                  $contextAttr->defaultValue = $defValue;
                
                $fieldLabel = $field->label();
                if( $fieldLabel )
                {
                  $contextAttr->label = $fieldLabel;
                }
                
                $fieldAccess = $field->getAccess();
                if( $fieldAccess )
                  $attr->access = $fieldAccess;

                $cols[$targetMgmt->name->name.'-'.$fieldName]     =  $contextAttr;

              }
              else 
              {
                $this->builder->dumpError( "Requested target from non target Attribute {$fieldName}" );
              }

            }
            else 
            {
              $this->builder->dumpError
              ( 
                'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context 
              );
            }

          }//end if( $attribute = $management->entity->getAttribute( $field->fieldName() ) )
          else
          {
            $this->builder->dumpError
            ( 
              'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context 
            );
          }

          $attr = new TContextAttribute( $attribute, $management, $context, $field );
          $attr->force      = true;
          $attr->variante   = 'field_mgmt-field';
          $attr->readOnly   = $field->isReadOnly();
          $attr->fieldName  = $customFieldName;
 
          $cols[$management->name->name.'-'.$attr->name->name.$customFieldKey] = $attr;

        }//end if( $fieldName = $field->displayField() )
        else
        {

          $fieldName = $field->fieldName();

          if( $attribute = $management->entity->getAttribute( $fieldName ) )
          {

            $attr = new TContextAttribute( $attribute, $management, $context, $field );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $attr->variante = 'field_mgmt-attr';
            
            $fieldUi = $field->getUiElement();
              
            if( $fieldUi  )
            {
              $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $attribute->uiElement, $fieldUi );
            }
            
            $attr->readOnly = $attr->uiElement->readonly();
            $attr->required = $attr->required()?true:$attr->uiElement->required();
            
            $defValue = $field->defaultValue();
            
            if( !is_null($defValue) )
              $attr->defaultValue = $defValue;
            
            $fieldLabel = $field->label();
            if( $fieldLabel )
            {
              $attr->label = $fieldLabel;
            }
            
            $fieldAccess = $field->getAccess();
            if( $fieldAccess )
              $attr->access = $fieldAccess;

            $cols[$management->name->name.'-'.$attr->name->name] = $attr;

          }
          else
          {
            $this->builder->dumpError
            (
              'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context
            );
          }

        }//end else

      }//end else

    }

    return $cols;

  }//end public function sortFields */

  
  /**
   * @param LibGenfTreeNodeManagement $management
   * @param array<LibGenfTreeNodeUiListField> $fields
   * @param string $context
   * @param array $additionalFields
   */
  protected function sortListFields( $management, $fields, $context, $additionalFields = array() )
  {

    $cols   = array( );
    $rowIds = array( );

    $cols   = $this->appendContextFields( $cols, $management, 'text' );

    // additionalFields sind Felder die nicht aus dem Layout ausgelesen werden
    // sondern z.B durch Konzepte wie Tree ( parent id ) oder ColorSource
    // hinzugefügt werden
    // diese Felder können von den UI Feldern überschrieben, bzw customized werden
    // werden hier jedoch aufgeführt da sie zwingen nötig vorhanden sein müssen
    if( $additionalFields )
    {
      foreach( $additionalFields as $source => $addFields  )
      {
        foreach( $addFields as $addField )
        {

          // wenn die tabelle die quelle ist setzen wir den key auf null zurrück
          if( $attr = $management->getField( $addField, $source ) )
          {

            if( $attr->ref )
            {
              $key = $attr->ref->name->name.'-'.$attr->name->name;
            }
            else if( $attr->namespace )
            {
              $key = $attr->namespace->name->name.'-'.$attr->name->name;
            }
            else
            {
              $key = $attr->management->name->name.'-'.$attr->name->name;
            }


            $attr->variante = 'additional';

            $cols[$key] =  $attr;

          }
          else
          {
            $this->builder->error
            (
              "Missing requested Additional Field: {$addField} ".$this->debugData()
            );
          }
          
        }
      }
    }

    // extrahierte felder zum generieren anpassen
    foreach( $fields as /* @var LibGenfTreeNodeUiListField */ $field )
    {

      // es könnte schon ein fertiges attribut hinterlegt sein
      // wenn eins da ist, wird einfach dieses genommen
      $attr = $field->getField();
      if( $attr )
      {
        
        //Debug::console( "Existing ATTRIBUTE" );

        if( $attr->ref )
        {
          $key = $attr->ref->name->name.'-'.$attr->name->name;
        }
        else if( $attr->namespace )
        {
          $key = $attr->namespace->name->name.'-'.$attr->name->name;
        }
        else
        {
          $key = $attr->management->name->name.'-'.$attr->name->name;
        }

        if( $fieldName = $field->displayField( ) )
          $attr->fieldName = $fieldName;
          
        $fieldUiElem = $field->getUiElement();
        
        if( $fieldUiElem )
          $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $attr->attrUi, $fieldUiElem );
          
        $fieldLabel = $field->label();
        if( $fieldLabel )
        {
          $attr->label = $fieldLabel;
        }

        $attr->readOnly = $attr->uiElement->readonly();
        $attr->required = $attr->uiElement->required();
        
        $defValue = $field->defaultValue();
        
        if( !is_null($defValue) )
          $attr->defaultValue = $defValue;
          
        $fieldAccess = $field->getAccess();
        if( $fieldAccess )
          $attr->access = $fieldAccess;
        
        $attr->variante = 'existing';

        $cols[$key] =  $attr;

        continue;
      }

      // hier werden alle felder mit dem "src" ( und dem veralteten "ref") Attribute
      // behandelt. Diese Felder liegen auf einer OneToOne Referenz
      if( $src = $field->reference( ) )
      {

        // if($refMgmt = $management-> $this->builder->getRoot('Management')->getManagement( $src ))
        // das one to one referenz objekt holen
        if( $refMgmt = $management->getReference( $src ) )
        {

          $targetMgmt = $refMgmt->targetManagement( );
          $fieldName  = $field->fieldName( );
          $origAttr   = $fieldName;

          // prüfen ob
          if( $fdName = $field->displayField( ) )
          {
            $fieldName = $fdName;
          }

          if( $refAttr = $targetMgmt->entity->getAttribute( $fieldName ) )
          {

            $attr = new TContextAttribute( $refAttr, $refMgmt, $context, $field );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $key = $refMgmt->name->name.'-'.$fieldName;
            $attr->ref = $refMgmt;
  
            $fieldUi = $field->getUiElement();
            
            if( $fieldUi  )
            {
              $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $refAttr->uiElement, $fieldUi );
            }
                
            $attr->readOnly = $attr->uiElement->readonly();
            $attr->required = $attr->uiElement->required();
            
            $defValue = $field->defaultValue();
            
            if( !is_null($defValue) )
              $attr->defaultValue = $defValue;
            
            $fieldLabel = $field->label();
            if( $fieldLabel )
            {
              $attr->label = $fieldLabel;
            }
                
            $fieldAccess = $field->getAccess();
            if( $fieldAccess )
              $attr->access = $fieldAccess;

            $attr->variante = 'field_ref-ref-mgmt';

            $cols[$key] =  $attr;
            
            if( 'file_image' == $field->uiElement() )
            {
              if( !isset( $cols[$refMgmt->name->name.'-rowid'] ) )
              {
                
                $refAttr = $targetMgmt->entity->getAttribute( 'rowid' );
                $attrRowid = new TContextAttribute( $refAttr, $refMgmt, $context, $field );
    
       
                $attrRowid->ref   = $refMgmt;
                $fieldLabel = $field->label();
                if( $fieldLabel )
                {
                  $attrRowid->label = $fieldLabel;
                }

                $attrRowid->variante = 'field_ref-ref-mgmt_rowid';

                $cols[$refMgmt->name->name.'-rowid'] =  $attrRowid;
              }
            }

          }
          else 
          {
            $this->builder->dumpError
            ( 
              "Requested nonexisting field_ref-ref-mgmt Attribute '{$fieldName}'" 
            );
          }

        }//end if( $refMgmt = $management->getReference( $src ) )
        else if( $refAttr = $management->entity->getAttrByTarget( $src ) )
        {
          
          

          $targetMgmt = $refAttr->targetManagement();
          $fieldName  = $field->fieldName();

          if( $fdName = $field->displayField() )
          {
            $fieldName = $fdName;
          }

          if( $refAttr->name->name != $fieldName  )
            continue;

          $attr = new TContextAttribute( $refAttr, $targetMgmt, $context, $field );
          $attr->origTableKey     = $management->name->name;
          $attr->origTableSource  = $management->name->source;

          if( $fieldAction = $field->action()  )
          {
            $attr->fieldAction = $fieldAction;
          }

          $fieldUi = $field->getUiElement();
            
          if( $fieldUi  )
          {
            $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $refAttr->uiElement, $fieldUi );
          }
          
          $attr->readOnly = $attr->uiElement->readonly();
          $attr->required = $attr->uiElement->required();
          
          $defValue = $field->defaultValue();
          
          if( !is_null($defValue) )
            $attr->defaultValue = $defValue;
          
          $fieldLabel = $field->label();
          if( $fieldLabel )
          {
            $attr->label = $fieldLabel;
          }
            
          $fieldAccess = $field->getAccess();
          if( $fieldAccess )
            $attr->access = $fieldAccess;

          $attr->variante = 'field_ref-ref-attr';

          $key = $targetMgmt->name->name.'-'.$fieldName;

          $cols[$key] =  $attr;
        }//end else if( $refAttr = $management->entity->getAttrByTarget( $src ) )
        else 
        {
          $this->builder->dumpError( "Requested nonexisting field_ref-ref-attr Attribute {$fieldName}" );
        }

      }//end if( $src = $field->reference( ) )
      else
      {
        
        $customFieldName = null;
        $customFieldKey  = null;
        
        // ein feld auf der Hauptentity / Hauptmanagement
        if( $fieldName = $field->displayField() )
        {

          if( $attribute = $management->entity->getAttribute( $field->fieldName() ) )
          {

            if( $targetMgmt = $attribute->targetManagement()  )
            {

              if( $trgtAttribute = $targetMgmt->entity->getAttribute( $fieldName ) )
              {
                
                $customFieldName = $fieldName;
                $customFieldKey  = '-'.$fieldName;

                $contextAttr = new TContextAttribute( $trgtAttribute, $targetMgmt, $context, $field );
                $fieldAction = $field->action();
                if( $fieldAction )
                {
                  $contextAttr->fieldAction = $fieldAction;
                }

                $contextAttr->variante = 'field_mgmt-field_attr';
   
                $fieldUi = $field->getUiElement();
                  
                if( $fieldUi  )
                {
                  $contextAttr->uiElement = new LibGenfTreeNodeUiElementMerger( $trgtAttribute->uiElement, $fieldUi );
                }
                
                $contextAttr->readOnly = $contextAttr->uiElement->readonly();
                $contextAttr->required = $contextAttr->uiElement->required();
                
                $defValue = $field->defaultValue();
                
                if( !is_null($defValue) )
                  $contextAttr->defaultValue = $defValue;
                
                $fieldLabel = $field->label();
                if( $fieldLabel )
                {
                  $contextAttr->label = $fieldLabel;
                }
                
                $fieldAccess = $field->getAccess();
                if( $fieldAccess )
                  $attr->access = $fieldAccess;

                $cols[$targetMgmt->name->name.'-'.$fieldName]     =  $contextAttr;

              }
              else 
              {
                $this->builder->dumpError( "Requested target from non target Attribute {$fieldName}" );
              }

            }
            else 
            {
              $this->builder->dumpError
              ( 
                'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context 
              );
            }

          }//end if( $attribute = $management->entity->getAttribute( $field->fieldName() ) )
          else
          {
            $this->builder->dumpError
            ( 
              'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context 
            );
          }

          $attr = new TContextAttribute( $attribute, $management, $context, $field );
          $attr->force      = true;
          $attr->variante   = 'field_mgmt-field';
          $attr->readOnly   = $field->isReadOnly();
          $attr->fieldName  = $customFieldName;
 
          $cols[$management->name->name.'-'.$attr->name->name.$customFieldKey] = $attr;

        }//end if( $fieldName = $field->displayField() )
        else
        {

          $fieldName = $field->fieldName();

          if( $attribute = $management->entity->getAttribute( $fieldName ) )
          {

            $attr = new TContextAttribute( $attribute, $management, $context, $field );
            if( $fieldAction = $field->action()  )
            {
              $attr->fieldAction = $fieldAction;
            }

            $attr->variante = 'field_mgmt-attr';
            
            $fieldUi = $field->getUiElement();
              
            if( $fieldUi  )
            {
              $attr->uiElement = new LibGenfTreeNodeUiElementMerger( $attribute->uiElement, $fieldUi );
            }
            
            $attr->readOnly = $attr->uiElement->readonly();
            $attr->required = $attr->uiElement->required();
            
            $defValue = $field->defaultValue();
            
            if( !is_null($defValue) )
              $attr->defaultValue = $defValue;
            
            $fieldLabel = $field->label();
            if( $fieldLabel )
            {
              $attr->label = $fieldLabel;
            }
            
            $fieldAccess = $field->getAccess();
            if( $fieldAccess )
              $attr->access = $fieldAccess;

            $cols[$management->name->name.'-'.$attr->name->name] = $attr;

          }
          else
          {
            $this->builder->dumpError
            (
              'Requested nonexisting attribute '.$field->fieldName().' from '.$management.' context: '.$context
            );
          }

        }//end else

      }//end else

    }

    return $cols;

  }//end public function sortListFields */

  /**
   * @param array $cols
   * @param LibGenfTreeNodeEntity $entity
   * @param string $context
   */
  protected function appendContextCols( $cols, $management, $context  )
  {

    foreach( $management->entity as $attribute )
    {

      if( $attribute->name->name == 'rowid'  )
      {
        $attr   = new TContextAttribute($attribute,$management);
        $cols[] = $attr;
      }

      // check if field type exists
      else if( $attribute->field( $context ) )
      {
        $attr   = new TContextAttribute($attribute,$management);
        $cols[] = $attr;
      }

    }

    return $cols;

  }//end public function appendContextCols */


  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param LibGenfTreeNodeReference $reference
   * @param boolean $ignoreRowid
   */
  protected function appendContextFields
  (
    $cols,
    $management,
    $context ,
    $reference = null,
    $ignoreRowid = false
  )
  {

    $mgmtKey  = $management->name->name;

    $found    = 0;
    $rowid    = null;

    $allowedContexts = array
    (
      'text'
    );

    foreach( $management->entity as $attribute )
    {

      $key = $mgmtKey.'-'.$attribute->name->name;

      if( $attribute->name->name == 'rowid'  )
      {
        $attr   = new TContextAttribute( $attribute, $management );
        $attr->variante = 'def-rowid';
        $rowid = $attr;

        if( $reference )
          $attr->ref = $reference;

      }

      // check if field type exists
      else if( $attribute->field( $context ) )
      {
        $attr   = new TContextAttribute( $attribute, $management );

        if( $reference )
          $attr->ref = $reference;

        $attr->variante = 'def-by-context';

        $cols[$key] = $attr;
        ++$found;
      }

      // textfields are always required
      else if( $context != 'text' && $attribute->field( 'text' ) && !$reference )
      {
        $attr   = new TContextAttribute( $attribute, $management );
        $attr->implicit = 'text';

        $attr->variante = 'def-by-text';


        if($reference)
          $attr->ref = $reference;


        $cols[$key] = $attr;
        ++$found;
      }

    }

    // check if the rowid should be ignored
    if( !$ignoreRowid )
    {
      if( !$reference || ($found && $rowid) )
        $cols[$mgmtKey.'-rowid'] = $rowid;
    }

    return $cols;

  }//end protected function appendContextFields */

  /**
   *
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $catName
   * @param LibGenfTreeNodeReference $reference
   */
  protected function appendCategoryFields( $cols, $management, $catName, $reference = null )
  {

    $mgmtKey = $management->name->name;

    $found  = 0;
    $rowid  = null;

    foreach( $management->entity as $attribute )
    {

      $key = $mgmtKey.'-'.$attribute->name->name;

      // check if field type exists
      if( $catName == $attribute->mainCategory( ) )
      {
        $attr   = new TContextAttribute($attribute,$management);

        if($reference)
          $attr->ref = $reference;

        $cols[$key] = $attr;
        ++$found;
      }

    }

    return $cols;

  }//end protected function appendCategoryFields */

  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param LibGenfTreeNodeReference $reference
   */
  protected function appendFormFields( $cols, $management, $context, $reference = null  )
  {

    if( $reference )
    {
       $mgmtKey = $reference->name->name;
    }
    else
    {
      $mgmtKey = $management->name->name;
    }

    foreach( $management->entity as $attribute )
    {

      $key        = $mgmtKey.'-'.$attribute->name->name;
      $attr       = new TContextAttribute($attribute,$management);

      if( $reference )
        $attr->ref = $reference;

      $cols[$key] = $attr;

    }

    return $cols;

  }//end public function appendFormFields */

  /**
   * @param string $key
   * @param string $refKey
   * @param LibGenfEnv $env wird zum verfeinern der debug ausgabe verwendet
   *
   * @return TContextAttribute
   */
  public function getField( $key, $refKey = null, $env = null )
  {

    if( $refKey && $this->name->name != $refKey )
    {

      if( $this->referenceExists( $refKey ) )
      {

        $reference      = $this->getReference( $refKey );

        $refManagement  = $reference->targetManagement( );
        $attribute      = $refManagement->entity->getAttribute( $key );

        if( !$attribute )
        {
          $this->error
          (
            'MGMT Get Field: requested nonexisting field : '
              .$key.' by refkey: '.$refKey.' in '.$this.' env: '.$env.' '.Debug::backtrace()
          ); //. Debug::backtrace()
          return null;
        }

        $context = new TContextAttribute( $attribute, $refManagement );
        $context->ref = $reference;

        return $context;
      }
      else if( $attr = $this->getFieldByTargetKey( $refKey ) )
      {
        $refManagement  = $attr->targetManagement( );

        if( !$refManagement )
        {
          $this->error
          (
            'MGMT Get Field: field targetmanagement not exists : '
              .$key.' by target refkey: '.$refKey.' in '.$this.' env: '.$env.' '.Debug::backtrace()
          ); //. Debug::backtrace()
          return null;
        }

        $attribute      = $refManagement->entity->getAttribute( $key );

        if( !$attribute )
        {
          $this->error
          (
            'MGMT Get Field: requested nonexisting target field : '
              .$key.' by refkey: '.$refKey.' in '.$refManagement.' env: '.$env.' '.Debug::backtrace()
          ); //. Debug::backtrace()
          return null;
        }

        $context = new TContextAttribute( $attribute, $refManagement );
        $context->namespace = new LibGenfNamespace( $refKey ) ;

        return $context;
      }
      else
      {
        $this->error
        (
          'MGMT Get Field: requested nonexisting reference : '
            .$refKey.' in key: '.$key.' in '.$this.' env: '.$env.' '.Debug::backtrace()
        ); //. Debug::backtrace()

        return null;
      }

    }

    if( $attribute = $this->entity->getAttribute( $key ) )
    {
      return new TContextAttribute( $attribute, $this );
    }
    else
    {
      $this->error
      (
        'MGMT Get Field: requested nonexisting field: '
          .$key.' no refkey in '.$this.' '.$this->builder->dumpEnv().' '.$this->entity->debugData()
      ); // . Debug::backtrace()

      return null;
    }


  }//end public function getField */


  /**
   * prüfen ob ein bestimmtes Feld existiert
   *
   * @param string $key
   * @param string $refKey
   * @return boolean
   */
  public function hasField( $key, $refKey = null )
  {

    if( $refKey && $this->name->name != $refKey  )
    {
      if( !$reference = $this->getReference( $refKey ) )
        return false;

      $refManagement  = $reference->targetManagement();

      if( $attribute  = $refManagement->entity->getAttribute( $key ) )
      {
        return true;
      }
      else
      {
        return false;
      }

      return $context;
    }

    if( $attribute = $this->entity->getAttribute( $key ) )
    {
      return true;
    }
    else
    {
      return false;
    }


  }//end public function hasField */


  /**
   * @param array $cols
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   * @param boolen $free sollen normale search attribute oder freesearch attribute zurückgegeben werden
   *
   * @return array<name:<int:TContextAttribute>>
   */
  protected function appendSearchCols( $cols, $management, $context, $free  )
  {

    foreach( $management->entity as $attribute )
    {

      if( $free )
      {
        if( !$attribute->searchFree( ) )
          continue;
      }
      else
      {
        if( !$attribute->search() )
        {
          continue;
        }
      }

      if( $attribute->name->name == 'rowid'  )
      {
        $attr   = new TContextAttribute( $attribute, $management );

        if( !isset($cols[$management->name->name]) )
          $cols[$management->name->name] = array();

        $cols[$management->name->name][] = $attr;
      }

      // check if field type exists
      else if( $attribute->field( $context ) )
      {
        $attr = new TContextAttribute($attribute,$management);

        if(!isset($cols[$management->name->name]))
          $cols[$management->name->name] = array();

        $cols[$management->name->name][] = $attr;
      }
    }

    return $cols;

  }//end public function appendSearchCols */


/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param string $context
   * @param LibGenfTreeNodeUiListing $listUi
   *
   * @return array
   */
  public function getFieldSources( $context, $listUi = null )
  {

    $management = $this;

    $defJoins = array();

    // prüfen ob farbinformationen mitgejoint werden sollen
    if( $listUi && $colorSource = $listUi->getColorSource() )
    {

      $colFieldName = $colorSource->getAttrField();

      if( !$attrSrc = $colorSource->getAttrSource()  )
      {
        $attrSrc = null;
      }

      $colRefField = $this->getField( $colFieldName, $attrSrc );

      if( !$colRefField )
      {
        $this->builder->error
        (
          "Requested Color Information over a nonexisting Field: {$colFieldName} "
          .$this->debugData().' '.__METHOD__
        );
      }
      else
      {

        if( $targetMgmt = $colRefField->targetManagement( ) )
        {

          $targetKey  = $colRefField->targetKey( );
          $defJoins[] = $targetKey;

        }
        else
        {
          $this->builder->error
          (
            "Missing the target Management for Color Information Field: {$colFieldName} "
              .$this->debugData().' '.__METHOD__
          );
        }

      }

    }

    if( $listUi )
    {
      if( $fields = $listUi->getFields( ) )
      {
        return $this->extractFieldSources( $management, $fields, $listUi->context );
      }

      if(!is_null($fields))
        return array();
    }
    else if( $listUi = $management->getListUi( $context ) )
    {
      if( $fields = $listUi->getFields(  ) )
      {
        return $this->extractFieldSources( $management, $fields,  $context );
      }

      if( !is_null( $fields ) )
        return array();

    }

    $index   = array();
    $index   = $this->appendContextTables( $index, $management, $context );

    if( 'text' !=  $context  )
    {
      $index   = $this->appendContextTables( $index, $management, 'text' );
    }

    if( $references = $management->getSingleRefs() )
    {
      // append all tables
      foreach( $references as $reference )
      {
        // in manchen contexten kann eine referenz ignoriert werden
        if( $reference->exclude($context) )
          continue;

        $targetMgmt = $reference->targetManagement();
        $index      = $this->appendRefContextTables( $index, $targetMgmt, $context );

      }//end foreach
    }

    return $index;

  }//end public function getFieldSources */


  /**
   * @param LibGenfTreeNodemanagement $management
   * @param array $fields
   * @param string $context
   * @param array $defJoins Liste mit den Standard Joins
   */
  protected function extractFieldSources( $management, $fields, $context, $defJoins = array() )
  {

    $name           = $management->getName();

    $tables         = new TTabJoin();
    $tables->table  = $name->source;
    $index          = array();

    if( 'text' != $context )
      $index   = $this->appendContextTables( $index, $management, 'text' );

    foreach( $defJoins as $defJoin )
    {
      $index[$defJoin] = true;
    }

    foreach( $fields as $field )
    {

      if( $src = $field->src()  )
      {

        //if($refMgmt = $this->builder->getRoot('Management')->getManagement( $src ))
        if($fieldRef = $management->getReference($src))
        {

          if($refMgmt = $fieldRef->targetManagement())
          {
            $index[$refMgmt->name->name] = true;

            if($refAttr = $refMgmt->entity->getAttribute( $field->fieldName() ))
            {
              if( $target = $refAttr->targetKey() )
              {
                $index[$target] = true;
              }
            }
          }

        }
        else
        {
          $this->error
          (
            'did not find a reference:'.$src.' field:'.$field->fieldName()
              .' on '.$management->name->name.' in '.__METHOD__
          );
        }

      }//end if( $src = $field->src()  )
      else
      {
        if($attribute = $management->entity->getAttribute( $field->fieldName() ))
        {
          if( $target = $attribute->targetKey() )
          {
            $index[$target] = true;
          }
        }
      }

    }//end foreach

    return $index;

  }//end protected function extractFieldSources */

  /**
   * @param array $cols
   * @param LibGenfTreeNodeEntity $entity
   * @param string $context
   */
  protected function appendContextTables( $index, $management, $context  )
  {

    $tmp    = array();

    foreach( $management->entity as $attribute )
    {
      // check if field type exists
      if( $attribute->field( $context ) )
      {
        if( $targetKey = $attribute->targetKey( $context ) )
        {
          $tmp[] = $targetKey;
        }
      }
      else if( $context != 'text' && $attribute->field( 'text' ) )
      {
        if( $targetKey = $attribute->targetKey( $context ) )
        {
          $tmp[] = $targetKey;
        }
      }
    }

    if( $tmp )
    {
      foreach( $tmp as $tmpKey )
      {
        $index[$tmpKey] = true;
      }
    }

    return $index;

  }//end public function appendContextTables */

 /**
   * @param array $cols
   * @param LibGenfTreeNodeEntity $entity
   * @param string $context
   */
  protected function appendRefContextTables( $index, $management, $context  )
  {

    $tmp    = array();
    $join   = false;

    foreach( $management->entity as $attribute )
    {
      // check if field type exists
      if( $attribute->field( $context ) )
      {
        if( $targetKey = $attribute->targetKey( $context ) )
        {
          $tmp[] = $targetKey;
        }

        $join = true;
      }
      else if( $context != 'text' && $attribute->field( 'text' ) )
      {
        if( $targetKey = $attribute->targetKey( 'text' ) )
        {
          $tmp[] = $targetKey;
        }

        $join = true;
      }
    }

    if( $join )
    {
      $index[$management->name->source] = true;

      if( $tmp )
      {

        foreach( $tmp as $tmpKey )
        {
          $index[$tmpKey] = true;
        }

      }

    }

    return $index;

  }//end public function appendRefContextTables */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * parse the tables
   * @param string $context
   * @param LibGenfTreeNodeUiListing $uiList
   * @return TTabJoin
   */
  public function getTables( $context, $uiList = null )
  {

    $management     = $this;
    $name           = $this->getName();

    $tables         = new TTabJoin();
    $tables->table  = $name->source;
    $tables->index[$tables->table] = true;

    if( !$uiList )
    {
      if( $context )
        $uiList = $this->getListUi( $context );
    }

    // prüfen ob farbinformationen mitgejoint werden sollen
    if( $uiList && $colorSource = $uiList->getColorSource() )
    {

      $colFieldName = $colorSource->getAttrField();

      if( !$attrSrc = $colorSource->getAttrSource()  )
      {
        $attrSrc = null;
      }

      $colRefField = $this->getField( $colFieldName, $attrSrc );

      if( !$colRefField )
      {
        $this->builder->error
        (
          "Requested Color Information over a nonexisting Field: {$colFieldName} "
          .$this->debugData().' '.__METHOD__
        );
      }
      else
      {

        if( $targetMgmt = $colRefField->targetManagement( ) )
        {

          $targetKey = $colRefField->targetKey( );

          $tables->index[$targetKey] = true;

        }
        else
        {
          $this->builder->error
          (
            "Missing the target Management for Color Information Field: {$colFieldName} "
              .$this->debugData().' '.__METHOD__
          );
        }

      }

    }

    $this->appendAttributeReferenceTables( $tables, $management, $context );

    if( 'text' != $context )
    {
      $this->appendAttributeReferenceTables( $tables, $management, 'text' );
    }

    // check if there are any references
    if( !$references = $management->getSingleRefs()  )
      return $tables;

    // else
    foreach( $references as $reference )
    {
      $this->appendReferenceTables( $tables, $reference, $context );

      if( 'text' != $context )
      {
        $this->appendReferenceTables( $tables, $reference, 'text' );
      }

    }//end foreach

    return $tables;

  }//end protected function getQueryTables */

  /**
   * @param TTabJoin $tables
   * @param LibGenfTreeNodeManagement $management
   * @param string $context
   */
  protected function appendAttributeReferenceTables( $tables, $management, $context )
  {

    $name   = $management->getName();
    $entity = $management->getEntity();

    $code = '';

    foreach( $entity as $attribute )
    {
      // wenn keine quelle vorhanden ist oder eine bereits vorhandene referenz
      // verwendet werden soll weitermachen ref mappt auf vorhandenen oneTo* referenz

      $attrName   = $attribute->name();
      $entityName = $entity->name();

      // wenn eingebunden dann wird der join nochmal als referenz erstellt
      // daher wird dieser join hier ignoriert
      if( $attribute->isEmbeded() )
        continue;

      if( $paths = $attribute->sourcePath( $name, $context) )
      {
        foreach( $paths as $path )
        {

          $tables->joins[] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference '.$name->name
          );

          $tables->index[trim($path['targetAlias'])] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference '.$name->name
          );

        }//end foreach

      }//end if
      else if( 'text' != $context && $paths = $attribute->sourcePath( $name, 'text') )
      {
        foreach( $paths as $path )
        {

          $tables->joins[] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference '.$name->name
          );

          $tables->index[trim($path['targetAlias'])] = array
          (
            'left',                     // join
            trim($path['src']),
            trim($path['targetField']),
            trim($path['target']),
            trim($path['srcField']),
            null,                       // where
            trim($path['targetAlias']),  // alias
            'attribute reference '.$name->name
          );

        }//end foreach

      }//end if

    }//end foreach

    return $code;

  }//end protected function appendAttributeReferenceTables */

  /**
   * @param TTabJoin $tables
   * @param LibGenfTreeNodeReference $rRef
   * @param string $context
   */
  protected function appendReferenceTables( $tables, $rRef, $context )
  {

    // nur one to referenzen werden betrachtet
    if( !$rRef->relation( 'one' ) )
      return;

    $refName      = $rRef->name;

    $nameSrc      = $rRef->src();
    $nameTarget   = $rRef->target();

    $targetId     = $rRef->targetId( );
    $srcId        = $rRef->srcId( );

    if( $rRef->preSave( ) )
    {

      $tables->joins[] = array
      (
        'left',                     // join
        $nameSrc->source,
        $targetId,
        $nameTarget->source,
        $srcId,
        null,                       // where
        null,                       // alias
        'one to one pre save '.$refName->name    // comment
      );

      $tables->index[$nameTarget->source] = array
      (
        'left',                     // join
        $nameSrc->source,
        $targetId,
        $nameTarget->source,
        $srcId,
        null,                       // where
        null,                       // alias
        'one to one pre save '.$refName->name    // comment
      );

      //$code .= "    \$criteria->leftJoinOn( '".$nameTarget->source.".'.Db::PK, '".$nameSrc->source.".".$srcId."' ); //{$rRef->relation()} pre".NL;
    }
    else
    {

      $tables->joins[] = array
      (
        'right',                     // join
        $nameSrc->source,
        $srcId,
        $nameTarget->source,
        $targetId,
        null,                       // where
        null,                       // alias
        'one to one post save '.$refName->name   // comment
      );

      $tables->index[$nameTarget->source] = array
      (
        'right',                     // join
        $nameSrc->source,
        $srcId,
        $nameTarget->source,
        $targetId,
        null,                       // where
        null,                       // alias
        'one to one post save '.$refName->name   // comment
      );

      //$code .= "    \$criteria->rightJoinOn( '".$nameTarget->source.".".$srcId."', '".$nameSrc->source.".'.Db::PK ); //{$rRef->relation()} post".NL;
    }

    // parse the reference display fields with another table as target
    $this->appendAttributeReferenceTables( $tables, $rRef->targetManagement(), $context  );


  }//end protected function appendReferenceTables */



}//end class LibGenfTreeNodeManagement

