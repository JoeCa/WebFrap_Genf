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
class LibGenfDefinitionPercent
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

      $vars->name       = 'percent';
      $vars->size       = '5.2';
      $vars->type       = 'numeric';
      $vars->validator  = 'numeric';

      $vars->label->de  = 'Prozent';
      $vars->label->en  = 'Percent';

      $vars->semantic->unit->type = "percent";
      $vars->uiElement->type      = 'percent';


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

  }//end public function interpret */


} // end class LibGenfDefinitionMoney
