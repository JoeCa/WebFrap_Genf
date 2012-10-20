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
abstract class LibCartridgeUiElement
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

 /**
  *
  * @var LibGenfTreeNodeAttribute
  */
  protected $attribute ;

  /**
  *
  * @var LibCartridgeI18n
  */
  public $i18nPool = null;

  /**
   *
   * @var unknown_type
   */
  public $uiValid = array
  (
    'int'       => 'true',
    'cname'     => 'true',
    'email'     => 'true',
    'url'       => 'true',
    'numeric'   => 'true',
    'password'  => 'true',
  );

  /**
   *
   * @var array
   */
  public $typeMap = array
  (
    'integer'     => 'int',
    'int'         => 'int',
    'smallint'    => 'int',
    'bigint'      => 'int',
    'numeric'     => 'numeric',
    'text'        => 'text',
    'varchar'     => 'text',
    'date'        => 'date',
    'time'        => 'time',
    'timestamp'   => 'timestamp',
    'boolean'     => 'checkbox'
  );

  /**
  *
  * @var LibGenfBuild
  */
  protected $builder  = null;

  /**
  *
  * @var LibGenfTreeroot
  */
  protected $node     = null;

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

   /**
    * @param LibGenfTreeNodeAttribute $attribute
    * @param string $keyName
    * @param $node
    */
  public function __construct( $attribute, $node )
  {

    $this->attribute  = $attribute;
    $this->node       = $node;

    $this->i18nPool   = LibCartridgeI18n::getInstance();
    $this->builder    = LibGenfBuild::getInstance();

  }//end public function __construct */


  /**
   *
   * @param string $vSize
   * @param string $hSize
   * @return array
   */
  protected function parseKeynames( $width = null, $height = null, $search = false, $assignForm = true )
  {

    $attrLabel  = ucfirst( $this->attribute->label() );
    //$attrIdent  = $this->attribute->identifier();
    $attrName   = $this->attribute->name();

    $name = $this->node->getName();
    $tmp  = new TArray();

    $tmp->ident   = $this->attribute->identifier();
    $tmp->keyName = $this->attribute->name();
    $tmp->label   = $this->attribute->label();

    $keyTitle = $name->i18nText;
    $keyLabel = $name->i18nText;

    
    if( $search  )
    {
      $tmp->i18nTitle  = '$this->view->i18n->l( '."'Search for {$attrLabel} ({$name->label})', '{$keyTitle}'".' )';
    }
    else 
    {
      $tmp->i18nTitle  = '$this->view->i18n->l( '."'Insert value for {$attrLabel} ({$name->label})', '{$keyTitle}'".' )';
    }
    
    $tmp->i18nLabel  = '$this->view->i18n->l( '."'{$attrLabel}', '{$keyLabel}'".' )';


    //$tmp->i18nTitle  = $this->i18nPool->langKey($keyTitle,array(),$attrLabel);
    //$tmp->i18nLabel  = $this->i18nPool->langKey($keyLabel,array(),$attrLabel);

    if( $search && 'multi' === $this->attribute->search() )
    {
      $tmp->itemName      = "\$this->keyName.'[{$attrName}][]'";
    }
    else
    {
      $tmp->itemName      = "\$this->keyName.'[{$attrName}]'";
    }

    $tmp->itemId        = "'wgt-input-'.\$this->keyName.'_{$attrName}'.(\$this->suffix?'-'.\$this->suffix:'')";
    $tmp->plainId       = "wgt-input-'.\$this->keyName.'_{$attrName}";
    $tmp->itemTemplate  = "'input'.\$this->prefix.'{$tmp->ident}'";


    $tmp->class = 'wcm wcm_ui_tip';

    if( !$search )
    {
      if( $this->attribute->required() )
        $tmp->class .= ' valid_required';
    }

    $validator = $this->attribute->validator();
    
    if( $validator && isset( $this->uiValid[$validator] )  )
      $tmp->class .= ' valid_'.$validator;

    $uiSize = $this->attribute->uiElement->size( $width, $height );

    $tmp->width = $uiSize->width;

    if( '' != trim($uiSize->width)  )
      $tmp->class .= ' '.$uiSize->width;

    if( '' != trim($uiSize->height)  )
      $tmp->class .= ' '.$uiSize->height.'-height';

    // set the search class
    if( $search  )
      $tmp->class .= ' wcm_req_search wgt-no-save';

    /*
    if( '' != trim($styleClass)  )
      $tmp->class = ' '.$styleClass;
    */

    if( $assignForm  )
    {
      
      if( $search )
      {
        if( '' != trim( $tmp->class ) )
          $tmp->class = NL."          'class'     => '".trim($tmp->class)."'.(\$this->assignedForm?' fparam-'.\$this->assignedForm:''),";
        else
          $tmp->class = NL."          'class'     => (\$this->assignedForm?'fparam-'.\$this->assignedForm:''),";
      }
      else 
      {
        if( '' != trim( $tmp->class ) )
          $tmp->class = NL."          'class'     => '".trim($tmp->class)."'.(\$this->assignedForm?' asgd-'.\$this->assignedForm:''),";
        else
          $tmp->class = NL."          'class'     => (\$this->assignedForm?'asgd-'.\$this->assignedForm:''),";
      }

    }
    else
    {
      if( '' != trim( $tmp->class ) )
        $tmp->class = NL."          'class'     => '".trim($tmp->class)."',";
      else
        $tmp->class = '';
    }

    $tmp->classForm = NL."          'class'     => (\$this->assignedForm?'asgd-'.\$this->assignedForm:''),";
    
    return $tmp;

  }//end protected function parseKeynames */


////////////////////////////////////////////////////////////////////////////////
// abstract Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param boolean $search
   * @param boolean $readOnly
   */
  public abstract function asInlineCode( $search = false, $readOnly = false );

  /**
   * @param boolean $search
   * @param boolean $readOnly
   */
  public abstract function asMethod( $search = false, $readOnly = false );

  /**
   * @param string $key
   * @param boolean $search
   * @param boolean $readOnly
   */
  public abstract function parseElement( $key, $search = false, $readOnly = false );


}//end abstract class LibCartidgeUiElement
