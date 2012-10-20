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
class LibGenfDefinitionNetworkShare
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'network_share_location';
      $vars->type       = 'text';
      $vars->size       = '400';
      $vars->validator  = 'text';

      $vars->unique->strength = 'full';

      $vars->search->type     = 'equal';
      $vars->display->table   = true;

      $vars->uiElement->position->priority  = "29";
      //$vars->uiElement->position->align     = "right";

      $vars->label->de  = "Share Location";
      $vars->label->en  = "Share Location";

      $vars->description->de  = "Share Location";
      $vars->description->en  = "Share Location";


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

  }//end public function interpret */


} // end class LibGenfDefinitionNetworkShare
