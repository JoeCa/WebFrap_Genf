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
 *
 * <category name="time"  >
 *  <layout>
 *     <col type="1/3" align="v" fill="auto" />
 *     <col type="1/3" align="v" />
 *     <col type="1/3" align="v" />
 *   </layout>
 *   <label>
 *     <text lang="en" >Time Data</text>
 *   </label>
 * </category>
 *
 */

/**
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeNodeSubcategory
  extends LibGenfTreeNode
{

////////////////////////////////////////////////////////////////////////////////
// extract the relevant data from the category
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {
    $this->parseNames();
  }//end protected function prepareNode */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @param string $name
   * @param string $label
   * @return void
   */
  protected function parseNames(  )
  {
    $this->name = new LibGenfNameDefault( $this->node );
  }//end public function parseNames */



}//end class LibGenfTreeNodeSubcategory

