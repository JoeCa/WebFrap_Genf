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
class LibGenfDefinitionProcessStatus
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   * @param SimpleXmlElement $statement
   * @param SimpleXmlElement $parentNode
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $simpleStatement  = simplexml_import_dom($statement);

    $replaced         = null;

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name         = 'id_status';
      $vars->type         = 'int';
      $vars->target       = 'wbfsys_process_node'; 
      //$vars->targetField  = 'position';

      $vars->uiElement->type  = 'process';
      $vars->uiElement->src   = SParserString::subToCamelCase( trim($simpleStatement['process']) );

      $vars->display->field   = 'label';
      $vars->display->table   = true;
      $vars->display->listing = true;
      $vars->display->selection = true;
      $vars->display->treetable = true;

      $vars->label->de  = 'Prozess Status';
      $vars->label->en  = 'Process Status';

      $vars->description->de   = "Status für {$vars->entity->label->de}";
      $vars->description->en   = "Status for {$vars->entity->label->en}";

      $replaced = $this->replaceDefinition($vars->parse(), $statement, $parentNode);

    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $codeStack = new TArray();

    $xml = <<<XMLS

  <component 
    type="selectbox" 
    name="{$simpleStatement['process']}" 
    src="{$vars->target}"  >

    <label>
      <text lang="de" >{$vars->entity->label->de} Status</text>
      <text lang="en" >{$vars->entity->label->en} status</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} Status</text>
      <text lang="en" >{$vars->entity->label->en} status</text>
    </description>

    <id name="rowid" />
    
    <order_by>
      <field name="m_order" />
    </order_by>
    
    <fields>
      <field name="label" />
    </fields>
    
    <filter>

      <check type="path" >
       <code>
{$vars->target}.id_process.access_key == "{$simpleStatement['process']}"
       </code>
        
      </check>
    
    </filter>

  </component>

XMLS;

    $this->addRootNode( 'Component', $xml );

    return $replaced;

  }//end public function interpret */


}//end class LibGenfDefinitionProcessStatus
