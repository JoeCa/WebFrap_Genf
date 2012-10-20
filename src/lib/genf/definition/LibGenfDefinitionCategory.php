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
class LibGenfDefinitionCategory
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

    $codeStack = new TArray();

    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'id_category';
      $vars->target     = "{$vars->entity->name}_category";

      $vars->label->de  = 'Kategorie';
      $vars->label->en  = 'Category';

      $vars->description->de  = 'Kategorie für '.$vars->entity->label->de;
      $vars->description->en  = 'Category for '.$vars->entity->label->en;

      $vars->uiElement->type  = 'selectbox';
      $vars->uiElement->position->priority  = '76';

      $vars->display->action   = 'listing';

      $replaced = $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    if( isset( $statement->properties->sortable )  )
    {
      $codeStack->codeSortable = '      <attribute is_a="meta_sort" ></attribute>';
    }

    if($vars->target)
      $typeName = $vars->target;
    else
      $typeName = $vars->entity->name.'_category';

    $modName = SParserString::getDomainName( $vars->target, true );
    
    if( isset( $simpelNode->properties->stain_able ) )
    {
      $codeStack->concepts = <<<CODE
        <concept name="color_source" ></concept>
CODE;
    }

    $xml = <<<XMLS
  <entity name="{$typeName}" relevance="d-3" >

    <label>
      <text lang="de" >{$vars->entity->label->de} Kategorie</text>
      <text lang="en" >{$vars->entity->label->en} Category</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} Kategorie</text>
      <text lang="en" >{$vars->entity->label->en} Category</text>
    </description>

    <!-- Zugriffsbrechtigung -->
    <access>
      <roles>
        <role name="maintenance" access_level="maintenance" />
        <role name="{$modName}_maintenance" access_level="maintenance" />
        <role name="admin"    access_level="admin" />
        <role name="{$modName}_admin"    access_level="admin" />
        <role name="user"     access_level="listing" />
        <role name="employee" access_level="listing" />
        <role name="{$modName}_staff" access_level="listing" />
      </roles>
    </access>

    <categories main="master_data" ></categories>
    
    <concepts>
      <concept name="tree" >
        <field name="id_parent"  />
      </concept>
      <concept name="sortable" />
      <concept name="i18n" />
{$codeStack->concepts}
    </concepts>

    <attributes>
      
      <attribute is_a="name" ></attribute>
      
      <attribute is_a="access_key" ></attribute>
      
      <attribute is_a="title" >
        <display>
          <listing />
          <selection />
        </display>
        <search type="like" />
      </attribute>
      
      <attribute is_a="description" >
        <display>
          <listing />
          <selection />
        </display>
        <search type="like" />
      </attribute>
      
{$codeStack->codeSortable}

    </attributes>

  </entity>

XMLS;

    $this->addEntity( $xml );

    $xml = <<<XMLS

  <component type="selectbox" name="{$typeName}" src="{$typeName}"  >

    <label>
      <text lang="de" >{$vars->entity->label->de} Kategorie</text>
      <text lang="en" >{$vars->entity->label->en} Category</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} Kategorie</text>
      <text lang="en" >{$vars->entity->label->en} Category</text>
    </description>

    <id name="rowid" />
    
    <order_by>
      <field name="name" />
    </order_by>
    
    <fields>
      <field name="name" />
    </fields>

  </component>

XMLS;

    $this->addComponent( $xml );

    return $replaced;

  }//end public function interpret */


}//end class LibGenfDefinitionStatus
