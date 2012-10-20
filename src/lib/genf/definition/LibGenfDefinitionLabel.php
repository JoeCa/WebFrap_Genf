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
class LibGenfDefinitionLabel
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @param SimpleXmlElement $parentNode
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'label';
      $vars->type       = 'text';
      $vars->size       = '250';

      $vars->search->type  = 'like';
      //$vars->search->type  = 'equal';

      $vars->display->listing  = true;
      $vars->display->action  = 'edit';

      $vars->label->de  = 'Label';
      $vars->label->en  = 'Label';

      $vars->description->de  = 'Label für '.$vars->entity->label->de;
      $vars->description->en  = 'Label for '.$vars->entity->label->en;

      $vars->uiElement->type    = 'text';
      $vars->uiElement->position->priority = '94';

      return $this->replaceDefinition( $vars->parse(), $statement, $parentNode );

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    return $this->replaceDefinition( $xml, $statement, $parentNode );

  }//end public function interpret */


} // end class LibGenfDefinitionLabel
