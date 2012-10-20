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
class LibGenfDefinitionPriority
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

      $vars->name       = 'priority';
      $vars->type       = 'int';

      $vars->label->de  = 'Priorität';
      $vars->label->en  = 'Priority';

      $vars->uiElement->type    = 'priority';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $vars = $this->checkAttribute( $statement, $parentNode, 'priority' );


  }//end public function interpret */


} // end class LibGenfDefinitionPriority
