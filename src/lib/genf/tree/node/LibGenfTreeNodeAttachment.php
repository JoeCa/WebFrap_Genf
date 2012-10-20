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
class LibGenfTreeNodeAttachment
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array
   */
  public $validTypes = array
  (
    'attached_file',
    'embeded_file',
    'embeded_layout',
    'attachment',
    'embeded'
  );
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    //$this->name       = new LibGenfNameAction( $this->node );

  }//end protected function loadChilds */

  /**
   * @return string
   */
  public function getName()
  {

    return trim( $this->node['name'] );

  }//end public function getName */

  /**
   * @return string
   */
  public function getFile()
  {

    return trim( $this->node['file'] );
      
  }//end public function getFile */

  /**
   * @return string
   */
  public function getType()
  {

    if( !isset( $this->node['type'] ) )
      return 'attachment';
    else
      return trim( $this->node['type'] );

  }//end public function getType */

}//end class LibGenfTreeNodeAttachment

