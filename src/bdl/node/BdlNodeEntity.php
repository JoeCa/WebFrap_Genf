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
class BdlNodeEntity
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Liste mit den erlaubten Values
   * @var array
   */
  protected $_attributesMeta = array
  (
    'name' => array
    (
      'required'      => true,
      'validator'     => Validator::CNAME
    )
  );
  
  /**
   * @var array
   */
  public $_attributes = array
  (
    'name' => null, // Name der Entity
  );
  
////////////////////////////////////////////////////////////////////////////////
// Tags
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Liste mit dem vorhandenen Attributen der Entity
   * @var array
   */
  public $attributes = null;
  
  
////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   */
  public function __construct( $file  )
  {
    
    $this->file = $file;
    
    $this->dom = $this->file->getNodeByPath('/bdl/entities/entity');
    
  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName()
  {
    return $this['name'];
  }//end  public function getAuthor
  
  /**
   * @param string $name
   */
  public function setName( $name )
  {
    $this['name'] = $name ;
  }//end  public function setName */
  
  /**
   * @return string
   */
  public function getTableType()
  {
    return $this['data'];
  }//end  public function getTableType
  
  /**
   * @param string $name
   */
  public function setTableType( $val )
  {
    $this['data'] = $val ;
  }//end  public function setTableType */
  
  /**
   * @return string
   */
  public function getArchType()
  {
    return $this['type'];
  }//end  public function getArchType
  
  /**
   * @param string $name
   */
  public function setArchType( $val )
  {
    $this['type'] = $val ;
  }//end  public function setArchType */
  
  /**
   * @return string
   */
  public function getModule()
  {
    return $this['module'];
  }//end  public function getModule */
  
  /**
   * @param string $name
   */
  public function setModule( $module )
  {
    $this['module'] = $module ;
  }//end  public function setModule */

////////////////////////////////////////////////////////////////////////////////
// Attribute Logic
////////////////////////////////////////////////////////////////////////////////

  
  /**
   * @return [BdlNodeEntityAttribute]
   */
  public function getAttributes(  )
  {
    
    if( !is_null($this->attributes)  )
      return $this->attributes;
    
    $attributes = $this->getNodes( 'attributes/attribute' );

    foreach( $attributes as $attribute )
    {
      $attrNode = new BdlNodeEntityAttribute( $this->file, $attribute, $this->dom );
      
      $this->attributes[] = $attrNode;
    }
    
    return $this->attributes;
    
  }//end public function getAttributes */
  

  /**
   * @return [BdlNodeEntityAttribute]
   */
  public function createAttribute()
  {
    
    if( is_null($this->attributes) )
      $this->getAttributes();

    $attributes = $this->getNode( 'attributes' );
    
    if( !$attributes  )  
      $attributes = $this->touchNode( 'attributes' );
    
    $attribute = $attributes->ownerDocument->createElement ( 'attribute' );
    $attributes->appendChild( $attribute );
    
    $node = new BdlNodeEntityAttribute( $this->file, $attribute, $this->dom );
    $this->attributes[] = $attribute;
    
    return $node;
      
  }//end public function createAttribute */
  
  /**
   * @param int $index
   * @return BdlNodeEntityAttribute
   */
  public function getAttribute( $index )
  {
    
    if( is_null($this->attributes) )
      $this->getAttributes();
      
    if( !isset( $this->attributes[$index] ) )
      return null;
    
    return $this->attributes[$index];
    
  }//end public function getAttribute */
  
  /**
   * @param int $index
   */
  public function deleteAttribute( $index )
  {
    
    if( is_null($this->attributes) )
      $this->getAttributes();
      
    Debug::console( 'drop index '.$index );

    if( isset( $this->attributes[$index] ) )
    {
      $this->attributes[$index]->dom->parentNode->removeChild( $this->attributes[$index]->dom );
      unset( $this->attributes[$index] );
    }
    
    $this->attributes = array_merge( array(), $this->attributes );

  }//end public function deleteBackpath */
  
  /**
   * @return int
   */
  public function countAttributes()
  {
    
    if( is_null($this->attributes) )
      $this->getAttributes();

    return count($this->attributes);
      
  }//end public function countAttributes */

}//end class BdlEntity
