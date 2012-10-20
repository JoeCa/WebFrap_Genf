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
class GenfBdl_Model
  extends Model
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////



  /**
   * @return void
   */
  public function getProjectMap( )
  {

    $data = array();

    // includes data
    include PATH_GW.'conf/map/bdl/projects/projects.php';

    return $data;

  } // end public function getProjectMap */


  public function syncDbMetadata()
  {

  }



}//end class GenfBdl_Model

