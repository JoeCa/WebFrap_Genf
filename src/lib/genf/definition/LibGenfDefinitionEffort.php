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
class LibGenfDefinitionEffort
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

    $replaced = array();
    
    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'effort';
      $vars->size       = '30.6';
      $vars->type       = 'numeric';

      $vars->label->de  = 'Aufwand';
      $vars->label->en  = 'Effort';

      $vars->description->de  = 'Aufwand für '.$vars->entity->label->de;
      $vars->description->en  = 'Effort for '.$vars->entity->label->en;

      $vars->categories->main       = 'effort';
      $vars->uiElement->type        = 'numeric';
      $vars->uiElement->size->width = 'small';

      $replaced[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    return $replaced;


  }//end public function interpret */


} // end class LibGenfDefinitionEffort
