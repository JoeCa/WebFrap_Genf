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
class LibGenfDefinitionAcronym
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return [DOMNode]
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'acronym';
      $vars->size       = '250';
      $vars->type       = 'text';

      $vars->label->de  = 'Acronym';
      $vars->label->en  = 'Acronym';

      $vars->description->de  = 'Acronym '.$vars->entity->label->de;
      $vars->description->en  = 'Acronym of the '.$vars->entity->label->en;

      $vars->display->type    = 'exclude';
      $vars->display->action  = 'edit';
      
      $vars->search->type     = 'like';
      $vars->unique->strength = 'maybe';

      $vars->uiElement->type              = 'text';
      $vars->uiElement->position->priority  = '96';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionAcronym
