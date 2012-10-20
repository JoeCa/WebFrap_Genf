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
class LibGenfDefinitionTemplate
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

      $vars->name       = 'template';
      $vars->type       = 'text';

      $vars->label->de  = 'Template';
      $vars->label->en  = 'Template';

      $vars->description->de  = 'Template für '.$vars->entity->label->de;
      $vars->description->en  = 'Template for '.$vars->entity->label->en;

      $vars->uiElement->type    = 'textarea';
      $vars->uiElement->position->priority  = '5';
      $vars->uiElement->position->valign    = 'bottom';

      $vars->uiElement->size->width   = "full";
      $vars->uiElement->size->height  = "large";


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionTemplate
