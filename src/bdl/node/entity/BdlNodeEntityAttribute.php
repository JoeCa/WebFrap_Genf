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
class BdlNodeEntityAttribute
  extends BdlSubNode
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
    'type' => array
    (
      'required'      => true,
      'default'        => 'text',
      'valid_values'  => array
      (
        'text',
        'int',
      )
    )
  );
  
  /**
   * Liste der Attribute
   * @var array
   */
  public $_attributes = array
  (
    'name'     => null,  // Name string
    'size'     => null,  // Size string
    'is_a'     => null,
    'type'     => null,  // Type des Attributes, Default ist immer Text
    'target'   => null,
  );

  /**
   * @var BdlCategory
   */
  public $category = null;

  /**
   * @var BdlAttributeUiElement
   */
  public $uiElement = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName()
  {
    return $this['name'];
  }//end  public function getName
  
  /**
   * @param string $name
   */
  public function setName( $name )
  {
    
    if( '' != trim($name)  )
      $this['name'] = $name ;
    else 
      $this->removeAttr( 'name' );
    
    
  }//end  public function setName */
  
  /**
   * @return string
   */
  public function getType()
  {
    return $this['type'];
  }//end  public function getType
  
  /**
   * @param string $type
   */
  public function setType( $type )
  {
    
    if( '' != trim($type)  )
      $this['type'] = $type ;
    else 
      $this->removeAttr( 'type' );
      
  }//end  public function setType */
  
  
  /**
   * @return string
   */
  public function getSize()
  {
    return $this['size'];
  }//end  public function getSize */
  
  /**
   * @param string $size
   */
  public function setSize( $size )
  {
    if( '' != trim($size)  )
      $this['size'] = $size ;
    else 
      $this->removeAttr( 'size' );
  }//end  public function setSize */
  
  
  /**
   * @return string
   */
  public function getIsA()
  {
    return $this['is_a'];
  }//end  public function getIsA */
  
  /**
   * @param string $is_a
   */
  public function setIsA( $is_a )
  {
    
    if( '' != trim($is_a)  )
      $this['is_a'] = $is_a ;
    else 
      $this->removeAttr( 'is_a' );
      
  }//end  public function setIsA */

  
  /**
   * @return string
   */
  public function getTarget()
  {
    return $this['target'];
  }//end  public function getTarget */
  
  /**
   * @param string $target
   */
  public function setTarget( $target )
  {
    if( '' != trim($target)  )
      $this['target'] = $target ;
    else 
      $this->removeAttr( 'target' );
  }//end  public function setTarget */
  
  
  /**
   * @return string
   */
  public function getValidator()
  {
    return $this['validator'];
  }//end  public function getValidator */
  
  /**
   * @param string $validator
   */
  public function setValidator( $validator )
  {
    if( '' != trim($validator)  )
      $this['validator'] = $validator ;
    else 
      $this->removeAttr( 'validator' );
  }//end  public function setValidator */
  
  
  /**
   * @param boolean $asBoolean
   * @return string
   */
  public function getRequired( $asBoolean = false )
  {
    
    return $this['required'];
    
    if( $asBoolean )
      return 'false' == $this['required']?false:true;
    else 
      return $this['required'];
    
  }//end  public function getRequired */
  
  /**
   * @param string $required
   */
  public function setRequired( $required )
  {
    
    if( $required )
      $this['required'] = 'true';
    else 
      $this['required'] = 'false';

  }//end  public function setRequired */
  
  
  /**
   * @return string
   */
  public function getMinSize()
  {
    return $this['min_size'];
  }//end  public function getMinSize */
  
  /**
   * @param string $min_size
   */
  public function setMinSize( $min_size )
  {
    if( '' != trim($min_size)  )
      $this['min_size'] = $min_size ;
    else 
      $this->removeAttr( 'min_size' );
  }//end  public function setMinSize */
  
  
  /**
   * @return string
   */
  public function getMaxSize()
  {
    return $this['max_size'];
  }//end  public function getMaxSize */
  
  /**
   * @param string $max_size
   */
  public function setMaxSize( $max_size )
  {
    if( '' != trim($max_size)  )
      $this['max_size'] = $max_size ;
    else 
      $this->removeAttr( 'max_size' );
  }//end  public function setMaxSize */
  
  
  /**
   * @return string
   */
  public function getIndex()
  {
    return $this['index'];
  }//end  public function getIndex */
  
  /**
   * @param string $index
   */
  public function setIndex( $index )
  {
    if( '' != trim($index)  )
      $this['index'] = $index ;
    else 
      $this->removeAttr( 'index' );
  }//end  public function setIndex */
  
  /**
   * @return string
   */
  public function getUnique()
  {
    return $this->nodeExists( 'unique' )?'true':'false';
  }//end  public function getUnique */
  
  /**
   * @param boolean $unique
   */
  public function setUnique( $unique )
  {
    
    if( $unique )
      $this->touchNode( 'unique' );
    else 
      $this->removeNode( 'unique' );

  }//end  public function setUnique */
  
  
  /**
   * @return string
   */
  public function getSearchFree()
  {
    
    if( !$this->nodeExists( 'search' ) )
      return null;
      
    return $this->getNodeAttr('search', 'free');

  }//end  public function getUnique */
  
  /**
   * @param boolean $searchFree
   */
  public function setSearchFree( $searchFree )
  {
    
    if( $searchFree )
    {
      $this->touchNode( 'search' );
      $this->setNodeAttr( 'search', 'free', 'true' );
      
    }
    else 
    {
      if( !$this->nodeExists( 'search' ) )
        return;
        
      $this->removeNodeAttr( 'search', 'free' );
        
    }

  }//end  public function SearchFree */
  
  /**
   * @return string
   */
  public function getSearchType()
  {
    
    if( !$this->nodeExists( 'search' ) )
      return null;
      
    return $this->getNodeAttr('search', 'type');

  }//end  public function getSearchType */
  
  /**
   * @param boolean $searchFree
   */
  public function setSearchType( $searchType )
  {
    
    if( '' != trim($searchType) )
    {
      if( !$this->nodeExists( 'search' ) )
        $this->touchNode( 'search' );
      
      $this->setNodeAttr( 'search', 'type', $searchType );
    }
    else 
    {
      if( !$this->nodeExists( 'search' ) )
        return;
        
      $this->removeNodeAttr('search', 'type');
        
    }

  }//end  public function setSearchType */
  
  /**
   * @return string
   */
  public function getCategory()
  {
    
    if( !$this->nodeExists( 'categories' ) )
      return null;
      
    return $this->getNodeAttr('categories', 'main');

  }//end  public function getCategory */
  
  /**
   * @param boolean $catName
   */
  public function setCategory( $catName )
  {
    
    if( !$this->nodeExists( 'categories' ) )
      $this->touchNode( 'categories' );
    
    if( '' != trim($catName) )
    {
      $this->setNodeAttr( 'categories', 'main', $catName );
    }
    else 
    {
      $this->setNodeAttr( 'categories', 'main', 'default' );
    }

  }//end  public function setCategory */

}//end class BdlEntityAttribute
