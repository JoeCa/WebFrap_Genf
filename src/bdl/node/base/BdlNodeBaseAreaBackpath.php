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
class BdlNodeBaseAreaBackpath
  extends BdlSubNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   * Liste mit den Pfaden
   * @var [BdlNodeProfileAreaBackpathNode]
   */
  public $pathNodes = null;

////////////////////////////////////////////////////////////////////////////////
// Getter & Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function getName( $display = false )
  {
    
    if( $display )
    {
      return SParserString::shortLabel( $this['name'], 22, '...', true );
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
   * @return [BdlNodeProfileAreaBackpathNode]
   */
  public function getPathNodes()
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
        
    return $this->pathNodes;

  }//end public function getPathNodes */
  
  /**
   * Laden der Permission Referenzen
   * @return [BdlNodeProfileAreaBackpath]
   */
  public function getPathNodeByIndex( $idx )
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
        
    return isset( $this->pathNodes[$idx] ) 
      ? $this->pathNodes[$idx]
      : null;
    
  }//end public function getPathNodeByIndex */
  
  /**
   * @param array $path
   * @return BdlNodeProfileAreaBackpath
   */
  public function getBackpathNodeByPath( $path )
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
    
    $permIdx = array_shift($path);
    
    if( $path )
    {
      return $this->pathNodes[$permIdx]->getBackpathNodeByPath( $path );
    }
    else 
    {
      return $this->pathNodes[$permIdx];
    }
    
  }//end public function getBackpathNodeByPath */
  
  /**
   * @param array $path
   * @return void
   */
  public function deleteBackpathNodeByPath( $path )
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
    
    $permIdx = array_shift($path);
    
    if( $path )
    {
      return $this->pathNodes[$permIdx]->deleteBackpathNodeByPath( $path );
    }
    else 
    {
      $this->pathNodes[$permIdx]->dom->parentNode->removeChild( $this->pathNodes[$permIdx]->dom );
      unset( $this->pathNodes[$permIdx] );
      $this->pathNodes = array_merge( array(), $this->pathNodes );
    }
    
  }//end public function deleteBackpathNodeByPath */


  
  /**
   * Laden der Permission Referenzen
   */
  public function loadPathNodes()
  {

    $this->pathNodes = array();
    
    $paths = $this->getNodes( 'paths/path' );
    
    foreach( $paths as $path )
    {
      $this->pathNodes[] = new BdlNodeBaseAreaBackpathNode( $this->file, $path, $this->dom );
    }
    
  }//end public function loadPathNodes */
  
  /**
   * 
   */
  public function createBackpathNode()
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
    
    $paths = $this->getNode( 'paths' );
    
    if( !$paths  )  
      $paths = $this->touchNode( 'paths' );
    
    $path = $paths->ownerDocument->createElement ( 'path' );
    $paths->appendChild( $path );
    
    $node = new BdlNodeBaseAreaBackpathNode( $this->file, $path, $this->dom );
    $this->pathNodes[] = $node;
    
    return $node;
      
  }//end public function createBackpathNode */
  
  /**
   * 
   */
  public function countBackpathNodes()
  {
    
    if( is_null($this->pathNodes) )
      $this->loadPathNodes();
    
    return count($this->pathNodes);
      
  }//end public function countBackpathNodes */
  


}//end class BdlNodeProfileAreaBackpath
