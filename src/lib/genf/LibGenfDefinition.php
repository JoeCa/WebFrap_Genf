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
class LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTree
   */
  protected $tree       = null;

  /**
   *
   * @var DOMDocument
   */
  protected $fileXml    = null;

  /**
   *
   * @var DOMDocument
   */
  protected $fileXpath  = null;

  /**
   *
   * @var LibGenfBuild
   */
  protected $builder    = null;

////////////////////////////////////////////////////////////////////////////////
// magic methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuild $builder
   */
  public function __construct( $builder  )
  {

    $this->builder    = $builder;
    $this->tree       = $builder->tree;

  }//end public function __construct */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * interpret a statement an replace the statement with the definition
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $parentNode->removeChild($statement);

  }//end public function interpret */


  /**
   * interpret a statement an replace the statement with the definition
   * @param string  $xmlString
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return boolean
   */
  protected function replaceDefinition( $xmlString, $statement, $parentNode )
  {

    if( !is_object( $parentNode  ) || !$parentNode instanceof DOMnode  )
    {
      Error::report('Got invalid Parent for replaceDefinition: ', $parentNode);
      return null;
    }


    $doc = new DOMDocument( '1.0', 'utf-8' );
    $doc->preserveWhitespace  = false;
    $doc->formatOutput        = true;

    if(!$doc->loadXML( $xmlString ))
    {

      Error::report('Failed to load the xml stringnode');

      ///TODO improve error handling!
      // remove invalid definition
      if( is_object($statement) && $statement instanceof DOMNode )
        $statement->parentNode->removeChild($statement);

      return null;
    }

    $rootNode   = $doc->childNodes->item(0);

    if(!$definition = $parentNode->ownerDocument->importNode($rootNode,true))
    {
      Error::report('Failed to import the xml stringnode',htmlentities($xmlString));

      // remove invalid definition
      $statement->parentNode->removeChild($statement);
      return null;
    }


    if( $oldNode = $statement->parentNode->replaceChild( $definition, $statement ))
    {
      return $definition;
    }
    else
    {
      return null;
    }

  }//end public function replaceDefinition */

  /**
   * remove a node
   * @param DOMNode $statement
   * @return boolean
   */
  public function remove( $node  )
  {

    if
    (
      !is_object( $node  )
        || !$node instanceof DOMnode
        || !is_object( $node->parentNode  )
        || !$node->parentNode instanceof DOMnode
    )
    {
      Error::report('Got invalid Node for remove: ', $node);
      return null;
    }

    $node->parentNode->removeChild($node);

  }//end public function remove */


  /**
   * interpret a statement an replace the statement with the definition
   * @param string  $xmlString
   * @param DOMNode $parentNode
   * @return boolean
   */
  protected function addNode( $xmlString,  $parentNode )
  {

    $doc = new DOMDocument( '1.0', 'utf-8' );
    $doc->preserveWhitespace  = false;
    $doc->formatOutput        = true;


    if( !$doc->loadXML( $xmlString ) )
    {
      Error::report('Failed to load the xml for addNode: ', htmlentities($xmlString));
      return null;
    }

    if( !is_object( $parentNode  ) || !$parentNode instanceof DOMnode  )
    {
      Error::report('Got invalid Parent for addNode: ', $parentNode);
      return null;
    }

    $rootNode   = $doc->childNodes->item(0);

    if( !$definition = $parentNode->ownerDocument->importNode($rootNode,true) )
    {
      Error::report('Failed to import node: ', htmlentities($xmlString));
      return null;
    }

    $newChild = $parentNode->appendChild( $definition );

    return $newChild;

  }//end public function appendNode */

////////////////////////////////////////////////////////////////////////////////
// read given parameters from definition tags
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @param array $ignore list of child nodes that should just be ignored, like optional
   * @return LibGenfModelBdlAttribute
   */
  protected function checkAttribute( $statement, $parentNode, $ignore = array()  )
  {

    $vars   = new LibGenfModelBdlAttribute();
    $check  = simplexml_import_dom( $statement);

    $vars->import( $check );

    // asuming this is a attributes tag from an entity
    if( !$vars->isEmpty( 'name' ) )
    {
      $modelName        = SParserString::subToName( $vars->name ) ;
      $lowModelName     = strtolower( $modelName );

      $vars->label->de  = $modelName;
      $vars->label->en  = $lowModelName;
    }


    /*
    $vars->name       = isset($check['name'])?trim($check['name']):$attrName;
    $vars->type       = isset($check['type'])?trim($check['type']):null;
    $vars->size       = isset($check['size'])?trim($check['size']):null;
    $vars->validator  = isset($check['validator'])?trim($check['validator']):null;
    $vars->minSize    = isset($check['minSize'])?trim($check['minSize']):null;
    $vars->maxSize    = isset($check['maxSize'])?trim($check['maxSize']):null;

    $vars->target     = isset($check['target'])?trim($check['target']):null;
    */

    /*
    if( isset( $check->display ) )
    {
      $vars->display = $check->display->asXml();
    }


    // check the main category
    $vars->category   = isset($check->categories['main'])?trim($check->categories['main']):null;

    // TODO nicht wirklich gut erweiterbar wenn mal mehr als nur eine sprache
    // verwendet werden soll
    $vars->label      = new TArray();

    // asuming this is a attributes tag from an entity
    $modelName        = SParserString::subToCamelCase( SParserString::removeFirstSub($vars->name) ) ;
    $lowModelName     = strtolower( $modelName );

    $vars->label->de  = $modelName;
    $vars->label->en  = $lowModelName;

      // overwrite if a label exists
    // less code vs performance mkay!
    if( isset($check->label) )
    {
      // shortn
      $intp = $this->builder->interpreter;

      if($label = $intp->getLabel($check,'de') )
      {
        $vars->label->de = $label;
      }
      if($label = $intp->getLabel($check,'en') )
      {
        $vars->label->en = $label;
      }
    }
    */


    // TODO nicht wirklich gut erweiterbar wenn mal mehr als nur eine sprache
    // verwendet werden soll
    $vars->entity         = new LibGenfName();

    $vars->entity->node   = simplexml_import_dom( $parentNode->parentNode );

    // asuming this is a attributes tag from an entity
    $name                 = trim($vars->entity->node['name']);
    $vars->entity->name   = $name;
    $vars->entity->asCat  = SParserString::subBody( $vars->entity->name );

    $vars->entity->label  = new LibGenfName();
    $modelName            = SParserString::subToName( SParserString::removeFirstSub($name) ) ;
    $lowModelName         = strtolower( $modelName );

    $vars->entity->label->de  = $modelName;
    $vars->entity->label->en  = $lowModelName;
    $vars->entity->label->fr  = $modelName;
    $vars->entity->label->es  = $modelName;
    $vars->entity->label->it  = $modelName;
    $vars->entity->label->sr  = $modelName;

    /*
    // verwendet werden soll
    $vars->uiElement = isset($check->uiElement['type'])
      ? trim($check->uiElement['type'])
      : null;
    */

    return $vars;

  }//end protected function checkAttribute */

////////////////////////////////////////////////////////////////////////////////
// adder methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $xml
   */
  protected function addEntity( $xml )
  {
    $this->builder->tree->getRootNode('Entity')->add($xml);
  }//end protected function addEntity */
  
  /**
   * @param string $xml
   */
  protected function addManagement( $xml )
  {
    $this->builder->tree->getRootNode('Management')->add($xml);
  }//end protected function addEntity */
  
  /**
   * @param string $xml
   */
  protected function addWidget( $xml )
  {
    $this->builder->tree->getRootNode('Widget')->add($xml);
  }//end protected function addWidget */

  /**
   * @param string $xml
   */
  protected function addItem( $xml )
  {
    $this->builder->tree->getRootNode('Item')->add($xml);
  }//end protected function addItem */
  
  /**
   * @param string $xml a xml string, that should be appendend to the component root node
   */
  protected function addComponent( $xml )
  {
    $this->builder->tree->getRootNode('Component')->add($xml);
  }//end protected function addComponent */

  /**
   * @param string $type the type of the rootnode where the new model element should bd appended
   * @param string $xml a xml string, that should be appendend to the root node
   */
  protected function addRootNode( $type, $xml )
  {
    $this->builder->tree->getRootNode(ucfirst($type))->add($xml);
  }//end protected function addRootNode */

/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/

  public function getDebugDump()
  {
    return array
    (

    );
  }

}//end class LibGenfDefinition
