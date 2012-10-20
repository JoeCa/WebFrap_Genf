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
 * @subpackage Genf
 */
class LibGeneratorDmodelAttribute
  extends LibGenfGenerator
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param LibGenfTreeNodeAttribute $attribute
   */
  public function buildSetupField( $attribute )
  {

    return '';

  }//end public function buildSetupField */

  /**
   *
   * @param string $string
   * @return string
   */
  public function escapeString( $string )
  {

    return str_replace( '"', '\\"', $string  );

  }//end public function escapeString */

} // end class LibGeneratorDmodelAttribute
