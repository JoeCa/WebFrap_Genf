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
class LibGenfDefinitionDataProfile
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

      $vars->name       = 'data_profile';
      $vars->size       = '120';
      $vars->type       = 'text';
      $vars->validator  = 'cname';
      
      $vars->index      = 'btree';

      $vars->unique->strength = 'full';
      $vars->search->type     = 'equal';

      $vars->label->de  = 'Data Profile';
      $vars->label->en  = 'Data Profile';

      $vars->description->de  = 'Data Profile '.$vars->entity->label->de;
      $vars->description->en  = 'Data Profile '.$vars->entity->label->en;

      $vars->uiElement->type  = 'text';

      $vars->categories->main  = 'meta';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionDataProfile
