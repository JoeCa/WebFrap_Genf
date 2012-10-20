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
class BdlNodeDesktop
  extends BdlBaseNode
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Constructor
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @param BdlFile $file
   */
  public function __construct( $file )
  {
    
    $this->file = $file;
    $this->dom  = $this->file->getNodeByPath( '/bdl/desktops/desktop' );
    
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
  }//end  public function getName */
  
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
  
  /**
   * @return string
   */
  public function getExtends()
  {
    return $this['extends'];
  }//end  public function getExtends */
  
  /**
   * @param string $val
   */
  public function setExtends( $val )
  {
    $this['extends'] = $val ;
  }//end  public function setExtends */
  
  
  /**
   * @return string
   */
  public function getNavigationName()
  {
    return $this->getNodeAttr( 'navigation', 'name' );
  }//end  public function getNavigationName */
  
  /**
   * @param string $name
   */
  public function setNavigationName( $name )
  {
    $this->setNodeAttr( 'navigation', 'name', $name );
  }//end  public function setNavigationName */
  
  /**
   * @return string
   */
  public function getTreeName()
  {
    return $this->getNodeAttr( 'tree', 'name' );
  }//end  public function getTreeName */
  
  /**
   * @param string $name
   */
  public function setTreeName( $name )
  {
    $this->setNodeAttr( 'tree', 'name', $name );
  }//end  public function setTreeName */
  
  /**
   * @return string
   */
  public function getWorkareaName()
  {
    return $this->getNodeAttr( 'workarea', 'name' );
  }//end  public function getWorkareaName */
  
  /**
   * @param string $name
   */
  public function setWorkareaName( $name )
  {
    $this->setNodeAttr( 'workarea', 'name', $name );
  }//end  public function setWorkareaName */

}//end class BdlNodeDesktop
