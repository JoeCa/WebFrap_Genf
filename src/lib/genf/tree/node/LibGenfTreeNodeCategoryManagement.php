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
class LibGenfTreeNodeCategoryManagement
  extends LibGenfTreeNodeCategory
{
////////////////////////////////////////////////////////////////////////////////
// attribute
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTreeNodeCategory
   */
  protected $entityCategory = null;

////////////////////////////////////////////////////////////////////////////////
// getter
////////////////////////////////////////////////////////////////////////////////


  /**
   * @return boolean
   */
  public function isAutoCat()
  {

    if(!is_string( $this->node ))
      return false;

    return $this->entityCategory->isAutoCat();

  }//end public function isAutoCat */

  /**
   * @return array
   */
  public function layout()
  {
    return $this->entityCategory->layout();
  }//end public function layout */

  /**
   * @return LibGenfTreeNodeCategoryManagement
   */
  public function getLayout( )
  {

    if( isset( $this->node->layout ) )
    {
      return new LibGenfTreeNodeLayout( $this->node->layout ) ;
    }
    else
    {
      return new LibGenfTreeNodeLayout( $this->entityCategory->layout() );
    }

  }//end public function getLayout */

////////////////////////////////////////////////////////////////////////////////
// extract the relevant data from the category
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param array $params
   */
  protected function prepareNode( $params = array() )
  {

    $this->entityCategory = $params['category'];

    $this->parseNames();

  }//end protected function prepareNode */


  /**
   * parse the name to all needed Names to generate the Files
   *
   * @return void
   */
  protected function parseNames(  )
  {

    if( is_string($this->entityCategory) )
    {
      $this->name = new LibGenfNameDefault(trim($this->entityCategory));
    }
    else
    {
      $this->name = $this->entityCategory->name;
    }

  }//end public function parseNames */


}//end class LibGenfTreeNodeCategory

