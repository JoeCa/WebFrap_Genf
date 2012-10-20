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
 * @subpackage Genf
 */
abstract class LibCartridgeQuery
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
  *
  * @var LibGenfTreeNodeAttribute
  */
  protected $component = null;

  /**
  *
  * @var string
  */
  protected $keyName;

  /**
  *
  * @var unknown_type
  */
  public $i18nPool = null;


////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

   /**
    *
    */
  public function __construct( $attribute, $keyName, $node )
  {

    $this->attribute  = $attribute;
    $this->keyName    = $keyName;
    $this->node       = $node;

    $this->i18nPool   = LibCartridgeI18n::getInstance();

  }//end public function __construct */


  /**
   *
   * @param string $attribute
   * @return array
   */
  protected function parseKeynames( $sizeClass = null, $styleClass = null )
  {

    $attrLabel  = $this->attribute->label();
    $attrIdent  = $this->attribute->identifier();
    $attrName   = $this->attribute->name();

    $name = $this->node->getName();
    $tmp  = new TArray();

    $keyTitle = $name->i18nText.'.inputTitle'.$attrIdent;
    $keyLabel = $name->i18nText.'.inputLabel'.$attrIdent;

    $tmp->i18nTitle  = $this->i18nPool->langKey($keyTitle);
    $tmp->i18nLabel  = $this->i18nPool->langKey($keyLabel,array(),$attrLabel);

    if( $this->keyName[0] == '$' )
    {
      $tmp->itemName    =  $this->keyName.".'".'['.$attrName.']'."'";
      $tmp->itemId      =  "'wgt-input-'.$this->keyName.\$vext.'_$attrName'";
    }
    else
    {
      $tmp->itemName    =  "'".$this->keyName.'['.$attrName.']'."'";
      $tmp->itemId      =  "'wgt-input-$this->keyName'.\$vext.'_$attrName'";
    }

    $tmp->itemTemplate  = "'input'.\$this->preName.'{$attrIdent}'.\$vext";


    $tmp->class = '';

    if( $this->attribute->required() )
      $tmp->class .= ' valid_required';

    $validator = $this->attribute->validator();

    if( $validator && isset( $this->uiValid[$validator] )  )
      $tmp->class = ' valid_'.$validator;

    if( '' != trim($sizeClass)  )
      $tmp->class = ' '.$sizeClass;

    if( '' != trim($styleClass)  )
      $tmp->class = ' '.$styleClass;

    if( '' == trim( $tmp->class ) )
      $tmp->class = NL."        'class'     => '".$tmp->class."',";

    return $tmp;

  }//end protected function parseKeynames */


////////////////////////////////////////////////////////////////////////////////
// abstract Methodes
////////////////////////////////////////////////////////////////////////////////

  public abstract function parse();


}//end abstract class LibCartridgeQuery
