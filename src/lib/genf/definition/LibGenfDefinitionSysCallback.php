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
class LibGenfDefinitionSysCallback
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DOMNode $statement
   * @param DOMNode $parentNode
   * @return LibGenfModelBdlAttribute
   */
  public function interpret( $statement, $parentNode )
  {

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'sys_callback';
      $vars->size       = '250';
      $vars->type       = 'text';
      $vars->validator  = 'cname';

      $vars->label->de  = 'Callback';
      $vars->label->en  = 'Callback';


      return $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }


  }//end public function interpret */


} // end class LibGenfDefinitionName
