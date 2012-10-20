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
class LibGenfDefinitionTitle
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

      $vars->name       = 'title';
      $vars->size       = '400';
      $vars->type       = 'text';
      $vars->validator  = 'text';

      $vars->label->de  = 'Titel';
      $vars->label->en  = 'Title';

      $vars->description->de  = 'Titel der '.$vars->entity->label->de;
      $vars->description->en  = 'Ttle the '.$vars->entity->label->en;

      $vars->display->type    = 'exclude';
      $vars->search->type     = 'equal';
      $vars->search->free     = true;
      $vars->unique->strength = 'maybe';

      $vars->uiElement->type              = 'text';
      $vars->uiElement->position->valign  = 'top';
      $vars->uiElement->size->width       = 'xxlarge';


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

  }//end public function interpret */


} // end class LibGenfDefinitionTitle
