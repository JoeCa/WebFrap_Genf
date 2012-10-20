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
class LibGenfDefinitionEnum
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
      $vars->display->action   = 'listing';

      $vars->uiElement->type  = 'selectbox';

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


    $xml = <<<XMLS
  <entity name="{$vars->target}" >

    <label>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </label>

    <categories main="maintenance" >
      <category name="admin" />
      <category name="{$vars->entity->asCat}" />
    </categories>

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
    </ui>

    <description>
      <text lang="de" >{$vars->entity->label->de} {$vars->label->de}</text>
      <text lang="en" >{$vars->entity->label->en} {$vars->label->en}</text>
    </description>

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

      <attribute is_a="name" ></attribute>
      <attribute is_a="access_key" ></attribute>
      <attribute is_a="description" ></attribute>
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

    <fields>
      <!-- id hat per default immer rowid  -->
      <id>rowid</id>
      <value>name</value>
      <title>title</title>
      <order>m_order</order>
    </fields>

  </component>

XMLS;

    $this->addComponent( $xml );

    return $replaced;

  }//end public function interpret */


} // end class LibGenfDefinitionEnum
