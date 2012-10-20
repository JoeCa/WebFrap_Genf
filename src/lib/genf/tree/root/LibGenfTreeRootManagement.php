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
class LibGenfTreeRootManagement
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @var SimpleXmlElement
   */
  public $management    = null;

  /**
   * @var LibGenfTreeRootEntity
   */
  public $rootEntity    = null;

  /**
   * @var LibGenfTreeRootComponent
   */
  public $rootComponent = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create the nodeRoot for the managements
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/managements';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('managements');
      $this->modelRoot->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */


  /**
   *
   */
  public function postProcessing()
  {
    
    $modelXpath = $this->tree->getXpath();
    $references  = $modelXpath->query( '/bdl/managements/management/references/ref' );
    // <ref target="code_author"     relation="manyToMany" />
    foreach( $references as $ref )
    {
      $this->postProcessReferencesGuessType( $ref, $modelXpath );
    }//end foreach
    
    
    $this->postCopyEditToShowForm();
    
    foreach( $this->nodes as $management )
    {
      
      // wenn ein management eine route ist, und zurück auf den router verweist
      // müssen die Metadaten für listenaufrufe geändert werden
      if( $routeConcept = $management->concept( 'route' ) )
      {
        
        if( $mainRoute = $routeConcept->getMainRouteName() )
        {
          $targetMgmt = $this->getManagement( $mainRoute );
          
          $management->name->classList     = $targetMgmt->name->class;
          $management->name->nameList      = $targetMgmt->name->name;
          $management->name->classUrlList  = $targetMgmt->name->classUrl;
          $management->name->moduleList    = $targetMgmt->name->module;
          $management->name->i18nTextList  = $targetMgmt->name->i18nText;
          $management->name->i18nMsgList   = $targetMgmt->name->i18nMsg;
        }
        
      }
      
    }
    
  }//end public function postProcessing */

  /**
   * @param DOMElement $ref
   * @param DOMXpath $modelXpath
   */
  protected function postProcessReferencesGuessType( $ref, $modelXpath )
  {

    $node   = simplexml_import_dom( $ref );

    $nodeManagement = $this->tree->getRootNode( 'Management' );
    
    $tmpList = $modelXpath->query('./connection', $ref);
    
    if( !isset( $node['relation'] ) )
    {
      if( !$tmpList->length )
      {
        $ref->setAttribute( 'relation', 'manyToOne' );
      }
      else 
      {
        $ref->setAttribute( 'relation', 'manyToMany' );
      }
    }
    
    if( !isset( $node['binding'] ) )
    {
      $ref->setAttribute( 'binding', 'connected' );
    }
    

  }//end protected function postProcessAllReferences */
  
  /**
   * 
   */
  public function postCopyEditToShowForm()
  {
    
    $modelXpath    = $this->tree->getXpath();
    $formElements  = $modelXpath->query( '/bdl/managements/management/ui/form' );
    
    
    // <ref target="code_author"     relation="manyToMany" />
    foreach( $formElements as $formEle )
    {
      
      $simpleEle = simplexml_import_dom( $formEle );
      
      if( isset( $simpleEle->edit ) && !isset( $simpleEle->show ) )
      {
        
        $innerCode = '';
        
        foreach( $simpleEle->edit->children() as $cNode )
        {
          $innerCode .= $cNode->asXml();
        }
        
        $innerCode = '<show>'.$innerCode.'</show>';
        
        $this->stringToNode( $innerCode,  $formEle );
        
      }

    }//end foreach
    
  }//end public function postCopyEditToShowForm */
  
  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath , $repoPath = null  )
  {

    $nodeItem    = $this->tree->getRootNode( 'Item' );
    
    $this->builder->activRepo = $repoPath;


    $query       = '/bdl/managements/management';
    
    $queryItems  = '/bdl/managements/management/ui/form//layout//item';

    $tmpXpath   = new DOMXpath($tmpXml);
    $listNew    = $tmpXpath->query( $query );
    $modelXpath = $this->tree->getXpath();

    $managements = array();

    foreach( $listNew as $node )
    {
      $managements[] = $node;
    }

    foreach( $managements as $node )
    {
      $this->add( $node );
    }
    
    // item
    $listFoundItems    = $tmpXpath->query( $queryItems );
    
    foreach( $listFoundItems as $item )
    {
      $nodeItem->addItemNode( $item->cloneNode( true ) );
    }
    
    
  }//end public function importFile */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNode#createIndex()
   */
  public function createIndex()
  {

    // append default attributes to the entity
    $modelXpath     = $this->tree->getXpath();
    $nodeList       = $modelXpath->query( '/bdl/managements/management' );

    if(!$className  = $this->builder->getNodeClass( 'Management' ))
    {
      throw new LibGenfTree_Exception( 'Got no Node for Management' );
    }

    $this->rootEntity     = $this->tree->getRootNode( 'Entity' );
    $this->rootComponent  = $this->tree->getRootNode( 'Component' );

    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);

      // parse Names
      //$name     = $this->parseNames( $smplNode );

      // create an entity index in the node
      $mgmtNode = new $className( $smplNode );
      $this->names[trim($smplNode['name'])] = $mgmtNode->name;
      $this->nodes[trim($smplNode['name'])] = $mgmtNode;

    }//end foreach

  }//end public function createIndex */

  /**
   * @param string $name
   * @param string $type
   * @return DOMNode
   */
  public function get( $name, $type = null )
  {
    $check        = '/bdl/managements/management[@name="'.$name.'"]';

    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */

  /**
   * @param string $name
   * @param TArray $params
   * @return void
   */
  public function createDefault( $name, $params = array() )
  {

    if(isset($params['management']))
      $mgmtName = trim($params['management']);
    else
     $mgmtName = $name;

    if( $this->get($mgmtName) )
      return true;

    $entity = $params['entity'];

    $categories = '';
    if( isset($entity->categories) )
      $categories = $entity->categories->asXml();

    $description = '';
    if( isset($entity->description) )
      $description = $entity->description->asXml();

    $label = '';
    if( isset($entity->label) )
      $label = $entity->label->asXml();

    $info = '';
    if( isset($entity->info) )
      $info = $entity->info->asXml();

    $processes = '';
    if( isset($entity->processes) )
      $processes = $entity->processes->asXml();

    $references = '';
    if( isset($entity->references->ref) )
    {
      $references .= '<references>';
      foreach( $entity->references->ref as $ref )
      {
        $references .= '<ref name="'.trim($ref['name']).'" />'.NL;
      }
      $references .= '</references>';
    }

    $concepts = '';
    if( isset($entity->concepts) )
      $concepts = $entity->concepts->asXml();
      
    $dataProfile = '';
    if( isset($entity->data_profile) )
      $dataProfile = $entity->data_profile->asXml();

    $semantic = '';
    if( isset($entity->semantic) )
      $semantic = $entity->semantic->asXml();

    $events = '';
    if( isset($entity->events) )
      $events = $entity->events->asXml();

    $ui = '';
    if( isset($entity->ui) )
      $ui = $entity->ui->asXml();

    $contexts = '';
    if( isset($entity->contexts) )
      $contexts = $entity->contexts->asXml();
      
    $access = '';
    if( isset($entity->access) )
      $access = $entity->access->asXml();
      
    $customModul = '';
    
    if( isset($entity['module']) )
    {
      $customModul = ' module="'.trim($entity['module']).'" ';
    }

    $xml = <<<CODE
  <management name="{$mgmtName}" src="{$name}" {$customModul} >

  {$label}
  {$info}
  {$description}
  {$dataProfile}
  {$access}
  {$processes}
  {$categories}
  {$concepts}
  {$semantic}
  {$ui}
  {$contexts}
  {$events}
  {$references}

  </management>
CODE;

    $this->stringToNode( $xml, $this->nodeRoot );

    return true;

  }//end public function createDefault */

  /**
   * @param string $context
   * @return LibGenfEnvManagement
   */
  public function activEnvironment( $context  )
  {

    $environment = new LibGenfEnvManagement($this->builder);
    $environment->build( $this->management, $context );

    return $environment;

  }//end public function createEnvironment */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfEnvManagement
   */
  public function createEnvironment( $management )
  {

    $environment = new LibGenfEnvManagement($this->builder,$management);

    return $environment;

  }//end public function createEnvironment */

  /**
   * @param LibGenfTreeNodeManagement $management
   * @return LibGenfEnvManagement
   */
  public function createEnv( $management )
  {

    $environment = new LibGenfEnvManagement($this->builder,$management);

    return $environment;

  }//end public function createEnv */

  /**
   * @param LibGenfTreeNodeReference $ref
   * @return LibGenfEnvReference
   */
  public function createMgmtRefEnv( $ref  )
  {

    $env = new LibGenfEnvMgmtReference( $this->builder, $ref  );

    return $env;

  }//end public function createMgmtRefEnv */

  /**
   * @param LibGenfTreeNodeReference $ref
   * @return LibGenfEnvReference
   */
  public function createRefEnv( $ref  )
  {

    $env = new LibGenfEnvReference( $this->builder, $ref  );

    return $env;

  }//end public function createEnvironment */



  /**
   * @param string $name
   * @return LibGenfTreeNodeManagement
   */
  public function getManagement( $name = null )
  {

    // if no name, return default management
    if( is_null($name)  )
    {
      return $this->management;
    }

    if( empty($name) )
    {
      $this->builder->error( " requested management with empty key ".$this->builder->dumpEnv() );
      return null;
    }
    
    // if it's an object use the name key
    if( is_object($name) )
    {
      $name = $name->original;
    }

    if( !is_string($name) )
    {
      $this->builder->error( " requested management with invalid key ".get_class($name)." ".Debug::backtrace()  );
      return null;
    }
    
    // check for existance and return
    if( isset( $this->nodes[$name] ) )
    {
      return $this->nodes[$name];
    }
    else
    {
      return null;
    }

  }//end public function getManagement */



  /**
   * @param string $name
   * @return array<LibGenfTreeNodemanagement>
   */
  public function getManagementsBySource( $name )
  {

    // if it's an object use the name key
    $managements  = array();
    $tmpNodes     = $this->nodes;

    foreach( $tmpNodes as $node )
    {
      if( $node->name->source == $name->source &&  $node->name->name != $name->name  )
        $managements[] = $node;
    }

    return $managements;

  }//end public function getManagementsBySource */

  /**
   * @param string $name
   * @return array<LibGenfTreeNodemanagement>
   */
  public function getManagementsByProfile( $name )
  {

    // if it's an object use the name key
    $managements  = array();
    $tmpNodes     = $this->nodes;

    foreach( $tmpNodes as /* @var $node LibGenfTreeNodeManagement */ $node )
    {
      if( $node->dataProfile && $node->dataProfile->checkTarget( $name )  )
        $managements[] = $node;
    }

    return $managements;

  }//end public function getManagementsByProfile */
  
  /**
   * @param string $name
   * @return boolean
   */
  public function hasDataProfiles( $name )
  {

    // if it's an object use the name key
    $tmpNodes     = $this->nodes;

    foreach( $tmpNodes as $node )
    {
      if( $node->dataProfile && $node->dataProfile->checkTarget( $name )  )
        return true;
    }

    return false;

  }//end public function hasDataProfiles */
  
  /**
   * @param string $name
   * @return SimpleXmlElement
   */
  public function getEntity( $name = null )
  {

    if( is_null($name) )
    {
      $name = $this->name->source;
    }
    else if( is_object($name) )
    {
      $name = $name->source;
    }

    return $this->rootEntity->getEntity( $name );

  }//end public function getEntity */

  /**
   * set entity activ
   *
   * @param string $table
   * @return boolean
   */
  public function setActiv( $name )
  {

    if( $name instanceof LibGenfTreeNode )
    {
      $name = $name->name;
    }

    if( isset($this->nodes[$name->name]) )
    {
      $this->management = $this->nodes[$name->name];
    }
    else
    {
      $this->management = null;
      return false;
    }

    if( isset($this->names[$name->name]) )
    {
      $this->name       = $this->names[$name->name];
    }
    else
    {
      $this->name = null;
      return false;
    }

    if(!$this->rootEntity->setActiv( $this->name->source ))
      return false;


    return true;

  }//end public function setActiv */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param SimplexmlElement $node
   * @return void
   */
  public function parseNames( $node )
  {

    $name       = trim($node['name']);

    if( isset($this->names[$name]) )
      return $this->names[$name];

    $obj = new LibGenfNameManagement
    (
      $node,
      array( 'interpreter' => $this->builder->interpreter  )
    );

    $this->names[$name] = $obj;


    return $obj;

  }//end public function parseNames */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @todo do this more elegant
   *
   * @param string $name
   * @param string $label
   * @return void
   * /
  public function parseAlias( $original, $name, $source = null, $label = null )
  {

    if( !$source )
      $source = $name;

    $obj = new LibGenfNameManagement();

    $obj->original        = $original;

     if( $label )
      $obj->label         = $label;
    else
      $obj->label         = SParserString::subToName( $name );

    $obj->name            = $name;
    $obj->module          = SParserString::getDomainName( $name );
    $obj->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($name) ) ;


    $tmp = explode('_',$name);
    array_shift($tmp);

    $obj->management      = SParserString::subToCamelCase($name);
    $obj->managementPath  = $obj->lower('module').'/'.implode('_',$tmp);
    $obj->managementUrl   = $obj->module.'.'.$obj->model;

    $obj->class           = SParserString::subToCamelCase($name);
    $obj->classPath       = $obj->lower('module').'/'.implode('_',$tmp);
    $obj->classUrl        = $obj->module.'.'.$obj->model;

    $obj->i18nKey         = $obj->lower('module').'.'.SParserString::subBody($name).'.';
    $obj->i18nText        = $obj->lower('module').'.'.SParserString::subBody($name).'.label';
    $obj->i18nMessage     = $obj->lower('module').'.'.SParserString::subBody($name).'.message';

    // entity / source names
    $obj->source          = $source;
    $obj->emodule         = SParserString::getDomainName($source);
    $obj->emodel          = SParserString::subToCamelCase( SParserString::removeFirstSub($source) );

    $tmp = explode('_',$source);
    array_shift($tmp);

    $obj->entity          = SParserString::subToCamelCase($source);
    $obj->entityPath      = $obj->lower('emodule').'/'.implode('_',$tmp);
    $obj->entityUrl       = $obj->emodule.'.'.$obj->emodel;
    $obj->entityI18n      = $obj->lower('emodule').'.'.SParserString::subBody($source).'.';

    return $obj;

  }//end public function parseAlias */



  /**
   * get all managements ordered by categories
   * @return array
   */
  public function getCategoryManagements()
  {

    $categoryIndex = array();

    foreach( $this->nodes as $management )
    {

      if( !$categories = $management->getCategories() )
      {
        if(!isset($categoryIndex['default']))
          $categoryIndex['default'] = array();

        $categoryIndex['default'][] = $management;
      }//end if
      else
      {

        $catList = array_keys($categories);

        foreach( $catList as $catName  )
        {

          if(!isset($categoryIndex[$catName]))
            $categoryIndex[$catName] = array();

          $categoryIndex[$catName][] = $management;
        }

      }//end else

    }//end foreach

    return $categoryIndex;

  }//end public function getCategoryManagements */


////////////////////////////////////////////////////////////////////////////////
// def methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * Enter description here...
   *
   * @param SimpleXmlElement $entity
   * @param array $params
   * @return string
   */
  public function getAttributes( $entity, $params = array() )
  {
    return $this->rootEntity->getAttributes( $entity, $params );
  }//end public function getAttributes */


  /**
   *
   *     <ref name="project_employee" binding="free" relation="manyToMany">
   *       <label>test</label>
   *       <src name="project_project" id="id_project" alias=""/>
   *       <connection name="project_employee"/>
   *       <target name="enterprise_employee" id="id_employee" alias=""/>
   *     </ref>
   *
   * @return array<SimpleXmlElement>
   */
  public function connectionReferences( $name = null )
  {

    if(!$name)
      $name   = $this->name->name;

    $isRef  = array();

    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]';

    $modelXpath = $this->tree->getXpath();
    $listNodes  = $modelXpath->query($checkQuery);

    if( !$listNodes->length )
    {
      return array();
    }


    //TODO add check

    $nodeClass    = $this->builder->getNodeClass( 'ConnectionReference' );
    $doubleChecks = array();

    foreach( $listNodes as $node )
    {

      // new
      $parent   = $node->parentNode;
      $refName  = $parent->getAttribute('name');
      $mgmtName = $parent->parentNode->parentNode->getAttribute('name');

      $checkMgmtRefs = '/bdl/managements/management[@name="'.$mgmtName.'"]/references/ref[@name="'.$refName.'"]';

      $mgmtNodes  = $modelXpath->query($checkMgmtRefs);
      if( !$mgmtNodes->length )
      {
        continue;
      }

      $xmlString = simplexml_import_dom($node->parentNode)->asXml();

      foreach( $mgmtNodes as $mgmtNode )
      {
        $newNode  = simplexml_load_string($xmlString);
        $pMgmt    = $mgmtNode->parentNode->parentNode;

        $newNode->src['name'] = $pMgmt->getAttribute('name');
        $newNode->src['mask'] = $pMgmt->getAttribute('name');

        $refNode  = new $nodeClass($newNode) ;
        $refNode->setManagement( $this->getManagement( $name ) );
        $isRef[]  = $refNode ;

      }

      // new

      //$simpleNode = simplexml_import_dom($node->parentNode);

      /* removed double check, for naming we now use the refName
       * @todo recreate extended double check that includes srcRefId targetRefId
       * the src and the target
       *
      $double = false;

      foreach( $doubleChecks as $doubleCheck )
      {
        if
        (
          trim($simpleNode->target['name']) == ''
            || $doubleCheck['target'] == trim($simpleNode->target['name'])
        )
        {
          $double = true;
        }
      }//end foreach

      if( $double )
        continue;

      $doubleChecks[] = array('target' => trim($simpleNode->target['name']));
      */

      //$isRef[]        = new $nodeClass($simpleNode) ;
    }

    return $isRef;

  }//end public function connectionReferences */
  
  
  /**
   * Prüfen ob der aktuelle Knoten eine Connection Reference ist
   * @return boolean
   */
  public function isConnectionReferences( $name )
  {

    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]';

    $modelXpath = $this->tree->getXpath();
    $listNodes  = $modelXpath->query( $checkQuery );

    if( !$listNodes->length )
    {
      return false;
    }
    else 
    {
      return true;
    }

  }//end public function isConnectionReferences */
  
  
  /**
   *
   *     <ref name="project_employee" binding="free" relation="manyToMany">
   *       <label>test</label>
   *       <src name="project_project" id="id_project" alias=""/>
   *       <connection name="project_employee"/>
   *       <target name="enterprise_employee" id="id_employee" alias=""/>
   *     </ref>
   *
   * @return array<SimpleXmlElement>
   */
  public function connectionReferencesIsFiltered( $name  )
  {

    $modelXpath = $this->tree->getXpath();

    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]/../ui/list/filter';
    $listNodes  = $modelXpath->query( $checkQuery );
    if( $listNodes->length )
    {
      return true;
    }
    
    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]/../ui/list/table/filter';
    $listNodes  = $modelXpath->query( $checkQuery );
    if( $listNodes->length )
    {
      return true;
    }
    
    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]/../ui/list/treetable/filter';
    $listNodes  = $modelXpath->query( $checkQuery );
    if( $listNodes->length )
    {
      return true;
    }
    
    $checkQuery = '/bdl/entities/entity/references/ref/connection[@name="'.$name.'"]/../ui/list//filter';
    $listNodes  = $modelXpath->query( $checkQuery );
    if( $listNodes->length )
    {
      return true;
    }
    
    return false;


  }//end public function connectionReferencesIsFiltered */
  
  /**
   * Alle Filter aus dem Modell extrahieren
   * @return array
   */
  public function getFilters( )
  {
    $check        = '//filter/check[@type="container"]';

    $modelXpath   = $this->tree->getXpath();
    $nodeList     = $modelXpath->query( $check );

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $filters = array();
      
      foreach( $nodeList as $check )
      {
        $filters[] = trim($check->nodeValue);
      }
      
      return $filters;
    }
    else
    {
      return array();
    }

  }//end public function getFilters */

} // end class LibGenfTreeManagement
