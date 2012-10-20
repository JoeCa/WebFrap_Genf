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
class LibGenfDefinitionPassword
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

    $replaced = array();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'password';
      $vars->size       = '64';
      $vars->type       = 'text';
      $vars->validator  = 'password';

      $vars->label->de  = 'Passwort';
      $vars->label->en  = 'Password';


      $vars->uiElement->type              = 'password';
      $vars->uiElement->position->priority  = "40";
      //$vars->uiElement->position->align   = 'right';


      $replaced[] = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $xml = <<<XMLS

      <attribute name="change_{$vars->name}" type="date"   >

        <uiElement type="date" >
          <position relation="below" target="{$vars->name}" />
        </uiElement>

        <description>
          <text lang="en" >Date until the password has to be changed</text>
        </description>

      </attribute>

XMLS;

    $replaced[] = $this->addNode($xml, $parentNode);
    
    $xml = <<<XMLS

      <attribute name="salt_{$vars->name}" type="text" size="10"   >

        <uiElement type="hidden" >
        </uiElement>

        <description>
          <text lang="en" >Salt Value for the password</text>
        </description>

      </attribute>

XMLS;

    $replaced[] = $this->addNode($xml, $parentNode);

    return $replaced;

  }//end public function interpret */


}//end class LibGenfDefinitionPassword
