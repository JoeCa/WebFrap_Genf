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
class LibGenfDefinitionAccessKey
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

      $vars->name       = 'access_key';
      $vars->size       = '120';
      $vars->type       = 'text';
      $vars->validator  = 'cname';
      $vars->index      = 'btree';

      $vars->unique->strength = 'full';
      $vars->search->type     = 'equal';

      $vars->label->de  = 'Access Key';
      $vars->label->en  = 'Access Key';
      $vars->label->fr  = 'Accès  Clé';
      $vars->label->it  = 'Accesso Chiave';

      $vars->description->de  = 'Zugriffs Key '.$vars->entity->label->de;
      $vars->description->en  = 'Access Key for '.$vars->entity->label->en;
      $vars->description->fr  = 'Accès  Clé pour '.$vars->entity->label->fr;
      $vars->description->it  = 'Accesso Chiave per '.$vars->entity->label->it;

      $vars->uiElement->type  = 'text';
      $vars->uiElement->position->priority  = '90';

      //$vars->categories->main  = 'meta';

      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionCname
