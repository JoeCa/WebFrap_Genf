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
 * Bdl Node
 */
class BdlFile
{
////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////
  
  /**
   * @var DOMDocument
   */
  public $document = null;
  
  /**
   * @var DOMXPath
   */
  public $xpath = null;
  
  /**
   * @var string
   */
  public $fileName = null;
  
////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param string $file
   */
  public function __construct( $file )
  {
    
    $this->fileName = $file;
    
    $this->document = new DOMDocument();
    $this->document->load( $file );
    
    $this->xpath = new DOMXPath( $this->document );
    
  }// public function __construct */
  
  /**
   * @param string $path
   * @return DOMElement
   */
  public function getNodeByPath( $path )
  {
    $node = $this->xpath( $path );
    
    if( $node->length )
      return $node->item( 0 );
      
    return null;
    
  }//end public function getNodeByPath */

  
  /**
   * @return string
   */
  public function save()
  {
    $this->document->save( $this->fileName );
  }//end public function save */
  
  /**
   * @return string
   */
  public function xpath( $query, $node = null )
  {
    
    if( $node )
      return $this->xpath->evaluate( $query, $node );
    else 
      return $this->xpath->evaluate( $query );

  }//end public function xpath */
  
  /**
   * @return string
   */
  public function guessType()
  {
    
    if( $this->xpath->evaluate( '/bdl/entities/entity' )->length )
      return 'entity';
      
    if( $this->xpath->evaluate( '/bdl/managements/management' )->length )
      return 'management';
      
    if( $this->xpath->evaluate( '/bdl/components/component' )->length )
      return 'component';
      
    if( $this->xpath->evaluate( '/bdl/messages/message' )->length )
      return 'message';

    if( $this->xpath->evaluate( '/bdl/docus/docu' )->length )
      return 'docu';

    if( $this->xpath->evaluate( '/bdl/documents/document' )->length )
      return 'document';
      
    if( $this->xpath->evaluate( '/bdl/enums/enum' )->length )
      return 'enum';
      
    if( $this->xpath->evaluate( '/bdl/modules/module' )->length )
      return 'module';
    
    if( $this->xpath->evaluate( '/bdl/services/service' )->length )
      return 'service';
      
    if( $this->xpath->evaluate( '/bdl/items/item' )->length )
      return 'item';
      
    if( $this->xpath->evaluate( '/bdl/actions/action' )->length )
      return 'action';
      
    if( $this->xpath->evaluate( '/bdl/processes/process' )->length )
      return 'process';
      
    if( $this->xpath->evaluate( '/bdl/profiles/profile' )->length )
      return 'profile';
      
    if( $this->xpath->evaluate( '/bdl/menus/subtree' )->length )
      return 'subtree';
      
    if( $this->xpath->evaluate( '/bdl/menus/node' )->length )
      return 'menu_node';
      
    if( $this->xpath->evaluate( '/bdl/menus/tree' )->length )
      return 'tree';
      
    if( $this->xpath->evaluate( '/bdl/widgets/widget' )->length )
      return 'widget';

    if( $this->xpath->evaluate( '/bdl/roles/role' )->length )
      return 'role';

    if( $this->xpath->evaluate( '/bdl/desktops/desktop' )->length )
      return 'desktop';

    if( $this->xpath->evaluate( '/bdl/navigations/navigation' )->length )
      return 'navigation';
      
    return null;
    
  }//end public function guessType */
  
} // end class BdlFile

