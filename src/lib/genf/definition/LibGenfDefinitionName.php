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
class LibGenfDefinitionName
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return LibGenfModelBdlAttribute
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'name';
      $vars->size       = '250';
      $vars->type       = 'text';
      $vars->validator  = 'text';

      $vars->label->de  = 'Name';
      $vars->label->en  = 'Name';

      $vars->description->de  = 'Der Name der '.$vars->entity->label->de;
      $vars->description->en  = 'the Name of the '.$vars->entity->label->en;

      $vars->display->listing   = true;
      $vars->display->selection = true;
      $vars->display->text      = true;
      $vars->display->input     = true;
      $vars->display->action    = 'edit';

      $vars->search->type     = 'like';
      $vars->search->free     = 'true';
      $vars->unique->strength = 'maybe';

      $vars->uiElement->type              = 'text';
      $vars->uiElement->position->priority  = '95';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionName
