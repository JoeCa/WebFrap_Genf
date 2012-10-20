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
class LibGenfDefinitionShortname
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

      $vars->name       = 'shortname';
      $vars->size       = '70';
      $vars->type       = 'text';
      $vars->validator  = 'text';

      $vars->label->de  = 'Kürzel';
      $vars->label->en  = 'Shortname';

      $vars->description->de  = 'Kürzel für '.$vars->entity->label->de;
      $vars->description->en  = 'Shortname for '.$vars->entity->label->en;

      $vars->display->type    = 'exclude';
      $vars->search->type     = 'equal';
      $vars->unique->strength = 'maybe';


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

  }


} // end class LibGenfDefinitionShortname
