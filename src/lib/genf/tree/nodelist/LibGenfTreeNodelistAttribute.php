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
class LibGenfTreeNodelistAttribute
  extends LibGenfTreeNodelist
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * backlink to the entity
   * @var LibGenfTreeNodeEntity
   */
  protected $entity = null;

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $key
   * @return LibGenfTreenodeAttribute
   */
  public function getAttribute( $key )
  {

    if( isset( $this->childs[$key] ) )
    {
      return $this->childs[$key];
    }
    else
    {
      return null;
    }

  }//end public function getAttribute */
  
  /**
   * @return string array
   */
  public function getAttrList( $asString = false )
  {
    
    if( !$asString )
    {
      return array_keys( $this->childs );
    }
    else 
    {
      return implode( "; ", array_keys( $this->childs ) ) ;
    }
    
  }//end public function getAttrList */

  /**
   * @param array<string> $categories List with categories
   * @return array<LibGenfTreenodeAttribute> List of all search attributes
   */
  public function getSearchAttributes( $categories = null )
  {

    $searchAttributes = array();

    foreach( $this->childs as /* @var $attribute LibGenfTreeNodeAttribute */ $attribute )
    {
      if( $attribute->search() )
      {
        $searchAttributes[] = $attribute;
      }
    }

    return $searchAttributes;

  }//end public function getSearchAttributes */
  
  /**
   * @return [LibGenfTreenodeAttribute] List of all search attributes
   */
  public function getRequiredAttributes( )
  {

    $reqAttributes = array();

    foreach( $this->childs as /* @var $attribute LibGenfTreeNodeAttribute */ $attribute )
    {
      if( $attribute->required() )
      {
        $reqAttributes[] = $attribute;
      }
    }

    return $reqAttributes;

  }//end public function getRequiredAttributes */


  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNodelist#parseParams($params)
   */
  protected function parseParams( $params )
  {

    $this->params   = $params;
    $this->entity   = $params['entity'];

  }//end protected function parseParams */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNodelist#extractChildren($node)
   */
  protected function extractChildren( $node )
  {

    $this->node = $node;
    $nodeClass  = $this->builder->getNodeClass('Attribute');

    $this->childs = array();


    foreach( $this->node->attribute as $attribute )
    {

      if( isset( $attribute->ignore ) )
      {
        continue;
      }

      if( isset($this->childs[trim($attribute['name'])]) )
      {
        Debug::console( 'multiple attribute: '.trim($attribute['name']).' in '.$this->entity->name() , $attribute );
      }

      $this->childs[trim($attribute['name'])] = new $nodeClass($attribute, null, array('entity'=>$this->entity) ) ;
    }

  }//end protected function extractChildren */

  /**
   * (non-PHPdoc)
   * @see src/lib/genf/tree/LibGenfTreeNodelist#extractChildren($node)
   */
  public function dump(  )
  {

    $this->rewind();

    foreach( $this->childs as $attribute )
    {

      if( is_object($attribute) )
      {
        $name = $attribute->name();
      }
      else
      {
        $name = 'no obj?!';
      }
      //$name = $attribute->name();

    }//end foreach

  }//end public function dump */

}//end class LibGenfTreeNodelistAttribute

