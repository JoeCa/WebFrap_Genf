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
 * Auslesen aller vorhandener Komponenten
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeRootComponent
  extends LibGenfTreeRoot
{
////////////////////////////////////////////////////////////////////////////////
// Index Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * index for the parsed entities
   * @var array
   */
  public $components      = array();

  /**
   * the activ entity
   * @var SimpleXmlElement
   */
  public $component       = null;

  /**
   *
   * @var array
   */
  protected $classCache   = array();


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   */
  public function preProcessing()
  {

    $checkRoot  = '/bdl/components';
    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($checkRoot);

    // create entities, if not yet exists
    if( $nodeList->length )
    {
      $this->nodeRoot = $nodeList->item(0);
    }
    else
    {
      $this->nodeRoot = $this->modelTree->createElement('components');
      $this->modelRoot->appendChild( $this->nodeRoot );
    }

  }//end public function preProcessing */

  /**
   * @param DOMDocument $tmpXml
   * @param DOMXpath $tmpXpath
   * @param string $repoPath
   */
  public function importFile(  $tmpXml, $tmpXpath, $repoPath = null  )
  {


    $this->builder->activRepo = $repoPath;

    $tmpXpath = new DOMXPath( $tmpXml );

    $checkQuery  = '/bdl/components/component';
    $nodeList   = $tmpXpath->query( $checkQuery );

    foreach( $nodeList as $node )
    {

      if( !$node->hasAttribute('name') )
      {
        Error::report
        (
          'Missing the name Attribute for a component. Please check you Model!',
          htmlentities( simplexml_import_dom($node)->asXml()  )
        );
        continue;
      }

      if( Log::$levelDebug )
        Log::debug( 'Found Component '.$node->getAttribute('type').': '.$node->getAttribute('name') );

      $this->add( $node  );

    }//end foreach


  }//end public function importFile */

  /**
   *
   */
  public function postProcessing()
  {

  }//end public function postProcessing */

  /**
   * create all default elements that inherits from entity
   * @param string $name
   */
  public function createDefaultDependencies(  )
  {


  }//end protected function createDefaultDependencies */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeRoot#createIndex()
   */
  public function createIndex()
  {
    // append default attributes to the entity


    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query('/bdl/components/component');


    foreach( $nodeList as $node )
    {

      $smplNode = simplexml_import_dom($node);
      $type = trim($smplNode['type']);

    
      if( !$className = $this->builder->getNodeClass( 'Component'.SParserString::subToCamelCase(trim($smplNode['type'])) ) )
      {
        $this->builder->error( 'Missing Treenode for Component '.$smplNode['name'].' Type: '.$type.'  ' );
        continue;
      }
      
      // parse Names
      //$this->parseNames( $smplNode );

      
      if(!isset($this->nodes[$type]))
      {
        $this->nodes[$type] = array();
      }

      // create an entity index in the node
      $this->nodes[$type][trim($smplNode['name'])] = new $className($smplNode);

    }//end foreach


  }//end public function createIndex */

   /**
    * special add for the tree
    * @param $node
    * @return boolean
    */
  public function add( $node )
  {

    if( is_string($node) )
    {
      if(!$node = $this->stringToNode( $node ))
      {
        Error::addError('Tried to add an invalid string node');
        return null;
      }
    }

    if( !is_object($node) || !$node instanceof DOMNode )
    {
        Error::addError('Got invalid Node to add' , null, $node  );
        return null;
    }

    if( $node->ownerDocument !== $this->nodeRoot->ownerDocument )
    {
      $newNode = $this->nodeRoot->ownerDocument->importNode( $node , true );
    }
    else
    {
      $newNode = $node;
    }

    $this->builder->interpreter->interpret( $newNode );

    if( $oldEntity = $this->get( $newNode->getAttribute('name'), $newNode->getAttribute('type') ) )
    {
      return $this->merge( $oldEntity, $newNode );
    }
    else
    {
      return $this->nodeRoot->appendChild( $newNode );
    }

  }//end public function add */

  /**
   *
   * @param string $key
   * @param string $type
   * @return LibGenfName with all parsed Names for the Tree
   */
  public function getName( $key = null, $type = null )
  {
    if( !$key )
      return $this->name;
    else if( is_object($key) )
      $name = $key['name'];
    else
      $name = $key;

    return isset($this->names[$type][$name])
      ? $this->names[$type][$name]
      : null;

  }//end public function getName */

  /**
   * @param string $name
   * @param string $type
   * @return DOMNode
   */
  public function get( $name, $type = null )
  {

    $check = '/bdl/components/component[@name="'.$name.'" and @type="'.$type.'"]';

    $modelXpath = $this->tree->getXpath();
    $nodeList   = $modelXpath->query($check);

    // create entities, if not yet exists
    if( $nodeList->length )
      return $nodeList->item(0);
    else
      return null;

  }//end public function get */
  
  /**
   * Ein Component Node anfragen
   * @param string $name
   * @param string $type
   * @return LibGenfTreeNodeComponent
   */
  public function getComponent( $name, $type )
  {
    
    if( isset($this->nodes[$type][$name]) )
      return $this->nodes[$type][$name];
    else 
      return null;

  }//end public function getComponent */

  /**
   * @param string $name
   * @return void
   */
  public function createDefault( $name, $params = array() )
  {

    if( !isset($params['type']) )
    {
      Error::addError('Missing type for Component: '.$name );
      return false;
    }

    if( !$this->get($name) )
      return true;

    $method = 'create'.ucfirst($params['type']);

    if( !method_exists($method,$this) )
    {
      Error::addError('Requested invalid component type: '.$type );
      return false;
    }

    $xml = $this->$method( $name, $params = array() );

    $this->stringToNode( $xml , $this->nodeRoot );

  }//end public function createDefault */


  /**
   * @param string $name
   * @param array $params
   */
  protected function createSelectbox( $name, $params = array() )
  {

    /*
      <component type="selectbox">
        <fields value="this.name" id="this.rowid" title=""/>
      </component>
     */

    $value  = isset( $params['value'] )
      ? trim($params['value'])
      : 'name';

    $id     = isset( $params['id'] )
      ? trim($params['id'])
      : 'rowid';


    $xml =     <<<CODE
  <component type="selectbox" name="{$name}" src="{$name}" >
  
      <id name="{$id}" />
      
      <order_by>
        <field name="{$value}" />
      </order_by>

      <fields>
        <field name="{$value}" />
      </fields>
      
  </component>
CODE;


    return $xml;

  }//end protected function createSelectbox */

  /**
   *
   * @param string $newNode
   * @param array $params
   * @return void
   */
  public function addElement( $newNode, $params = array() )
  {

    if( is_string($newNode) )
    {
      if( !$newNode = $this->stringToNode( $newNode ) )
      {
        Error::addError( 'Failed to load the given XML String as Node in the Tree. The Attribute name is missing.' );
        return false;
      }
    }

    if( !$name =  $newNode->getAttribute('name') )
    {
      Error::addError( 'Tried to add an invalid node. The Attribute name is missing.' );
      return false;
    }

    if( !$type =  $newNode->getAttribute('type') )
    {
      Error::addError( 'Tried to add an invalid node. The Attribute type is missing.' );
      return false;
    }

    if( $old = $this->get( $name, $type ) )
    {
      return $this->merge( $old, $newNode );
    }
    else
    {
      return $this->add( $newNode );
    }

  }//end public function addElement */

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param SimpleXmlElement $node
   * @return void
   */
  public function parseNames( $node  )
  {

    $name       = trim($node['name']);
    $type       = trim($node['type']);
    $src        = trim($node['src']);

    if( isset($this->names[$type][$name]) )
      return;

    if(!isset($this->names[$type]) )
      $this->names[$type] = array();

    $label      = $this->builder->interpreter->getLabel( $node );
    $obj        = new LibGenfName();


    $obj->name            = $name;
    $obj->type            = $type;
    $obj->source          = $src;

    $obj->label           = $label;

    $obj->class           = SParserString::subToCamelCase($name);
    $obj->module          = SParserString::getDomainName($name);

    $obj->entity          = SParserString::subToCamelCase( $src );
    $obj->model           = SParserString::subToCamelCase( SParserString::removeFirstSub($src) ) ;


    $tmp = explode('_',$name);
    array_shift($tmp);

    $obj->entityPath      = $obj->lower('module').'/'.implode('_',$tmp);
    $obj->entityUrl       = $obj->module.'.'.$obj->model;

    $obj->classPath       = $obj->lower('module').'/'.implode('_',$tmp);
    $obj->classUrl        = $obj->module.'.'.$obj->model;

    $obj->i18nKey         = $obj->lower('module').'.'.SParserString::subBody($name).'.';
    $obj->i18nText        = $obj->lower('module').'.'.SParserString::subBody($name).'.label';
    $obj->i18nMessage     = $obj->lower('module').'.'.SParserString::subBody($name).'.message';

    $this->names[$type][$name] = $obj;

  }//end public function parseNames */


  /**
   * set entity activ
   *
   * @param string $table
   */
  public function setActiv( $name , $type = null )
  {

    if( is_null($name) )
    {
      Error::addError( 'Empty set activ request' );
      return false;
    }

    if( is_object($name) )
    {
      $name = trim($name['name']);
      $type = trim($name['type']);
    }

    /*
    if( is_object($name) )
    {
      if( $name instanceof SimpleXmlElement )
        $name = trim($name['name']);
      else
        $name = $name->name();
    } $component
    */

    if( isset($this->nodes[$type][$name]) )
    {
      $this->component = $this->nodes[$type][$name];
    }
    else
    {
      $this->component = null;
      Error::addWarning('Found no Entity Object for Entity:'.$name , $name );
      return false;
    }

    if( isset($this->names[$type][$name]) )
    {
      $this->name = $this->names[$type][$name];
    }
    else
    {
      $this->name = null;
      Error::addWarning('Found no Name Object for Entity:'.$name , $name );
      return false;
    }

    return true;

  }//end public function setActiv */

  /**
   *
   * @param string $key
   * @return LibGenfName with all parsed Names for the Tree
   */
  public function getEntity( $key = null )
  {

    if(!$key)
      return $this->entity;

    return isset($this->entities[$key])?$this->entities[$key]:null;

  }//end public function getEntity */


} // end class LibGenfTreeRootBdlEntity
