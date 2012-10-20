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
 * Auslesen aller vorhandener Komponenten
 *
 *
 * @package WebFrap
 * @subpackage GenF
 */
class LibGenfTreeComponentTree
  extends LibGenfTreeComponent
{
////////////////////////////////////////////////////////////////////////////////
// Index Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * create a component node in the tree
   * @param unknown_type $name
   * @param unknown_type $params
   * @return unknown_type
   */
  public function create( $name, $params = array() )
  {

    /*
      <component type="selectbox">
        <fields value="this.name" id="this.rowid" title=""/>
      </component>
     */

    $value = isset( $params['value'] )?trim($params['value']):'name';
    $id = isset( $params['id'] )?trim($params['id']):'name';


    $xml =     <<<CODE
  <component type="selectbox" name="$name" src="$name" >
     <fields value="this.name" id="this.rowid" title="" />
  </component>
CODE;


    return $xml;

  }//end public function create */




} // end class LibGenfTreeComponentSelectbox
