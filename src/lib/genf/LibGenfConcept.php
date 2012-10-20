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
class LibGenfConcept
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
   * @var LibGenfBuild
   */
  protected $builder    = null;

  /**
   *
   * @var DOMDocument
   */
  protected $node    = null;

////////////////////////////////////////////////////////////////////////////////
// magic methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param  $fileXml
   * @param  $fileXpath
   * @param  $tree
   * @param  $builder
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
  public function interpret( $statement )
  {
    // default is to do nothing
  }//end public function interpret */


  /**
   * interpret a statement an replace the statement with the definition
   * @param string  $xmlString
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return boolean
   */
  protected function importString( $xmlString, $document )
  {

    $doc = new DOMDocument( '1.0', 'utf-8' );
    $doc->preserveWhitespace  = false;
    $doc->formatOutput        = true;

    if(!$doc->loadXML( $xmlString ))
    {
      Error::addError('Failed to load the xml stringnode');
      return null;
    }

    $rootNode     = $doc->childNodes->item(0);

    if(!$newNode  = $document->importNode($rootNode,true))
    {
      Error::addError('Failed to import the xml stringnode');
      return null;
    }

    return $rootNode;

  }//end public function importString */

  /**
   * interpret a statement an replace the statement with the definition
   * @param string  $xmlString
   * @param DOMNode $parentNode
   * @return boolean
   */
  protected function add( $xmlString,  $parentNode )
  {

    $doc = new DOMDocument( '1.0', 'utf-8' );
    $doc->preserveWhitespace  = false;
    $doc->formatOutput        = true;

    if( !$doc->loadXML( $xmlString ) )
    {
      Error::addError('Failed to load the xml for addNode');
      return null;
    }

    $rootNode   = $doc->childNodes->item(0);
    $definition = $parentNode->ownerDocument->importNode($rootNode,true);
    $parentNode->appendChild( $definition );

    return $definition;

  }//end public function add */

  /**
   * interpret a statement an replace the statement with the definition
   * @param string  $xmlString
   * @param DOMNode $parentNode
   * @return boolean
   */
  protected function replace( $old,  $new )
  {

    if( $old->ownerDocument !== $new->ownerDocument )
      $new = $old->ownerDocument->importNode($new,true);

    return $oldNode = $old->parentNode->replaceChild( $new, $old );

  }//end public function replace */

////////////////////////////////////////////////////////////////////////////////
// adder methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $xml
   */
  protected function addEntity( $xml )
  {
    
    $this->tree->getRootNode('Entity')->add( $xml );
    
  }//end protected function addEntity */
  
  /**
   * @param string $key
   * @return DOMNode
   */
  protected function getEntity( $key )
  {
    
    return $this->tree->getRootNode('Entity')->get($key);
    
  }//end protected function getEntity */

  /**
   * @param string $xml
   */
  protected function addComponent( $xml )
  {
    
    $this->tree->getRootNode('Component')->add($xml);
    
  }//end protected function addComponent */

  /**
   * @param string $xml
   */
  protected function addManagement( $xml )
  {
    
    $this->tree->getRootNode('Management')->add($xml);
    
  }//end protected function addManagement */
  
  /**
   * @param string $key
   * @return DOMNode
   */
  protected function getManagement( $key )
  {
    
    return $this->tree->getRootNode('Management')->get($key);
    
  }//end protected function getManagement */

  /**
   * @param DOMNode $conceptNode
   */
  protected function getParent( $conceptNode )
  {
    return $conceptNode->parentNode->parentNode;
  }//end protected function getParent */

  /**
   * @param DOMNode $conceptNode
   */
  protected function getMainNode( $conceptNode )
  {
    return $conceptNode->parentNode->parentNode;
  }//end protected function getMainNode */

  /**
   * @param DOMNode $conceptNode
   */
  protected function parentType( $conceptNode )
  {
    return $conceptNode->parentNode->parentNode->nodeName ;
  }//end protected function parentType */

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
