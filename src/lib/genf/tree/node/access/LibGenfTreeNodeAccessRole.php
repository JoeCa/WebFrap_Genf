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
class LibGenfTreeNodeAccessRole
  extends LibGenfTreeNode
{
/*//////////////////////////////////////////////////////////////////////////////
// attributes
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * Name der zugewiesenen Gruppe
   * @return string
   */
  public function name( )
  {

    ///TODO error handling
    return isset( $this->node['name'] )
      ? trim( $this->node['name'] )
      : null;

  }//end public function name */

  /**
   * Das Access Level abfragen
   * @return string
   */
  public function getAccessLevel( )
  {

    return isset( $this->node['access_level'] )
      ? trim( strtoupper( $this->node['access_level'] ) )
      : 'LISTING';

  }//end public function getAccessLevel */
  
  /**
   * Das Access Level abfragen
   * @return string
   */
  public function getRefAccessLevel( )
  {

    return isset( $this->node['ref_access_level'] )
      ? trim( strtoupper( $this->node['ref_access_level'] ) )
      : 'LISTING';

  }//end public function getRefAccessLevel */
  
  /**
   * Mit Potency wird der benötigte Grad der zuweisung angegeben.
   * 
   * Es gibt 
   * - dataset Im Relation zum Datensatz
   * - entity   In Relation zur Entity
   * - area     In Relation zur aktuellen Maske
   * - global   Keine Relation, global
   * Eine zuweisung ist dann partial wenn die zugehörigkeit zu der gruppe
   * zumindest in relation zu einem Datensatz existiert
   * 
   * @return string
   */
  public function getPotency( )
  {

    return isset( $this->node['potency'] )
      ? trim( strtolower( $this->node['potency'] ) )
      : 'dataset';

  }//end public function getPotency */


}//end class LibGenfTreeNodeAccess

