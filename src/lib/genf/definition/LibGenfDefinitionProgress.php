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
class LibGenfDefinitionProgress
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

      $vars->name       = 'progress';
      $vars->type       = 'int';

      $vars->uiElement->type  = 'progress';

      $vars->display->type    = 'progress';
      $vars->display->table   = true;

      $vars->label->de  = 'Fortschritt';
      $vars->label->en  = 'Progress';

      $vars->description->de   = "Fortschritt für {$vars->entity->label->de}";
      $vars->description->en   = "Progress for {$vars->entity->label->en}";

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionPriority
