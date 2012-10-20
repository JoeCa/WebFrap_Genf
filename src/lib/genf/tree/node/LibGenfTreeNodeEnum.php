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
class LibGenfTreeNodeEnum
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// Attributes
//////////////////////////////////////////////////////////////////////////////*/
  
  /**
   * @var array
   */
  public $values = array();
  
/*//////////////////////////////////////////////////////////////////////////////
// Load methodes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameEnum( $this->node );
    
    foreach( $this->node->values->value as $value )
    {
      $this->values[trim($value['name'])] = new LibGenfTreeNodeEnumValue( $value );
    }

  }//end protected function loadChilds */


  /**
   * @return LibGenfNameDefault
   */
  public function getValues()
  {
    
    return $this->values;
    
  }//end public function getValues */

  


}//end class LibGenfTreeNodeEnum

