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
class LibGenfDefinitionStatus
  extends LibGenfDefinition
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DOMNode $statement
   * @return boolean
   */
  public function interpret( $statement, $parentNode )
  {

    $replaced = null;


    try
    {
      $vars = $this->checkAttribute( $statement, $parentNode );

      $vars->name       = 'id_status';
      $vars->target     = $vars->entity->name.'_status';

      if( 'id_status' != $vars->name )
      {
        $vars->label->de  = $this->builder->interpreter->nameToLabel( $vars->name );
        $vars->label->en  = $this->builder->interpreter->nameToLabel( $vars->name, true );
        $vars->label->fr  = $this->builder->interpreter->nameToLabel( $vars->name, true );
      }
      else
      {
        $vars->label->de  = "Status";
        $vars->label->en  = "Status";
        $vars->label->fr  = "Etat";
      }


      $vars->description->de  = $vars->label->de.' für '.$vars->entity->label->de;
      $vars->description->en  = $vars->label->en.' for '.$vars->entity->label->en;
      $vars->description->fr  = $vars->label->fr.' pour '.$vars->entity->label->fr;

      $vars->uiElement->type  = 'selectbox';
      $vars->uiElement->position->priority = '75';

      $vars->display->field   = 'name';
      $vars->display->table   = true;
      $vars->display->action   = 'listing';

      $vars->search->type     = 'multi';

      $replaced = $this->replaceDefinition( $vars->parse(), $statement, $parentNode );
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
    $codeStack->concepts   = '';

    if( isset( $simpelNode->properties->stain_able ) )
    {

      $codeStack->concepts = <<<CODE
     <concept name="color_source" ></concept>
CODE;

    }


    $xml = <<<XMLS
  <entity name="{$vars->target}" relevance="d-3" >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </description>

    <info>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </info>

   <concepts>
    <concept name="i18n" ></concept>
{$codeStack->concepts}
  </concepts>

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
      <listing>
        <window size="small" />
        <body   type="plain" />
      </listing>

      <list>
        <listing>
          <footer type="simple" />
        </listing>

        <order_by>
          <field
            name="m_order" />
        </order_by>
      </list>
    </ui>

    <semantic>
      <data_volume>xxsmall</data_volume>
    </semantic>

    <categories main="master_data" >
    </categories>

    <properties>
      <autocomplete field="name" ></autocomplete>
    </properties>

    <attributes>

      <categories>
         <category name="default"  >

           <layout type="auto" cols="1" ></layout>

           <label>
             <text key="de" >{$vars->label->de}</text>
             <text key="en" >{$vars->label->en}</text>
             <text key="fr" >{$vars->label->fr}</text>
           </label>

         </category>
      </categories>

      <attribute is_a="name" >
        <concepts>
          <concept name="i18n" />
        </concepts>
      </attribute>

      <attribute is_a="access_key" ></attribute>
{$codeStack->attributes}
      <attribute is_a="description" >
        <concepts>
          <concept name="i18n" />
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

  <component
    type="selectbox"
    name="{$vars->target}"
    src="{$vars->target}"  >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
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

    $this->addRootNode( 'Component', $xml );

    /**

  <!--
    Be aware of possible conflicts! Access Key is a reserved keyword
    Never use it as name in an entity!
  -->
     */

    $xml = <<<XMLS

  <component
    type="selectbox"
    name="{$vars->target}_access_key"
    src="{$vars->target}"  >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </label>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
      <text lang="fr" >{$vars->entity->label->fr} {$vars->label->fr}</text>
    </description>

    <id name="access_key" />

    <order_by>
      <field name="m_order" />
    </order_by>

    <fields>
      <field name="name" />
    </fields>

  </component>

XMLS;

    $this->addRootNode( 'Component', $xml );

    return $replaced;

  }//end public function interpret */


}//end class LibGenfDefinitionStatus
