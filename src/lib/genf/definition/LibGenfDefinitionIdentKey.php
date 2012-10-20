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
class LibGenfDefinitionIdentKey
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

      $vars->name       = 'ident_key';
      $vars->size       = '120';
      $vars->type       = 'text';
      $vars->validator  = 'cname';

      $vars->unique->strength = 'full';
      $vars->search->type     = 'equal';

      $vars->label->de  = 'Ident Key';
      $vars->label->en  = 'Ident Key';

      $vars->description->de  = 'Ident Key '.$vars->entity->label->de;
      $vars->description->en  = 'Ident Key for '.$vars->entity->label->en;

      $vars->uiElement->type  = 'text';
      $vars->uiElement->position->priority  = '80';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionIdentKey
