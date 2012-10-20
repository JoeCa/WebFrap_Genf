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
class BdlNodeModule
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
  public function __construct( $file  )
  {
    
    $this->file = $file;
    
    $this->dom = $this->file->getNodeByPath('/bdl/modules/module');
    
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



}//end class BdlNodeModule
