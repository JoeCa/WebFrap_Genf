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
class LibGenfInterpreterCondition
  extends LibGenfInterpreterParser
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  protected $xpath  = null;

  /**
   *
   * Enter description here ...
   * @var unknown_type
   */
  protected $node   = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   *
   */
  public function postInterpret( $xmlNode  )
  {

    // check if the given node is a valid definition
    if( 'condition' !==  strtolower(trim($xmlNode->nodeName)) )
    {
      return;
    }

    $this->interpretNode( $xmlNode );

  }//end protected function interpret */


  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   *
   */
  protected function interpretNode( $xmlNode  )
  {

    $this->node   = $xmlNode;
    $this->xpath  = new DOMXpath( $this->node->ownerDocument );


    foreach( $xmlNode->childNodes as $childNode )
    {
      if( !$this->interpreter->isNode( $childNode ) )
        continue;

      if( $this->resolvCondition( $childNode ) )
        return true;

    }

    return false;


  }//end protected function interpretNode */


  /**
   * create all default elements that inherits from entity
   * @param string $defType
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   *
   */
  protected function resolvCondition( $node  )
  {

    if( $node->hasAttribute('exists')  )
    {
      return $this->interpretExists( $node );
    }
    else if( 'else' ==  $node->nodeName  )
    {
      return $this->interpretElse( $node );
    }
    else
    {
      Debug::console('Failed to interpret the condition');
      return false;
    }

  }//end protected function interpretNode */

  /**
   * @return true  when successfully interpreted the node
   */
  protected function interpretExists( $node )
  {

    $existsKey  = $node->getAttribute('exists');

    $tmp        = explode(':',$existsKey);
    $type       = $tmp[0];
    $name       = $tmp[1];

    ///TODO im moment is type per definition einfach mal entity

    $root = $this->builder->getRoot($type);

    if( $root->exists($name) )
    {
      foreach( $node->childNodes as $childNode )
      {
        if( !$this->interpreter->isNode( $childNode ) )
          continue;

        $this->node->parentNode->appendChild( $childNode );

      }

      // remove the condition node we are finished
      $this->node->parentNode->removeChild( $this->node );
      return true;

    }

    return false;

  }//end protected function interpretExists */


  /**
   * @return true  when successfully interpreted the node
   */
  protected function interpretElse( $node )
  {

    foreach( $node->childNodes as $childNode )
    {
      if( !$this->interpreter->isNode( $childNode ) )
        continue;

      $this->node->parentNode->appendChild( $childNode );

    }

    // remove the condition node we are finished
    $this->node->parentNode->removeChild( $this->node );
    return true;


  }//end protected function interpretExists */


}//end class LibGenfInterpreter
