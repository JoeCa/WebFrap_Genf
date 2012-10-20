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
class BdlNodeBaseAreaPermission
  extends BdlSubNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  public $references = null;

  /**
   * @var BdlNodeBaseAreaPermission
   */
  public $treeParent = null;
  
////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   * @param DOMElement $node
   * @param DOMElement $parent
   * @param BdlNodeBaseAreaPermission $treeParent
   */
  public function __construct( $file, $node, $parent, $treeParent = null )
  {
    
    $this->file    = $file;
    $this->dom     = $node;
    $this->parent  = $parent;
    
    $this->treeParent = $treeParent;
    
  }//end public function __construct */
  
////////////////////////////////////////////////////////////////////////////////
// Getter & Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * checken ob der node in der ersten ebene liegt
   * @return boolean
   */
  public function isFirstChild(  )
  {
    
    return !($this->treeParent instanceof BdlNodeBaseAreaPermissionRef);

  }//end  public function isFirstChild

  /**
   * @return string
   */
  public function getName( $display = false )
  {
    
    if( $display )
    {
      return SParserString::shortLabel( $this['name'], 33, '...', true );
    }
    
    return $this['name'];
    
  }//end  public function getName
  
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
  public function getLevel()
  {
    return $this['level'];
  }//end  public function getLevel */
  
  /**
   * @param string $level
   */
  public function setLevel( $level )
  {
    $this['level'] = $level;
  }//end  public function setLevel */
  
////////////////////////////////////////////////////////////////////////////////
// References
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * Laden der Permission Referenzen
   * @return [BdlNodeProfileAreaPermissionRef]
   */
  public function getReferences()
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
        
    return $this->references;

  }//end public function getReferences */
  
  /**
   * Laden der Permission Referenzen
   * @return [BdlNodeProfileAreaPermissionRef]
   */
  public function getReferenceByIndex( $idx )
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
        
    return isset( $this->references[$idx] ) 
      ? $this->references[$idx]
      : null;
    
  }//end public function getReferenceByIndex */
  
  /**
   * @param array $path
   * @return BdlNodeProfileAreaPermissionRef
   */
  public function getRefByPath( $path )
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
    
    $permIdx = array_shift($path);
    
    if( $path )
    {
      return $this->references[$permIdx]->getRefByPath( $path );
    }
    else 
    {
      return $this->references[$permIdx];
    }
    
  }//end public function getRefByPath */
  
  /**
   * @param array $path
   * @return void
   */
  public function deleteRefByPath( $path )
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
    
    $permIdx = array_shift($path);
    
    if( $path )
    {
      return $this->references[$permIdx]->deleteRefByPath( $path );
    }
    else 
    {
      $this->references[$permIdx]->dom->parentNode->removeChild( $this->references[$permIdx]->dom );
      unset( $this->references[$permIdx] );
      $this->references = array_merge( array(), $this->references );
    }
    
  }//end public function deleteRefByPath */
  
  /**
   * Laden der Permission Referenzen
   * @return [BdlNodeProfileAreaPermissionRef]
   */
  public function deleteReferenceByIndex( $idx )
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
        
    if( isset( $this->references[$idx] ) )
    {
      
      $this->references[$idx]->dom->parentNode->removeChild( $this->references[$index]->dom );
      unset( $this->references[$idx] );
    }
    
    $this->references = array_merge( array(), $this->references );

  }//end public function deleteReferenceByIndex */

  
  /**
   * Laden der Permission Referenzen
   */
  public function loadReferences()
  {

    $this->references = array();
    
    $references = $this->getNodes( 'references/ref' );
    
    foreach( $references as $ref )
    {
      $this->references[] = new BdlNodeProfileAreaPermissionRef( $this->file, $ref, $this->dom, $this );
    }
    
  }//end public function loadReferences */
  
  /**
   * 
   */
  public function createPermissionRef()
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
    
    $refs = $this->getNode( 'references' );
    
    if( !$refs  )  
      $refs = $this->touchNode( 'references' );
    
    $ref = $refs->ownerDocument->createElement ( 'ref' );
    $refs->appendChild( $ref );
    
    $node = new BdlNodeProfileAreaPermissionRef( $this->file, $ref, $this->dom, $this );
    $this->areaPermissions[] = $node;
    
    return $node;
      
  }//end public function createPermissionRef */
  
  /**
   * 
   */
  public function countPermissionRef()
  {
    
    if( is_null($this->references) )
      $this->loadReferences();
    
    return count($this->references);
      
  }//end public function countPermissionRef */
  

  
////////////////////////////////////////////////////////////////////////////////
// References
////////////////////////////////////////////////////////////////////////////////



}//end class BdlNodeProfileAreaPermission
