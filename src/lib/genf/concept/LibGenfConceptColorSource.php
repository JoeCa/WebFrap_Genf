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
class LibGenfConceptColorSource
  extends LibGenfConcept
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param SimpleXmlElement $statement
   * @return boolean
   */
  public function interpret( $conceptNode )
  {

    $type = $this->parentType( $conceptNode );

    switch( $type )
    {
      case 'entity':
      {
        return $this->interpretEntity( $conceptNode );
        break;
      }
    }

    return array();


  }//end public function interpret */

  /**
   * @param DOMNode $conceptNode
   */
  public function interpretEntity( $conceptNode )
  {

    $entity = $this->getParent( $conceptNode );
    
    $simpleEntity = simplexml_import_dom( $entity );
    $simpleConcept = simplexml_import_dom( $conceptNode ); 
    
    $codeStack = new TCodeStack();
    
    if( isset( $simpleConcept->highlight_able ) )
    {
      
      $codeStack->attrHighlight = <<<ATTRS
      
        <attribute name="bg_color_highlight" is_a="color" >
          <label>
            <text lang="de" >Hintergrundfarbe Aktiv</text>
            <text lang="en" >Background Color Active</text>
          </label>
          
          <categories main="color_source" />
          
        </attribute>
        
        <attribute name="text_color_highlight" is_a="color" >
          <label>
            <text lang="de" >Text Farbe Aktiv</text>
            <text lang="en" >Font Color Active</text>
          </label>
          <categories main="color_source" />
        </attribute>
        
        <attribute name="border_color_highlight" is_a="color" >
          <label>
            <text lang="de" >Rahmen Farbe Aktiv</text>
            <text lang="en" >Border Color Active</text>
          </label>
          <categories main="color_source" />
        </attribute>
ATTRS;

    }
    
    if( isset( $simpleConcept->select_able ) )
    {
      
      $codeStack->attrSelect = <<<ATTRS
      
        <attribute name="bg_color_selected" is_a="color" >
          <label>
            <text lang="de" >Hintergrundfarbe Markiert</text>
            <text lang="en" >Background Color Selected</text>
          </label>
          
          <categories main="color_source" />
          
        </attribute>
        
        <attribute name="text_color_selected" is_a="color" >
          <label>
            <text lang="de" >Text Farbe Markiert</text>
            <text lang="en" >Font Color Selected</text>
          </label>
          <categories main="color_source" />
        </attribute>
        
        <attribute name="border_color_selected" is_a="color" >
          <label>
            <text lang="de" >Rahmen Farbe Markiert</text>
            <text lang="en" >Border Color Selected</text>
          </label>
          <categories main="color_source" />
        </attribute>
ATTRS;

    }
    
    if( isset( $simpleConcept->stripped ) )
    {
      
      $codeStack->attrStripped = <<<ATTRS
      
        <attribute name="bg_color_2" is_a="color" >
          <label>
            <text lang="de" >Hintergrundfarbe 2</text>
            <text lang="en" >Background Color 2</text>
          </label>
          
          <categories main="color_source" />
          
        </attribute>
        
        <attribute name="text_color_2" is_a="color" >
          <label>
            <text lang="de" >Text Farbe 2</text>
            <text lang="en" >Font Color 2</text>
          </label>
          <categories main="color_source" />
        </attribute>
        
        <attribute name="border_color_2" is_a="color" >
          <label>
            <text lang="de" >Rahmen Farbe 2</text>
            <text lang="en" >Border Color 2</text>
          </label>
          <categories main="color_source" />
        </attribute>
ATTRS;

    }

    $xml = <<<XML

    <entity name="{$simpleEntity['name']}"  >

      <attributes>
      
         <attribute name="bg_color" is_a="color" >
          <label>
            <text lang="de" >Hintergrundfarbe</text>
            <text lang="en" >Background Color</text>
            <text lang="af" >Agtergrondkleur</text>
            <text lang="yi" >באַקקגראָונדקאָלאָר</text>
            <text lang="fr" >Couleur de Fond</text>
            <text lang="it" >Colore di Sfondo</text>
            <text lang="ma" >боја на позадината</text>
            <text lang="pl" >kolor tła</text>
            <text lang="es" >el color de fondo</text>
          </label>
          
          <categories main="color_source" />
          
        </attribute>
        
        <attribute name="text_color" is_a="color" >
          <label>
            <text lang="de" >Text Farbe</text>
            <text lang="en" >Font Color</text>
            <text lang="fr" >Couleur du Texte</text>
            <text lang="es" >el color del texto</text>
          </label>
          <categories main="color_source" />
        </attribute>
        
        <attribute name="border_color" is_a="color" >
          <label>
            <text lang="de" >Rahmen Farbe</text>
            <text lang="en" >Border Color</text>
            <text lang="fr" >couleur de la bordure</text>
            <text lang="es" >borde de color</text>
            <text lang="pl" >kolor obramowania</text>
          </label>
          <categories main="color_source" />
        </attribute>
        
{$codeStack->attrStripped}
{$codeStack->attrSelect}
{$codeStack->attrHighlight}
        
      </attributes>

    </entity>

XML;

    return $this->addEntity( $xml );

  }//end public function interpretEntity */



} // end class LibGenfConceptSortable
