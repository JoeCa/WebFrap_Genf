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
class LibGenfDefinitionLogo
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

      $vars->name       = 'id_logo';
      $vars->target     = 'wbfsys_file';

      $vars->label->de  = 'Logo';
      $vars->label->en  = 'Logo';

      $vars->description->de  = 'Logo für '.$vars->entity->label->de;
      $vars->description->en  = 'Logo for '.$vars->entity->label->en;

      $vars->uiElement->type            = 'FileImage';
      $vars->uiElement->position->valign    = 'top';
      $vars->uiElement->position->priority  = '94';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    return $this->replaceDefinition($xml, $statement, $parentNode);

  }//end public function interpret */



} // end class LibGenfDefinitionLogo
