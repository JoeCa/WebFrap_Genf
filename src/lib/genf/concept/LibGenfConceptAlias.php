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
class LibGenfConceptAlias
  extends LibGenfConcept
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  
  /**
   *
   * @param DOMNode $conceptNode
   * @return array
   */
  public function interpret( $conceptNode )
  {

    $type = $this->parentType( $conceptNode );

    if( 'attribute' != $type )
    {
      Error::addError( "Invalid concept alias on a non attribute" );
      return array();
    }
    
    if( !isset( $conceptNode->parentNode->parentNode->parentNode->parentNode ) )
    {
      Error::addError( "Got no entity for the alias concept attribute" );
      return array();
    }
    
    // entity/attributes/attribute/concepts/concept
    $entityNode = $conceptNode->parentNode->parentNode->parentNode->parentNode;
    
    $name = $entityNode->getAttribute( 'name' );
    
 $xml = <<<XMLS
    <entity name="{$name}_alias" >
        
        <docu>
          <text lang="de" >Alias für die Entität {$name}</text>
          <text lang="en" >Alias for the Entity {$name}</text>
        </docu>

        <description>
          <text lang="de" >Alias für die Entität {$name}</text>
          <text lang="en" >Alias for the Entity {$name}</text>
        </description>
        
        <attributes>
          
          <attribute name="name" type="text" size="120" >
            <display>
              <listing />
            </display>
          </attribute>
          
          <attribute name="id_{$name}" target="{$name}" >

          </attribute>
          
        </attributes>

    </entity>
    

    <entity name="{$name}" >

        <references>
           
          <ref name="ent_alias" binding="connected" relation="manyToMany" >
            
            <label>
              <text lang="de" >Alias</text>
              <text lang="en" >Alias</text>
            </label>
            
            <plabel>
              <text lang="de" >Aliase</text>
              <text lang="en" >Aliases</text>
            </plabel>
            
            <src name="{$name}" />
            <target name="{$name}_alias" id="id_{$name}" />
          </ref>
          
        </references>

    </entity>

XMLS;

    return $this->builder->getEntityRoot()->add( $xml );


  }//end public function interpret */
  

  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   * /
  public function interpret( $conceptNode  )
  {

    $entity   = $this->getMainNode( $conceptNode );
    //$entName  = $entity->getAttribute('name');
    
    $parent = simplexml_import_dom( $entity );

    $vars = new TArray();


    $xml = <<<XMLS
    <entity name="{$parent['name']}_alias" >


        <description>
          <text lang="de" >Alias für die Entität {$parent['name']}</text>
          <text lang="en" >Alias for the Entity {$parent['name']}</text>
        </description>
        
        <attributes>
          <attribute is_a="name" >

          </attribute>
          <attribute name="id_{$parent['name']}" target="{$parent['name']}" >

          </attribute>
        </attributes>

    </entity>


XMLS;

    return $this->builder->getEntityRoot()->add( $xml );

  }//end public function interpret */


} // end class LibGenfDefinitionDescription
