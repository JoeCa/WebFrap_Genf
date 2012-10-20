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
class LibGenfDefinitionType
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

      $vars->name       = 'id_type';
      $vars->target     =  $vars->entity->name.'_type';

      $vars->search->type     = "multi";
      $vars->display->field   = 'name';
      $vars->display->table   = true;
      $vars->display->action  = 'listing';

      $vars->uiElement->type  = 'selectbox';
      $vars->uiElement->position->priority  = '74';

      if( 'id_type' != $vars->name )
      {
        $vars->label->de  = $this->builder->interpreter->nameToLabel($vars->name);
        $vars->label->en  = $this->builder->interpreter->nameToLabel($vars->name, true);
      }
      else
      {
        $vars->label->de  = "Type";
        $vars->label->en  = "Type";
      }

      $vars->description->de  = $vars->label->de." für {$vars->entity->label->de}";
      $vars->description->en  = $vars->label->en." for {$vars->entity->label->en}";


      $replaced =  $this->replaceDefinition($vars->parse(), $statement, $parentNode);
    }
    catch( LibGenf_Exception $e )
    {
      $this->remove($statement);
      return array();
    }

    $modName = SParserString::getDomainName( $vars->target, true );
    
    $simpelNode  = simplexml_import_dom( $statement);
    $codeStack   = new TCodeStack();
    
    $codeStack->attributes = '';
    
    if( isset( $simpelNode->properties->stain_able ) )
    {
      $concepts = <<<CODE
        <concept name="color_source" ></concept>
CODE;

      $codeStack->appendCode( 'concepts', $concepts );
    }

    

    $xml = <<<XMLS
  <entity name="{$vars->target}" relevance="d-3" >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </label>
    
    <categories main="master_data" ></categories>

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

    <semantic>
      <data_volume>xxsmall</data_volume>
    </semantic>

    <ui>
      <default>
        <window size="small" />
        <body   type="plain" />
      </default>
      <create>
        <window size="small" />
        <body   type="plain" />
      </create>
      <edit>
        <window size="small" />
        <body   type="plain" />
      </edit>
      <table>
        <window size="small" />
        <body   type="plain" />
      </table>
      <selection>
        <window size="small" />
        <body   type="plain" />
      </selection>

      <list>
        <table>
          <footer type="simple" />
        </table>

        <order_by>
          <field
            name="name"  />
        </order_by>
      </list>

    </ui>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </description>

    <properties>
      <autocomplete field="name" ></autocomplete>
    </properties>
    
    <concepts>
      <concept name="i18n" ></concept>
      <concept name="sortable" ></concept>
{$codeStack->concepts}
    </concepts>

    <attributes>

      <categories>
         <category name="default"  >

           <layout type="auto" cols="1" ></layout>

           <label>
             <text key="en" >{$vars->label->de}</text>
             <text key="en" >{$vars->label->en}</text>
           </label>

         </category>
      </categories>

      <attribute is_a="name" >
        <concepts>
          <concept name="i18n" ></concept>
        </concepts>
      </attribute>
      <attribute is_a="access_key" ></attribute>
{$codeStack->attributes}
      <attribute is_a="description" >
        <concepts>
          <concept name="i18n" ></concept>
        </concepts>
        <display>
          <listing />
          <selection />
        </display>
        <search type="like" />
      </attribute>
      <attribute is_a="meta_order" ></attribute>
    </attributes>

  </entity>

XMLS;

    $this->addRootNode( 'Entity', $xml );

    $xml = <<<XMLS

  <component type="selectbox" name="{$vars->target}" src="{$vars->target}"  >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </description>

    <id name="rowid" />
    
    <order_by>
      <field name="m_order" />
    </order_by>
    
    <fields>
      <field name="name" />
    </fields>

  </component>

XMLS;

    $this->addComponent( $xml );

    return $replaced;

  }//end public function interpret */


} // end class LibGenfDefinitionType
