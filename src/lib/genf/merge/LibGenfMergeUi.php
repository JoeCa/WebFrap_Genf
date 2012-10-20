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
class LibGenfMergeUi
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeUi
   */
  private static $instance = null;


////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfTree $tree
   * @return  LibGenfMergeUi
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeUi($tree);

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// merge logic
////////////////////////////////////////////////////////////////////////////////


  /**
   *
   * @param DomNode <ui> $oldNode
   * @param DomNode <ui> $newNode
   * @return unknown_type
   */
  public function merge( $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    $tmpChilds = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[] = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {

        case 'properties':
        {
          $this->mergeProperties( $oldNode , $childNode );
          break;
        }
        case 'list':
        {
          $this->mergeList( $oldNode , $childNode );
          break;
        }
        case 'form':
        {
          $this->mergeForm( $oldNode , $childNode );
          break;
        }
        case 'visibility':
        {
          $this->mergeVisibility( $oldNode , $childNode );
          break;
        }

        default:
        {
          $this->mergeGenericElement( $oldNode , $childNode );
          break;
        }

      }//end switch

    }//end foreach( $newNode->childNodes as $childNode )

  }//end public function merge */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   *
   * @param DomNode <ui>  $oldNode
   * @param DomNode <ui/form> $newNode
   * @return unknown_type
   */
  public function mergeForm( $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './form', $oldNode  );
    if( !$tmpListOld->length )
    {
      // importieren des neuen attributes in das full model
      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldNode->appendChild($newNode);
    }
    else
    {

      $oldParent = $tmpListOld->item(0);

      $tmpChilds = array();
      foreach( $newNode->childNodes as $childNode )
      {
        // skip invalid childnodes
        if( $childNode->nodeType != XML_ELEMENT_NODE )
          continue;

        $tmpChilds[] = $childNode;
      }

      foreach( $tmpChilds as $childNode )
      {

        switch( $childNode->nodeName )
        {

          case 'create':
          {
            $this->mergeFormContext( 'create', $oldParent, $childNode );
            break;
          }
          case 'edit':
          {
            $this->mergeFormContext( 'edit', $oldParent, $childNode );
            break;
          }
          default:
          {
            $builder = $this->getBuilder();
            $builder->error('Merge: Got unexpected Node '.$childNode->nodeName.' in '.__METHOD__);
            break;
          }

        }//end switch

      }//end foreach( $newNode->childNodes as $childNode )
    }

  }//end public function mergeForm */


  /**
   *
   * @param DomNode <ui>  $oldNode
   * @param DomNode <ui/form> $newNode
   * @return unknown_type
   */
  public function mergeFormContext( $context, $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // importieren des neuen attributes in das full model
    if($needImport)
      $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

    // asume references exists
    $tmpListOld     = $oldXpath->query( './'.$context, $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldNode->appendChild($newNode);
    }
    else
    {
      $oldChild = $tmpListOld->item(0);
      $oldNode->replaceChild($newNode,$oldChild);
    }

  }//end public function mergeFormContext */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param DomNode <ui> $oldNode
   * @param DomNode <ui> $newNode
   * @return void
   */
  public function mergeList( $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './list', $oldNode  );
    if( !$tmpListOld->length )
    {
      // importieren des neuen attributes in das full model
      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldNode->appendChild($newNode);
    }
    else
    {

      $oldParent = $tmpListOld->item(0);

      $tmpChilds = array();
      foreach( $newNode->childNodes as $childNode )
      {
        // skip invalid childnodes
        if( $childNode->nodeType != XML_ELEMENT_NODE )
          continue;

        $tmpChilds[] = $childNode;
      }

      foreach( $tmpChilds as $childNode )
      {

        switch( $childNode->nodeName )
        {

          case 'table':
          {
            $this->mergeListContext( 'table', $oldParent, $childNode );
            break;
          }
          case 'listing':
          {
            $this->mergeListContext( 'listing', $oldParent, $childNode );
            break;
          }
          case 'selection':
          {
            $this->mergeListContext( 'selection', $oldParent, $childNode );
            break;
          }
          case 'grid':
          {
            $this->mergeListContext( 'grid', $oldParent, $childNode );
            break;
          }
          case 'treetable':
          {
            $this->mergeListContext( 'treetable', $oldParent, $childNode );
            break;
          }
          case 'tree':
          {
            $this->mergeListContext( 'tree', $oldParent, $childNode );
            break;
          }
          case 'blocklist':
          {
            $this->mergeListContext( 'blocklist', $oldParent, $childNode );
            break;
          }
          case 'order_by':
          {
            $this->mergeListContext( 'order_by', $oldParent, $childNode );
            break;
          }
          default:
          {
            $builder = $this->getBuilder();
            $builder->error('Merge: Got unexpected Node '.$childNode->nodeName.' in '.__METHOD__);
            break;
          }

        }//end switch

      }//end foreach( $newNode->childNodes as $childNode )
    }

  }//end public function mergeList */


  /**
   *
   * @param DomNode <ui>  $oldNode
   * @param DomNode <ui/list> $newNode
   * @return unknown_type
   */
  public function mergeListContext( $context, $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // importieren des neuen attributes in das full model
    if($needImport)
      $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

    // asume references exists
    $tmpListOld     = $oldXpath->query( './'.$context, $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldNode->appendChild($newNode);
    }
    else
    {
      $oldChild = $tmpListOld->item(0);
      $oldNode->replaceChild($newNode,$oldChild);
    }

  }//end public function mergeListContext */

/*//////////////////////////////////////////////////////////////////////////////
//
//////////////////////////////////////////////////////////////////////////////*/

  /**
   * @param DomNode <ui> $oldNode
   * @param DomNode <ui/properties> $newNode
   * @return unknown_type
   */
  public function mergeProperties( $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './properties', $oldNode  );
    if( !$tmpListOld->length )
    {
      // importieren des neuen attributes in das full model
      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldNode->appendChild($newNode);
    }
    else
    {

      $oldParent = $tmpListOld->item(0);

      $tmpChilds = array();
      foreach( $newNode->childNodes as $childNode )
      {
        // skip invalid childnodes
        if( $childNode->nodeType != XML_ELEMENT_NODE )
          continue;

        $tmpChilds[] = $childNode;
      }

      foreach( $tmpChilds as $childNode )
      {

        // importieren des neuen attributes in das full model
        if($needImport)
          $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

        // asume references exists
        $tmpListOld     = $oldXpath->query( './'.$childNode->nodeName, $oldNode  );
        if( !$tmpListOld->length )
        {
          $oldParent->appendChild($childNode);
        }
        else
        {
          $oldChild = $tmpListOld->item(0);
          $oldParent->replaceChild($childNode,$oldChild);
        }

      }//end foreach
    }

  }//end public function mergeProperties */

  /**
   * @param DomNode <ui> $oldNode
   * @param DomNode <ui/properties> $newNode
   * @return unknown_type
   */
  public function mergeVisibility( $oldNode, $newNode  )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './visibility', $oldNode  );
    if( !$tmpListOld->length )
    {
      // importieren des neuen attributes in das full model
      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldNode->appendChild($newNode);
    }
    else
    {

      $oldParent = $tmpListOld->item(0);

      $tmpChilds = array();
      foreach( $newNode->childNodes as $childNode )
      {
        // skip invalid childnodes
        if( $childNode->nodeType != XML_ELEMENT_NODE )
          continue;

        $tmpChilds[] = $childNode;
      }

      foreach( $tmpChilds as $childNode )
      {

        if( 'profiles' == $childNode->nodeName )
        {
          $this->mergeVisibilityProfiles( $oldParent, $childNode  );
        }
        else
        {
          $this->builder->error( "Got unexpected node ".$childNode->nodeName." in visbility " );
        }

      }//end foreach
    }

  }//end public function mergeVisibility */

  /**
   * @param DomNode <ui> $oldNode
   * @param DomNode <ui/properties> $newNode
   * @return unknown_type
   */
  public function mergeVisibilityProfiles( $oldNode, $newNode  )
  {



    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->evaluate( './profiles', $oldNode  );

    if( !$tmpListOld->length )
    {
      // importieren des neuen attributes in das full model
      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);



      $oldNode->appendChild($newNode);
    }
    else
    {

      $oldParent = $tmpListOld->item(0);

      $tmpChilds = array();
      foreach( $newNode->childNodes as $childNode )
      {
        // skip invalid childnodes
        if( $childNode->nodeType != XML_ELEMENT_NODE )
          continue;

        $tmpChilds[] = $childNode;
      }

      foreach( $tmpChilds as $childNode )
      {

        // importieren des neuen attributes in das full model
        if($needImport)
          $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

        // asume references exists
        $tmpListOld     = $oldXpath->query( './profile[@name="'.$childNode->nodeName.'"]', $oldNode  );
        if( !$tmpListOld->length )
        {
          $oldParent->appendChild( $childNode );
        }

      }//end foreach
    }

  }//end public function mergeVisibility */

}//end class LibGenfMergeUi
