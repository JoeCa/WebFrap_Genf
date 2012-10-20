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
class LibGenfDefinitionDuration
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

      $vars->name       = 'duration';
      $vars->type       = 'numeric';
      $vars->size       = '8.2';

      $vars->semantic->unit->type   = 'time';
      $vars->semantic->unit->value  = 'month';

      $vars->uiElement->size->width = 'small';

      $vars->label->de  = 'Dauer';
      $vars->label->en  = 'Duration';

      $vars->description->de  = 'Dauer für '.$vars->entity->label->de;
      $vars->description->en  = 'Duration for '.$vars->entity->label->en;

      $vars->categories->main  = 'schedule';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionDuration
